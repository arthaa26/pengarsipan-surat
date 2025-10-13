<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
<<<<<<< Updated upstream
=======
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
>>>>>>> Stashed changes

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

<<<<<<< Updated upstream
=======
// --- RUTE AUTENTIKASI ---

// Ntuk form login
>>>>>>> Stashed changes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

<<<<<<< Updated upstream
=======
// ---------------------------------------------------------------------
// Semua rute di dalam ini hanya dapat diakses oleh user yang sudah login
// ---------------------------------------------------------------------
>>>>>>> Stashed changes
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

<<<<<<< Updated upstream
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('daftarsurat', App\Http\Controllers\Admin\DaftarSuratController::class)
            ->names('daftarsurat');

        Route::resource('manajemenuser', App\Http\Controllers\Admin\ManajemenUserController::class)
            ->names('manajemenuser');
        
        // Rute ini memiliki nama lengkap: admin.surat.view
        Route::get('/surat/{id}/detail', [App\Http\Controllers\Admin\DaftarSuratController::class, 'showDetail'])->name('surat.view');
        
        // Rute ini memiliki nama lengkap: admin.surat.view_file
        Route::get('/surat/{id}/view', [App\Http\Controllers\Admin\DaftarSuratController::class, 'previewFile'])->name('surat.view_file');
        
        // Rute ini memiliki nama lengkap: admin.surat.download
        Route::get('/surat/{id}/download', [App\Http\Controllers\Admin\DaftarSuratController::class, 'downloadFile'])->name('surat.download');
        
        // Rute ini memiliki nama lengkap: admin.surat.delete
        Route::delete('/surat/{id}/delete', [App\Http\Controllers\Admin\DaftarSuratController::class, 'destroy'])->name('surat.delete');

    });

    Route::get('/dosen/dashboard', [UsersController::class, 'index'])->name('user.dashboard');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard.general');
    
    // Catatan: Jika Anda ingin rute user/dosen diakses tanpa prefix admin,
    // Anda harus memastikan nama rute di Blade file user/dosen Anda adalah 'surat.download'
    // jika ingin rute ini aktif:
    // Route::get('/surat/download/{surat}', [UsersController::class, 'downloadSurat'])->name('surat.download');

});
=======
    // DASHBOARD & HALAMAN UTAMA USER
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/dosen/dashboard', [UsersController::class, 'index'])->name('user.dashboard');
    
    // [PERBAIKAN] Rute Dashboard Umum sekarang mengalihkan ke Dashboard Dosen
    Route::get('/dashboard', function () {
        // Mengarahkan pengguna umum ke dashboard dosen
        return redirect()->route('user.dashboard');
    })->name('dashboard'); 

    // A. RUTE TERKAIT SURAT DAN PENGARSIPAN
    
    // 1. DAFTAR SURAT
    Route::get('/dosen/surat/daftar', [UsersController::class, 'daftarSurat'])->name('user.daftar_surat.index');
    
    // 2. KIRIM SURAT
    Route::get('/dosen/surat/kirim', [UsersController::class, 'createSurat'])->name('user.kirim_surat.index');
    Route::post('/kirim-surat', [KirimSuratController::class, 'store'])->name('user.kirim_surat.store');

    // 3. AKSI SURAT (Aksi pada tabel dashboard)
    
    // Melihat detail surat
    Route::get('/surat/{surat}', [UsersController::class, 'viewSurat'])->name('surat.view');
    
    // Menghapus surat (menggunakan metode DELETE)
    Route::delete('/surat/{surat}', [UsersController::class, 'deleteSurat'])->name('surat.delete');

    // [FIX] Melihat file lampiran di browser (Target UsersController@viewFileSurat)
    Route::get('/surat/view-file/{surat}', [UsersController::class, 'viewFileSurat'])->name('surat.view_file');

    // Mendownload file lampiran (Target UsersController@downloadSurat)
    Route::get('/surat/download/{surat}', [UsersController::class, 'downloadSurat'])->name('surat.download');
});
>>>>>>> Stashed changes
