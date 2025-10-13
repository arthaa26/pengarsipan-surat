<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller // Changed class name from LoginController to AuthController
{
    /**
     * Menampilkan halaman login.
     * (Opsional, tergantung routing Anda)
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Asumsi view login ada di 'resources/views/auth/login.blade.php'
    }

    /**
     * Menangani permintaan login yang dikirim dari form.
     */
    public function authenticate(Request $request)
    {
        // 1. Ntuk validasi input: untuk mastiin email / password diisi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            // Pesan kalo ade error
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kolom kata sandi wajib diisi.',
        ]);

        // 2. test autentikasi user
        // Auth::attempt() akan mencari user berdasarkan 'email' DAN
        // membandingkan password yang diinput dengan password ter-hash di database.
        $remember = $request->filled('remember'); // Cek apakah checkbox 'Ingat Saya' dicentang

        if (Auth::attempt($credentials, $remember)) {
            // 3. Autentikasi BERHASIL: Buat sesi baru dan redirect
            $request->session()->regenerate();

            return redirect()->intended('/dashboard'); // Ganti '/dashboard' dengan halaman tujuan setelah login
        }

        // 4. Autentikasi GAGAL: Kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak terdaftar atau salah.',
        ])->onlyInput('email');
    }

    /**
     * Menangani permintaan logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Mengalihkan pengguna ke halaman /login
        return redirect('/login'); 
    }
}
