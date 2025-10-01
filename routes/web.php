<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\AuthController; // Pastikan ini di-import

/*
|--------------------------------------------------------------------------
| Halaman Utama (Welcome Page)
|--------------------------------------------------------------------------
| Route untuk halaman sambutan E-Arsip UMP.
*/
Route::get('/', function () {
    return view('e-arsip');
});

/*
|--------------------------------------------------------------------------
| Route Autentikasi Kustom
|--------------------------------------------------------------------------
| Mendefinisikan route untuk Login dan prosesnya secara manual.
*/
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'checklogin']);

/*
|--------------------------------------------------------------------------
| Dashboard Terproteksi (Role-Based Access)
|--------------------------------------------------------------------------
*/
// Route untuk user biasa (dosen, dekan, dt)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboard::class, 'index'])->name('user.dashboard');
});

// Route untuk superadmin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
