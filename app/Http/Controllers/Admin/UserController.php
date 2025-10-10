<?php // app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Memuat View: resources/views/admin/manajemen_surat/manajemen_user.blade.php
        return view('admin.manajemen_surat.manajemen_user');
    }

    public function daftarSuratAdmin()
    {
        // Logika untuk menampilkan semua surat
        // ...
        return view('admin.daftarsurat.index');
    }

    // Di masa depan, Anda akan menambahkan method 'create', 'store', 'show', 'edit', 'update', 'destroy' di sini.
}