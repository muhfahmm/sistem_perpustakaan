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
}
