<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\KirimSuratController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda.
|
*/

// --- RUTE AUTENTIKASI ---
Route::get('/', function () {
    return view('welcome');
});

// Form login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ---------------------------------------------------------------------
// Semua rute di dalam ini hanya dapat diakses oleh user yang sudah login
// ---------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==================== ADMIN ====================

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('daftarsurat', App\Http\Controllers\Admin\DaftarSuratController::class)
            ->names('daftarsurat');

        Route::resource('manajemenuser', App\Http\Controllers\Admin\ManajemenUserController::class)
            ->names('manajemenuser');
        
        // Rute tambahan admin untuk surat
        Route::get('/surat/{id}/detail', [App\Http\Controllers\Admin\DaftarSuratController::class, 'showDetail'])->name('surat.view');
        Route::get('/surat/{id}/view', [App\Http\Controllers\Admin\DaftarSuratController::class, 'previewFile'])->name('surat.view_file');
        Route::get('/surat/{id}/download', [App\Http\Controllers\Admin\DaftarSuratController::class, 'downloadFile'])->name('surat.download');
        Route::delete('/surat/{id}/delete', [App\Http\Controllers\Admin\DaftarSuratController::class, 'destroy'])->name('surat.delete');

    });

    // ==================== DOSEN / USER ====================

    // Dashboard dosen
    Route::get('/dosen/dashboard', [UsersController::class, 'index'])->name('user.dashboard');

    // Dashboard umum → redirect ke dashboard dosen
    Route::get('/dashboard', function () {
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // --- PERBAIKAN DAN PENAMBAHAN RUTE DAFTAR SURAT ---
    
    // 1. Daftar Surat Masuk (Rute yang Hilang - user.daftar_surat.masuk)
    Route::get('/dosen/surat/masuk', [UsersController::class, 'daftarSuratMasuk'])
        ->name('user.daftar_surat.masuk');

    // 2. Daftar Surat Keluar (user.daftar_surat.keluar)
    Route::get('/dosen/surat/keluar', [UsersController::class, 'daftarSuratKeluar'])
        ->name('user.daftar_surat.keluar');
    
    // 3. Daftar surat dosen (user.daftar_surat.index) - Dipertahankan untuk kompatibilitas
    // Rute ini akan mengarahkan ke Controller yang melakukan redirect ke rute Masuk.
    Route::get('/dosen/surat/daftar', [UsersController::class, 'daftarSurat'])->name('user.daftar_surat.index');
    
    // --- PENAMBAHAN RUTE PROFIL (Sesuai diskusi sebelumnya) ---
    Route::get('/profile/edit', [UsersController::class, 'editProfile'])->name('user.profile.edit');
    Route::put('/profile/update', [UsersController::class, 'updateProfile'])->name('user.profile.update');
    // ---------------------------------------------------------

    // Form kirim surat
    Route::get('/dosen/surat/kirim', [UsersController::class, 'createSurat'])->name('user.kirim_surat.index');
    Route::post('/kirim-surat', [KirimSuratController::class, 'store'])->name('user.kirim_surat.store');

    // Aksi surat
    Route::get('/surat/{surat}', [UsersController::class, 'viewSurat'])->name('surat.view');
    Route::delete('/surat/{surat}', [UsersController::class, 'deleteSurat'])->name('surat.delete');
    Route::get('/surat/view-file/{surat}', [UsersController::class, 'viewFileSurat'])->name('surat.view_file');
    Route::get('/surat/download/{surat}', [UsersController::class, 'downloadSurat'])->name('surat.download');

});