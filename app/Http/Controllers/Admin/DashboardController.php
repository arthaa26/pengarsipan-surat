<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Surat; // Pastikan Anda mengimpor model Surat Anda
use Illuminate\Support\Facades\DB; // Hanya jika Anda menggunakan DB facade untuk query

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data summary.
     */
    public function index()
    {
        // --- LOGIKA PENGAMBILAN DATA (Contoh) ---
        
        // Asumsi model Surat ada dan memiliki scope 'masuk' atau 'jenis_surat' = 'masuk'
        // Anda perlu menyesuaikan ini dengan model dan database Anda yang sebenarnya.
        
        try {
            // Contoh 1: Menghitung jumlah surat masuk (Asumsi ada model Surat)
            // $suratMasukCount = Surat::where('jenis_surat', 'masuk')->count();
            
            // Contoh 2: Menggunakan DB facade jika model tidak tersedia (ganti 'nama_tabel_surat')
            $suratMasukCount = DB::table('surat')->where('jenis_surat', 'masuk')->count();

            // Mengambil 5 surat masuk terbaru untuk ditampilkan di tabel
            // $suratMasuk = Surat::where('jenis_surat', 'masuk')->latest()->limit(5)->get();
            $suratMasuk = DB::table('surat')
                            ->where('jenis_surat', 'masuk')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();


        } catch (\Exception $e) {
            // Jika ada masalah database atau tabel belum ada
            $suratMasukCount = 0;
            $suratMasuk = collect(); // Membuat koleksi kosong agar @forelse tidak error
        }
        
        // Memuat View: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', compact('suratMasukCount', 'suratMasuk')); 
    }
}