<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\KirimSuratController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DaftarSuratController; // Import Controller

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Disinik buat atur rute web.
|
*/

// --- RUTE UNTUK AUTENTIKASI ---
Route::get('/', function () {
    // Mengarahkan langsung ke dashboard jika sudah login
    if (Auth::check()) {
        return redirect()->route('user.dashboard');
    }
    return view('welcome');
});

// Form data buat login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ---------------------------------------------------------------------
// Rute yang ade di dalam ini hanya dapat diakses same user yang udah login
// ---------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ================BAGIAN HALAMAN ADMIN ====================
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Menggunakan Route Resource hanya untuk INDEX dan SHOW.
        Route::resource('daftarsurat', DaftarSuratController::class)
            ->only(['index', 'show']) 
            ->names('daftarsurat');
        
        Route::resource('manajemenuser', App\Http\Controllers\Admin\ManajemenUserController::class)
            ->names('manajemenuser');
        
        // Rute buat admin akses untuk surat
        Route::get('/surat/{id}/detail', [DaftarSuratController::class, 'show'])->name('surat.view');
        Route::get('/surat/{id}/view', [DaftarSuratController::class, 'previewFile'])->name('surat.view_file');
        Route::get('/surat/{id}/download', [DaftarSuratController::class, 'downloadFile'])->name('surat.download');
        
        // Rute DELETE eksplisit (yang digunakan di Blade)
        Route::delete('/surat/{id}/delete', [DaftarSuratController::class, 'destroy'])->name('surat.delete');
    });

    // ================ RUTE UNTUK DOSEN / USER ====================
    Route::prefix('dosen')->group(function () {

        // Dashboard tampilan dosen
        Route::get('/dashboard', [UsersController::class, 'index'])->name('user.dashboard');

        // --- RUTE DAFTAR SURAT TERPISAH (Sesuai Konvensi) ---
        // 1. Daftar Surat Masuk
        Route::get('/surat/masuk', [UsersController::class, 'daftarSuratMasuk'])
            ->name('user.daftar_surat.masuk');

        // 2. Daftar Surat Keluar
        Route::get('/surat/keluar', [UsersController::class, 'daftarSuratKeluar'])
            ->name('user.daftar_surat.keluar');
        
        // 3. Rute Redirect Utama (user.daftar_surat.index)
        Route::get('/surat/daftar', [UsersController::class, 'daftarSurat'])
            ->name('user.daftar_surat.index');
        
        // --- RUTE KIRIM SURAT ---
        
        // Rute 1: Tautan Navigasi Utama (diberi nama 'index')
        Route::get('/surat/kirim', [UsersController::class, 'createSurat'])
            ->name('user.kirim_surat.index');
        
        // Rute 2: Rute yang dipanggil untuk membuat surat BARU.
        Route::get('/surat/kirim/baru', [UsersController::class, 'createSurat']) 
            ->name('user.kirim_surat.create');

        // Rute untuk memproses pengiriman surat (POST)
        Route::post('/kirim-surat', [KirimSuratController::class, 'store'])
            ->name('user.kirim_surat.store');

        // --- RUTE AKSI SURAT (Lihat/Download/Hapus/Balas) ---

        // ✅ Rute Formulir Balasan (Menggunakan nama 'DaftarSurat.reply')
        Route::get('/surat/balas/{surat}', [KirimSuratController::class, 'replyForm']) 
            ->name('DaftarSurat.reply');
            
        // Rute Proses Balasan
        Route::post('/surat/balas/{surat}', [KirimSuratController::class, 'sendReply']) 
            ->name('DaftarSurat.reply.send');
            
        // Rute file actions untuk USER (Dosen) - Menggunakan nama yang SAMA dengan admin agar tidak duplikasi
        Route::get('/surat/view-file/{surat}', [UsersController::class, 'viewFileSurat'])->name('surat.view_file');
        Route::get('/surat/download/{surat}', [UsersController::class, 'downloadSurat'])->name('surat.download');
        
        // Rute untuk melihat detail surat atau rute generik
        Route::get('/surat/{surat}', [UsersController::class, 'viewSurat'])->name('surat.show');
        Route::delete('/surat/{surat}', [UsersController::class, 'deleteSurat'])->name('surat.delete');
        
        // Rute utama /dashboard mengarah ke dashboard dosen
        Route::get('/', function () {
            return redirect()->route('user.dashboard');
        })->name('dashboard');
    });
    // Rute dashboard umum di luar prefix
    Route::get('/dashboard', function () {
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // --- RUTE PROFIL & UPDATE ---
    Route::get('/profile/edit', [UsersController::class, 'editProfile'])
        ->name('user.profile.edit');
    Route::put('/profile/update', [UsersController::class, 'updateProfile'])
        ->name('user.profile.update');

    // =========================================================================================
    // RUTE API YANG HILANG [api.search.users]
    // =========================================================================================
    Route::get('/api/search/users', [UsersController::class, 'searchTargetUsers'])
        ->name('api.search.users');
    
});
