<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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