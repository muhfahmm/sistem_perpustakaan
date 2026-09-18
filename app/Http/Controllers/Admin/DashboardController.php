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
            'books' => DB::table('tb_data_buku')->count(),
            'activeLoans' => DB::table('tb_pinjaman')->whereIn('status', $activeLoanStatuses)->count(),
            'activeMembers' => DB::table('tb_user')->where('status_aktif', true)->count(),
            'overdueLoans' => DB::table('tb_pinjaman')->where('status', 'overdue')->count(),
        ];

        $recentLoans = DB::table('tb_pinjaman')
            ->join('tb_user', 'tb_user.id', '=', 'tb_pinjaman.user_id')
            ->join('tb_data_buku', 'tb_data_buku.id', '=', 'tb_pinjaman.buku_id')
            ->select('tb_data_buku.judul as title', 'tb_user.nama as borrower', 'tb_pinjaman.tanggal_pinjam as loan_date', 'tb_pinjaman.status')
            ->orderByDesc('tb_pinjaman.id')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentLoans'));
    }
}
