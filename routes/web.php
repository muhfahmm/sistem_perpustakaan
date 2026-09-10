<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\User\LoanController as UserLoanController;
use Illuminate\Support\Facades\Route;

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::prefix('admin-panel')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
        Route::get('/register', [AdminRegisterController::class, 'showForm'])->name('register');
        Route::post('/register', [AdminRegisterController::class, 'register'])->middleware('throttle:3,1')->name('register.attempt');
    });

    Route::middleware(['auth:admin', 'admin.only', 'admin.active', 'security.headers'])->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        Route::resource('books', BookController::class);
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);

        Route::get('/loans', [AdminLoanController::class, 'index'])->name('loans.index');
        Route::post('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
        Route::post('/loans/{loan}/reject', [AdminLoanController::class, 'reject'])->name('loans.reject');

        Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
        Route::post('/returns/scan', [ReturnController::class, 'scan'])->name('returns.scan');

        Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode.index');
        Route::get('/qrcode/scanner', [QrCodeController::class, 'scanner'])->name('qrcode.scanner');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/settings', [NotificationController::class, 'settings'])->name('notifications.settings');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

Route::middleware('auth:web')->group(function () {
    Route::post('/loans', [UserLoanController::class, 'store'])->middleware('throttle:3,1')->name('user.loans.store');
});

Route::get('/', fn () => redirect()->route('admin.dashboard'));

Route::get('/construction', function () {
    return view('construction');
})->name('construction');
