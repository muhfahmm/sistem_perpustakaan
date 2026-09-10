<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    public function showForm()
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:tb_admin,username'],
            'email' => ['required', 'email', 'max:150', 'unique:tb_admin,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = Admin::create([
            'username' => strtolower($data['username']),
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'status' => 'active',
        ]);

        AdminActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'admin_registration',
            'description' => 'New admin account registered.',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'url' => substr($request->fullUrl(), 0, 500),
        ]);

        return redirect()->route('admin.login')->with('success', 'Registrasi admin berhasil. Silakan login.');
    }
}
