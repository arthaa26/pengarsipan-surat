<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController; // Controller untuk logika pengguna (dosen/user)
use App\Http\Controllers\KirimSuratController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda.
|
*/

// Rute Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// --- RUTE AUTENTIKASI (Login) ---

// Ntuk form login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// buat proses form login
Route::post('/login', [AuthController::class, 'login']);


// Semue rute di dalam ini hanya dapat diakses oleh user yang sudah login
Route::middleware(['auth'])->group(function () {
    
    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 1. DASHBOARD ADMIN
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // 2. DASHBOARD DOSEN / USER (Menggunakan UsersController)
    
    // Dashboard Utama User/Dosen
    Route::get('/dosen/dashboard', [UsersController::class, 'index'])->name('user.dashboard');
    
    // Rute Daftar Surat
    Route::get('/dosen/surat/daftar', [UsersController::class, 'daftarSurat'])->name('user.daftar_surat.index');
    
    // Rute Kirim Surat
    Route::get('/dosen/surat/kirim', [UsersController::class, 'createSurat'])->name('user.kirim_surat.index');
    
    // Rute Aksi Surat (View dan Delete)
    
    // Melihat detail surat
    Route::get('/surat/{surat}', [UsersController::class, 'viewSurat'])->name('surat.view');
    
    // Menghapus surat (menggunakan metode DELETE)
    Route::delete('/surat/{surat}', [UsersController::class, 'deleteSurat'])->name('surat.delete');

    // ----------------------------------------------------
    // [PERBAIKAN UTAMA] Rute Download Lampiran
    // Rute ini harus ditangani oleh fungsi di UsersController untuk mengirim file
    Route::get('/surat/download/{surat}', [UsersController::class, 'downloadSurat'])->name('surat.download');
    // ----------------------------------------------------


    // Rute Dashboard Umum (jika masih diperlukan)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::resource('kirim-surat', KirimSuratController::class)->names('user.kirim_surat')->middleware(['auth']);
    Route::post('/kirim-surat', [KirimSuratController::class, 'store'])->name('user.kirim_surat.store');


    // Disini kalok nak nambah { ke atas }


});
