<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use App\Models\User; 

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kolom kata sandi wajib diisi.',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Login BERHASIL: Buat sesi baru
            $request->session()->regenerate();
            $user = Auth::user();
            $roleId = $user->role_id;
            
            if ($roleId == 1) { 
                return redirect()->intended('/admin/dashboard'); 
            } elseif ($roleId >= 2 && $roleId <= 6) { 
                return redirect()->intended('/dosen/dashboard'); 
            } else {
                return redirect()->intended('/dashboard'); 
            }
        }
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah atau tidak terdaftar.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login'); 
    }
}
