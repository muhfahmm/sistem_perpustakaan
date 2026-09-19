<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LoanException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveLoanRequest;
use App\Models\Book;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_pinjam', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('nama', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('telepon', 'like', "%{$search}%");
                  })
                  ->orWhereHas('book', function ($bq) use ($search) {
                      $bq->where('judul', 'like', "%{$search}%")
                         ->orWhere('isbn', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'dipinjam') {
                $query->whereIn('status', ['borrowed', 'pending', 'overdue']);
            } elseif ($status === 'returned') {
                $query->where('status', 'returned');
            } else {
                $query->where('status', $status);
            }
        }

        $loans = $query->latest('id')
            ->paginate(10)
            ->withQueryString();

        $selectedUser = $request->filled('user_id') ? \App\Models\User::find($request->user_id) : null;

        return view('admin.loans.index', compact('loans', 'selectedUser'));
    }

    public function approve(ApproveLoanRequest $request, Loan $loan, LoanService $loanService): RedirectResponse
    {
        try {
            $loanService->approveLoan($loan, (int) $request->user('admin')->id, $request->string('due_date')->toString());
            return back()->with('success', 'Peminjaman berhasil disetujui.');
        } catch (LoanException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function reject(Loan $loan, LoanService $loanService): RedirectResponse
    {
        try {
            $loanService->rejectLoan($loan, (int) request()->user('admin')->id, request('notes'));
            return back()->with('success', 'Pengajuan peminjaman ditolak.');
        } catch (LoanException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        DB::transaction(function () use ($loan) {
            $statusVal = is_object($loan->status) ? $loan->status->value : $loan->status;
            if (in_array($statusVal, ['pending', 'borrowed', 'approved', 'overdue'], true)) {
                Book::where('id', $loan->buku_id)->increment('tersedia');
            }
            $loan->delete();
        });

        return back()->with('success', 'Data transaksi peminjaman berhasil dihapus dan stok buku dikembalikan.');
    }
}
