<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $totalLoans = DB::table('tb_loans')
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->count();

        $returnedLoans = DB::table('tb_loans')
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->where('status', 'returned')
            ->count();

        $overdueLoans = DB::table('tb_loans')
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->where('status', 'overdue')
            ->count();

        $recentReports = DB::table('tb_loans')
            ->join('tb_user', 'tb_user.id', '=', 'tb_loans.user_id')
            ->join('tb_books', 'tb_books.id', '=', 'tb_loans.book_id')
            ->select('tb_loans.*', 'tb_user.name as borrower', 'tb_books.title as book_title')
            ->whereBetween('tb_loans.loan_date', [$startDate, $endDate])
            ->latest('tb_loans.created_at')
            ->paginate(15);

        return view('admin.reports.index', compact('totalLoans', 'returnedLoans', 'overdueLoans', 'recentReports', 'startDate', 'endDate'));
    }
}
