<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LoanException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveLoanRequest;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;

class LoanController extends Controller
{
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
}
