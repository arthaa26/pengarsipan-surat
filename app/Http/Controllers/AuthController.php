<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Fasad Auth untuk proses login
use Illuminate\Support\Facades\Hash; // Import Fasad Hash (jika diperlukan untuk registrasi)
use App\Models\User; // Import Model User (jika diperlukan untuk registrasi)

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan login dan memverifikasi kredensial.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        // 2. Autentikasi User
        if (Auth::attempt($credentials, $remember)) {
            // Login BERHASIL: Buat sesi baru
            $request->session()->regenerate();
            
            // 3. Pengecekan Peran (Role) dan Redirect
            $user = Auth::user();
            
            // ASUMSI:
            // role_id == 1 adalah Admin
            // role_id == 2 adalah Dosen
            
            // Mengarahkan user berdasarkan role_id
            if ($user->role_id == 1) { 
                // Jika User adalah Admin, arahkan ke dashboard admin
                return redirect()->intended('/admin/dashboard'); 
            } elseif ($user->role_id == 2) { 
                // Jika User adalah Dosen, arahkan ke dashboard dosen
                return redirect()->intended('/dosen/dashboard'); 
            } else {
                // Untuk role lain, arahkan ke dashboard umum atau berikan pesan error
                return redirect()->intended('/dashboard'); 
            }
            
        }

        // 4. Login GAGAL
        // Kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah atau tidak terdaftar.',
        ])->onlyInput('email');
    }
    
    // Metode registrasi dan logout harusnya juga ada di sini untuk kelengkapan

    // public function showRegistrationForm() { ... }
    // public function register(Request $request) { ... }
    // public function logout(Request $request) { ... }
}
