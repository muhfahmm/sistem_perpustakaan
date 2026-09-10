<?php

namespace App\Http\Controllers\User;

use App\Exceptions\LoanException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreLoanRequest;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function store(StoreLoanRequest $request, LoanService $loanService): RedirectResponse
    {
        try {
            $loan = $loanService->requestLoan(
                (int) $request->user('web')->id,
                (int) $request->integer('book_id'),
                $request->string('idempotency_key')->toString()
            );

            return back()->with('success', "Pengajuan {$loan->loan_code} berhasil dikirim.");
        } catch (LoanException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public static function idempotencyKey(): string
    {
        return (string) Str::uuid();
    }
}
