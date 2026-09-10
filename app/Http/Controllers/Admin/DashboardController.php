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
            'books' => DB::table('books')->count(),
            'activeLoans' => DB::table('loans')->whereIn('status', $activeLoanStatuses)->count(),
            'activeMembers' => DB::table('users')->whereIn('role', ['petugas', 'user'])->where('is_active', true)->count(),
            'overdueLoans' => DB::table('loans')->where('status', 'overdue')->count(),
        ];

        $recentLoans = DB::table('loans')
            ->join('users', 'users.id', '=', 'loans.user_id')
            ->join('books', 'books.id', '=', 'loans.book_id')
            ->select('books.title', 'users.name as borrower', 'loans.loan_date', 'loans.status')
            ->latest('loans.created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentLoans'));
    }
}
