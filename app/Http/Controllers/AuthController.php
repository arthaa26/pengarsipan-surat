<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use App\Models\User; 

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
        ], [
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kolom kata sandi wajib diisi.',
        ]);

        $remember = $request->filled('remember');

        // 2. Autentikasi User
        if (Auth::attempt($credentials, $remember)) {
            // Login BERHASIL: Buat sesi baru
            $request->session()->regenerate();
            
            // 3. Pengecekan Peran (Role) dan Redirect
            $user = Auth::user();
            $roleId = $user->role_id;
            
            // Mengarahkan user berdasarkan role_id (sesuai tabel roles)
            if ($roleId == 1) { 
                // ID 1: Admin
                return redirect()->intended('/admin/dashboard'); 
            } elseif ($roleId >= 2 && $roleId <= 6) { 
                // ID 2 (Rektor), 3 (Dekan), 4 (Dosen), 5 (Tenaga Pendidik), 6 (Dosen Tugas Khusus)
                // Semua peran ini diarahkan ke Dashboard Dosen/User (/dosen/dashboard)
                return redirect()->intended('/dosen/dashboard'); 
            } else {
                // Fallback untuk role ID yang tidak teridentifikasi
                return redirect()->intended('/dashboard'); 
            }
        }

        // 4. Login GAGAL
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah atau tidak terdaftar.',
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

        // Mengalihkan pengguna kembali ke halaman login menggunakan rute bernama
        return redirect()->route('login'); 
    }
}
