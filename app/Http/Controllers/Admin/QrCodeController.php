<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::latest()->paginate(12);
        return view('admin.qrcode.index', compact('books'));
    }

    public function scanner()
    {
        return view('admin.qrcode.scanner');
    }
}
