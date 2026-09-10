<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function showForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $key = 'admin-login:'.strtolower($credentials['username']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->logActivity(null, 'login_throttled', $request, "Too many attempts. Retry in {$seconds} seconds.");

            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $admin = Admin::where('username', $credentials['username'])->first();

        if (!$admin) {
            RateLimiter::hit($key, 300);
            $this->logActivity(null, 'login_failed_not_found', $request, 'Invalid admin credentials.');
            throw ValidationException::withMessages(['username' => 'Kredensial tidak cocok.']);
        }

        if ($admin->locked_until?->isFuture()) {
            $this->logActivity($admin, 'login_locked', $request, 'Account is temporarily locked.');
            throw ValidationException::withMessages(['username' => 'Akun terkunci sementara. Coba lagi nanti.']);
        }

        if ($admin->status !== 'active') {
            $this->logActivity($admin, 'login_failed_inactive', $request, 'Inactive admin account.');
            throw ValidationException::withMessages(['username' => 'Akun Anda tidak aktif.']);
        }

        if (!Hash::check($credentials['password'], $admin->password)) {
            RateLimiter::hit($key, 300);
            $attempts = $admin->failed_login_attempts + 1;
            $admin->update([
                'failed_login_attempts' => $attempts,
                'locked_until' => $attempts >= 10 ? now()->addMinutes(30) : null,
            ]);
            $this->logActivity($admin, 'login_failed_wrong_password', $request, 'Invalid password.');
            throw ValidationException::withMessages(['username' => 'Kredensial tidak cocok.']);
        }

        $admin->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        Auth::guard('admin')->login($admin, $request->boolean('remember'));
        $request->session()->regenerate();
        RateLimiter::clear($key);
        $this->logActivity($admin, 'login_success', $request, 'Admin login successful.');

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin')->user();
        $this->logActivity($admin, 'logout', $request, 'Admin logout.');
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function logActivity(?Admin $admin, string $action, Request $request, string $description): void
    {
        AdminActivityLog::create([
            'user_id' => $admin?->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'url' => substr($request->fullUrl(), 0, 500),
        ]);
    }
}
