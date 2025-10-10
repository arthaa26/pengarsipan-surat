<?php
// app/Http/Controllers/Admin/SuratController.php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SuratController extends Controller
{
    public function index()
    {
        // Memuat View: resources/views/admin/daftarsurat/daftar_surat.blade.php
        return view('admin.daftarsurat.index');
    }
    
    // Di masa depan, Anda akan menambahkan method 'create', 'store', 'show', 'edit', 'update', 'destroy' di sini.
}