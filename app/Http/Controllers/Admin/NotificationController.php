<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = DB::table('tb_notifications')
            ->join('tb_user', 'tb_user.id', '=', 'tb_notifications.user_id')
            ->select('tb_notifications.*', 'tb_user.name as user_name')
            ->latest('tb_notifications.created_at')
            ->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function settings()
    {
        return view('admin.notifications.settings');
    }
}
