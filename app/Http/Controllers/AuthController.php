<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Simpan data login ke database (contoh ke tabel logins)
        DB::table('logins')->insert([
            'email' => $request->email,
            'login_at' => now(),
        ]);

        // Redirect ke dashboard (atau halaman lain)
        return redirect('/dashboard')->with('success', 'Login berhasil!');
    }
}
