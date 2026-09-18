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

        $totalLoans = DB::table('tb_pinjaman')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->count();

        $returnedLoans = DB::table('tb_pinjaman')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->where('status', 'returned')
            ->count();

        $overdueLoans = DB::table('tb_pinjaman')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->where('status', 'overdue')
            ->count();

        $recentReports = DB::table('tb_pinjaman')
            ->join('tb_user', 'tb_user.id', '=', 'tb_pinjaman.user_id')
            ->join('tb_data_buku', 'tb_data_buku.id', '=', 'tb_pinjaman.buku_id')
            ->select('tb_pinjaman.*', 'tb_user.nama as borrower', 'tb_data_buku.judul as book_title')
            ->whereBetween('tb_pinjaman.tanggal_pinjam', [$startDate, $endDate])
            ->orderByDesc('tb_pinjaman.id')
            ->paginate(15);

        return view('admin.reports.index', compact('totalLoans', 'returnedLoans', 'overdueLoans', 'recentReports', 'startDate', 'endDate'));
    }
}
