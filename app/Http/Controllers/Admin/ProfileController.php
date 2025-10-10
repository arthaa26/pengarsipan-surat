<?php
// app/Http/Controllers/Admin/ProfileController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Menggunakan 'show' karena ini biasanya hanya menampilkan satu resource (profil user yang sedang login)
    public function show()
    {
        // Memuat View: resources/views/admin/profil/profile.blade.php
        return view('admin.profil.profile');
    }
}
