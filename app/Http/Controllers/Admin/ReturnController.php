<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LoanException;
use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReturnController extends Controller
{
    public function scan(Request $request, LoanService $loanService): JsonResponse
    {
        $data = $request->validate(['loan_code' => ['required', 'string', 'max:30']]);
        $lock = Cache::lock('scan-loan:'.$data['loan_code'], 5);

        if (!$lock->get()) {
            return response()->json(['success' => false, 'message' => 'Kode sedang diproses.'], 429);
        }

        try {
            $result = $loanService->returnBook($data['loan_code'], (int) $request->user('admin')->id);
            return response()->json([
                'success' => true,
                'duplicate' => $result['duplicate'],
                'message' => $result['message'],
                'loan_code' => $result['loan']->loan_code,
            ]);
        } catch (LoanException $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 422);
        } finally {
            $lock->release();
        }
    }
}
