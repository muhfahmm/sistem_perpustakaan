<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use App\Services\LoanService;
use App\Exceptions\LoanException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::paginate(12);
        return view('admin.qrcode.index', compact('books'));
    }

    public function scanner()
    {
        return view('admin.qrcode.scanner');
    }

    public function processScan(Request $request): JsonResponse
    {
        $code = trim($request->input('code', ''));

        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Kode barcode kosong.'], 422);
        }

        // 1. Check if loan code (starts with LN-)
        if (str_starts_with(strtoupper($code), 'LN-')) {
            $loan = Loan::with(['user', 'book'])->where('kode_pinjam', $code)->first();
            if ($loan) {
                return response()->json([
                    'success' => true,
                    'type' => 'loan',
                    'message' => 'Data peminjaman ditemukan.',
                    'data' => [
                        'id' => $loan->id,
                        'loan_code' => $loan->kode_pinjam,
                        'status' => $loan->status,
                        'user_name' => $loan->user->nama ?? '-',
                        'user_phone' => $loan->user->telepon ?? '-',
                        'book_title' => $loan->book->judul ?? '-',
                        'loan_date' => $loan->tanggal_pinjam?->format('Y-m-d'),
                        'due_date' => $loan->jatuh_tempo?->format('Y-m-d'),
                        'return_date' => $loan->tanggal_kembali?->format('Y-m-d'),
                    ]
                ]);
            }
            return response()->json(['success' => false, 'message' => "Kode peminjaman {$code} tidak ditemukan."], 404);
        }

        // 2. Check if user/member code (starts with AGT-, or matches user ID, email, or phone)
        $cleanCode = preg_replace('/^AGT-/i', '', $code);
        $userQuery = User::query();
        if (is_numeric($cleanCode)) {
            $userQuery->where('id', $cleanCode)->orWhere('telepon', $code)->orWhere('telepon', $cleanCode);
        } else {
            $userQuery->where('email', $code)->orWhere('nama', 'like', "%{$code}%");
        }
        $user = $userQuery->first();

        if ($user) {
            $activeLoansCount = Loan::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved', 'borrowed', 'overdue'])
                ->count();

            return response()->json([
                'success' => true,
                'type' => 'member',
                'message' => "Anggota '{$user->nama}' terdeteksi.",
                'data' => [
                    'id' => $user->id,
                    'name' => $user->nama,
                    'email' => $user->email,
                    'phone' => $user->telepon,
                    'active_loans_count' => $activeLoansCount,
                    'max_loans' => (int) config('library.max_active_loans', 3),
                ]
            ]);
        }

        // 3. Check if Book (ISBN or ID or title match)
        $bookQuery = Book::query();
        if (preg_match('/^\d{10,13}$/', $code)) {
            $bookQuery->where('isbn', $code);
        } else if (is_numeric($code)) {
            $bookQuery->where('id', $code)->orWhere('isbn', $code);
        } else {
            $bookQuery->where('isbn', 'like', "%{$code}%")->orWhere('judul', 'like', "%{$code}%");
        }
        $book = $bookQuery->first();

        if ($book) {
            return response()->json([
                'success' => true,
                'type' => 'book',
                'message' => "Buku '{$book->judul}' terdeteksi.",
                'data' => [
                    'id' => $book->id,
                    'title' => $book->judul,
                    'author' => $book->penulis,
                    'isbn' => $book->isbn ?? '-',
                    'stock' => $book->stok,
                    'available' => $book->tersedia,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Barcode/Kode '{$code}' tidak terdaftar sebagai Anggota, Buku, atau Kode Pinjam."
        ], 404);
    }

    public function quickLoan(Request $request, LoanService $loanService): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:tb_user,id'],
            'book_id' => ['required', 'exists:tb_data_buku,id'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $adminId = (int) $request->user('admin')->id;
        $dueDate = $validated['due_date'] ?? now()->addDays((int) config('library.default_loan_days', 7))->toDateString();
        $idempotencyKey = 'admin_scan_' . $validated['user_id'] . '_' . $validated['book_id'] . '_' . time();

        try {
            // 1. Request loan
            $loan = $loanService->requestLoan((int) $validated['user_id'], (int) $validated['book_id'], $idempotencyKey);

            // 2. Direct approve loan by admin
            $approvedLoan = $loanService->approveLoan($loan, $adminId, $dueDate);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil dibuat dan disetujui secara otomatis.',
                'loan' => [
                    'loan_code' => $approvedLoan->kode_pinjam,
                    'user_name' => $approvedLoan->user->nama ?? '-',
                    'book_title' => $approvedLoan->book->judul ?? '-',
                    'due_date' => $approvedLoan->jatuh_tempo?->format('Y-m-d'),
                ]
            ]);
        } catch (LoanException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
