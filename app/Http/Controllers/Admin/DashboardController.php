<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $activeLoanStatuses = ['approved', 'borrowed', 'overdue'];

        $stats = [
            'books' => DB::table('tb_books')->count(),
            'activeLoans' => DB::table('tb_loans')->whereIn('status', $activeLoanStatuses)->count(),
            'activeMembers' => DB::table('tb_user')->where('is_active', true)->count(),
            'overdueLoans' => DB::table('tb_loans')->where('status', 'overdue')->count(),
        ];

        $recentLoans = DB::table('tb_loans')
            ->join('tb_user', 'tb_user.id', '=', 'tb_loans.user_id')
            ->join('tb_books', 'tb_books.id', '=', 'tb_loans.book_id')
            ->select('tb_books.title', 'tb_user.name as borrower', 'tb_loans.loan_date', 'tb_loans.status')
            ->latest('tb_loans.created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentLoans'));
    }
}
