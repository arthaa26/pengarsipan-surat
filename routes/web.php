<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController; 
// --- Hapus Controller Admin yang sebelumnya di-import untuk menghindari masalah Autoloading ---
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;

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
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


// Semue rute di dalam ini hanya dapat diakses oleh user yang sudah login
Route::middleware(['auth'])->group(function () {

    // Rute Logout (Global, tidak tergantung Admin/User)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // ----------------------------------------------------
    // [1. ADMIN GROUP]
    // ----------------------------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {

        // DASHBOARD ADMIN
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // DAFTAR SURAT (Menggunakan FQCN)
        Route::resource('daftarsurat', App\Http\Controllers\Admin\DaftarSuratController::class)
            ->names('daftarsurat');

        // MANAJEMEN USER (Menggunakan FQCN untuk memperbaiki error 'Target class not exist')
        Route::resource('manajemenuser', App\Http\Controllers\Admin\ManajemenUserController::class)
            ->names('manajemenuser');

        // PROFIL ADMIN
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::get('/admin/surat/preview/{id}', [App\Http\Controllers\Admin\DaftarSuratController::class, 'previewFile'])->name('surat.preview_file');
Route::get('/admin/surat/download/{id}', [App\Http\Controllers\Admin\DaftarSuratController::class, 'downloadFile'])->name('surat.download_file');
Route::get('/admin/surat/{id}/detail', [App\Http\Controllers\Admin\DaftarSuratController::class, 'showDetail'])->name('surat.show_detail');
    });
    // routes/web.php (di dalam Route Group Admin atau di bawah rute utama)

// Route Detail Surat untuk tombol 'Lihat'
Route::get('/surat/{id}/detail', [App\Http\Controllers\Admin\DaftarSuratController::class, 'showDetail'])->name('surat.show_detail');

// Route Preview File (Simpan & Cetak)
Route::get('/surat/preview/{id}', [App\Http\Controllers\Admin\DaftarSuratController::class, 'previewFile'])->name('surat.preview_file');

// Route Download File (Simpan)
Route::get('/surat/download/{id}', [App\Http\Controllers\Admin\DaftarSuratController::class, 'downloadFile'])->name('surat.download_file');

// Route Delete Surat (Digunakan oleh tombol Hapus)
Route::delete('/surat/{id}', [App\Http\Controllers\Admin\DaftarSuratController::class, 'destroy'])->name('surat.delete');


    // ----------------------------------------------------
    // [2. DOSEN / USER GROUP]
    // ----------------------------------------------------

    // Dashboard Utama User/Dosen
    Route::get('/dosen/dashboard', [UsersController::class, 'index'])->name('user.dashboard');

    // Rute Daftar Surat 
    Route::get('/dosen/surat/daftar', [UsersController::class, 'daftarSurat'])->name('user.daftar_surat.index');

    // Rute Kirim Surat
    Route::get('/dosen/surat/kirim', [UsersController::class, 'createSurat'])->name('user.kirim_surat.index');

    // Rute Aksi Surat
    Route::get('/surat/{surat}', [UsersController::class, 'viewSurat'])->name('surat.view');
    Route::delete('/surat/{surat}', [UsersController::class, 'deleteSurat'])->name('surat.delete');

    // Rute Download Lampiran
    Route::get('/surat/download/{surat}', [UsersController::class, 'downloadSurat'])->name('surat.download');

});