<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = DB::table('tb_notifikasi')
            ->join('tb_user_peminjam', 'tb_user_peminjam.id', '=', 'tb_notifikasi.user_id')
            ->select('tb_notifikasi.*', 'tb_user_peminjam.nama as user_name')
            ->orderByDesc('tb_notifikasi.id')
            ->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function settings()
    {
        return view('admin.notifications.settings');
    }
}
