<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LoanException;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        $users = User::where('status_aktif', 1)->orderBy('nama')->get();
        $books = Book::where('tersedia', '>', 0)->orderBy('judul')->get();
        return view('admin.scan.index', compact('users', 'books'));
    }

    public function lookupUser(Request $request): JsonResponse
    {
        $code = trim($request->input('code', ''));
        $name = trim($request->input('name', ''));
        $email = trim($request->input('email', ''));
        $phone = trim($request->input('phone', ''));

        if (!$code && !$email && !$phone) {
            return response()->json(['success' => false, 'message' => 'Harap isi Nama, Email, atau No. WA peminjam.'], 422);
        }

        $cleanCode = preg_replace('/^AGT-/i', '', $code);
        $userQuery = User::query();

        $normalizedPhone = '62' . preg_replace('/^(\+62|62|0)/', '', $phone ?: $code);
        if ($phone) {
            $userQuery->where('telepon', $normalizedPhone);
        } elseif ($email) {
            $userQuery->where('email', $email);
        } else if (is_numeric($cleanCode)) {
            $userQuery->where('id', $cleanCode)
                      ->orWhere('telepon', $code)
                      ->orWhere('telepon', $cleanCode)
                      ->orWhere('telepon', $normalizedPhone);
        } else {
            $userQuery->where('email', $code)
                      ->orWhere('nama', 'like', "%{$code}%")
                      ->orWhere('telepon', $normalizedPhone);
        }

        $user = $userQuery->first();

        if (!$user) {
            if ($name && $email && $phone) {
                // Auto register new borrower when clicking "Tambah Data Peminjam"
                $user = User::create([
                    'nama' => $name,
                    'email' => $email,
                    'telepon' => $normalizedPhone,
                    'status_aktif' => 1,
                ]);

                return response()->json([
                    'success' => true,
                    'created' => true,
                    'message' => "Peminjam baru '{$user->nama}' berhasil ditambahkan ke tabel.",
                    'user' => [
                        'id' => $user->id,
                        'nama' => $user->nama,
                        'email' => $user->email,
                        'telepon' => $user->telepon,
                        'active_loans_count' => 0,
                        'max_loans' => (int) config('library.max_active_loans', 3),
                        'remaining_quota' => (int) config('library.max_active_loans', 3),
                    ]
                ]);
            }

            return response()->json(['success' => false, 'message' => "Data siswa/anggota tidak ditemukan."], 404);
        }

        if (!$user->status_aktif) {
            return response()->json(['success' => false, 'message' => "Siswa '{$user->nama}' dalam status non-aktif."], 422);
        }

        $activeStatuses = ['pending', 'approved', 'borrowed', 'overdue'];
        $activeLoansCount = Loan::where('user_id', $user->id)
            ->whereIn('status', $activeStatuses)
            ->count();

        $maxLoans = (int) config('library.max_active_loans', 3);
        $remainingQuota = max(0, $maxLoans - $activeLoansCount);

        return response()->json([
            'success' => true,
            'created' => false,
            'message' => "Siswa '{$user->nama}' terdeteksi.",
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'telepon' => $user->telepon,
                'active_loans_count' => $activeLoansCount,
                'max_loans' => $maxLoans,
                'remaining_quota' => $remainingQuota,
            ]
        ]);
    }

    public function lookupBook(Request $request): JsonResponse
    {
        $code = trim($request->input('code', ''));
        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Kode ISBN/Buku tidak boleh kosong.'], 422);
        }

        $bookQuery = Book::query();
        if (preg_match('/^\d{10,13}$/', $code)) {
            $bookQuery->where('isbn', $code);
        } else if (is_numeric($code)) {
            $bookQuery->where('id', $code)->orWhere('isbn', $code);
        } else {
            $bookQuery->where('isbn', 'like', "%{$code}%")->orWhere('judul', 'like', "%{$code}%");
        }

        $book = $bookQuery->first();

        if (!$book) {
            return response()->json(['success' => false, 'message' => "Buku dengan ISBN/Kode '{$code}' tidak ditemukan."], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Buku '{$book->judul}' terdeteksi.",
            'book' => [
                'id' => $book->id,
                'judul' => $book->judul,
                'isbn' => $book->isbn ?? '-',
                'stok' => $book->stok,
                'tersedia' => $book->tersedia,
            ]
        ]);
    }

    public function storeOnsite(Request $request, LoanService $loanService): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:tb_user_peminjam,id'],
            'user_name' => ['nullable', 'string', 'max:100'],
            'user_email' => ['nullable', 'email', 'max:150'],
            'user_phone' => ['nullable', 'string', 'max:20'],
            'book_id' => ['required', 'exists:tb_data_buku,id'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $userId = $validated['user_id'] ?? null;
        if (!$userId) {
            if (empty($validated['user_name']) || empty($validated['user_email']) || empty($validated['user_phone'])) {
                return response()->json(['success' => false, 'message' => 'Harap lengkapi Nama, Email, dan No. WA peminjam.'], 422);
            }

            $user = User::where('email', $validated['user_email'])
                ->orWhere('telepon', $validated['user_phone'])
                ->first();

            if (!$user) {
                $user = User::create([
                    'nama' => $validated['user_name'],
                    'email' => $validated['user_email'],
                    'telepon' => $validated['user_phone'],
                    'status_aktif' => 1,
                ]);
            }
            $userId = $user->id;
        }

        $adminId = (int) $request->user('admin')->id;

        try {
            $loan = $loanService->createOnsiteLoan(
                (int) $userId,
                (int) $validated['book_id'],
                $adminId,
                $validated['due_date'],
                null
            );

            $loan->load(['user', 'book']);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman on-site berhasil diproses dan dicatat!',
                'user' => [
                    'id' => $loan->user->id,
                    'nama' => $loan->user->nama ?? '-',
                    'email' => $loan->user->email ?? '-',
                    'telepon' => $loan->user->telepon ?? '-',
                    'status_aktif' => $loan->user->status_aktif ?? 1,
                ],
                'loan' => [
                    'id' => $loan->id,
                    'kode_pinjam' => $loan->kode_pinjam,
                    'user_nama' => $loan->user->nama ?? '-',
                    'user_telepon' => $loan->user->telepon ?? '-',
                    'book_judul' => $loan->book->judul ?? '-',
                    'book_isbn' => $loan->book->isbn ?? '-',
                    'tanggal_pinjam' => $loan->tanggal_pinjam?->format('d M Y'),
                    'jatuh_tempo' => $loan->jatuh_tempo?->format('d M Y'),
                ]
            ]);
        } catch (LoanException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
