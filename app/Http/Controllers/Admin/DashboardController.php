<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KirimSurat; // Model yang benar untuk data surat
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin.
     * Mengambil semua data surat dari tabel kirim_surat secara global.
     */
    public function index()
    {
        // --- 1. MENGAMBIL SEMUA DATA (GLOBAL) ---
        
        // Ambil semua surat (History Surat) tanpa filter user ID
        $allSurat = KirimSurat::orderBy('created_at', 'desc')->get();
        
        // --- 2. MENGHITUNG STATISTIK GLOBAL ---
        
        // Jumlah total semua baris di tabel kirim_surat
        $totalSuratCount = $allSurat->count();
        
        // Surat Masuk Global: Semua surat yang memiliki tujuan (user_id_2 tidak NULL)
        $suratMasukCount = KirimSurat::whereNotNull('user_id_2')->count();
        
        // Surat Keluar Global: Semua surat yang memiliki pengirim (user_id_1 tidak NULL)
        $suratKeluarCount = KirimSurat::whereNotNull('user_id_1')->count();
        
        // --- 3. MEMUAT VIEW ---
        
        // Variabel $suratMasuk digunakan untuk looping tabel History Surat
        return view('admin.dashboard', [
            'totalSuratCount' => $totalSuratCount,
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $allSurat, 
        ]);
    }
}
