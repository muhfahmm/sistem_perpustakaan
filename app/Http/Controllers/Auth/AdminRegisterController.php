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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = Admin::create([
            'username' => strtolower($data['username']),
            'password' => Hash::make($data['password']),
            'status' => 'active',
        ]);

        // Admin registration successful

        return redirect()->route('admin.login')->with('success', 'Registrasi admin berhasil. Silakan login.');
    }
}
