<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KirimSurat; 
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index()
    {
        $totalSuratCount = KirimSurat::count();

        // CATATAN: Berdasarkan skema tabel 'kirim_surat':
        // - user_id_1 adalah PENGIRIM (Surat Keluar)
        // - user_id_2 adalah PENERIMA spesifik (Surat Masuk)
        
        // 2. Hitung Surat Masuk (Menargetkan user tertentu)
        // Logika ini mungkin perlu disesuaikan dengan implementasi sebenarnya
        // Jika semua surat yang dikirim dianggap 'Surat Masuk' bagi sistem arsip:
        // $suratMasukCount = KirimSurat::count(); // Sama dengan totalSuratCount
        
        // Jika Anda hanya menghitung surat yang memiliki user_id_2 (penerima spesifik):
        // (Namun, banyak surat admin ditujukan ke faculty_id, jadi hitungan ini bisa salah)
        $suratMasukCount = KirimSurat::whereNotNull('user_id_2')->count();

        // 3. Hitung Surat Keluar (Semua yang memiliki pengirim, yang mana selalu ada)
        // Logika ini juga mungkin sama dengan totalSuratCount jika setiap surat harus memiliki user_id_1
        $suratKeluarCount = KirimSurat::whereNotNull('user_id_1')->count();
        
        // 4. Ambil 10 Surat Terbaru untuk Tabel History
        // Mengambil hanya 10 data dan melakukan Eager Loading untuk Pengirim (user1) dan Fakultasnya
        $suratMasuk = KirimSurat::with(['user1.faculty'])
                                ->latest() // equivalent to orderBy('created_at', 'desc')
                                ->limit(10) 
                                ->get();
        
        // Variabel $suratMasuk (list 10 terbaru) dan $suratMasukCount (total) 
        // memiliki nama yang ambigu. Pastikan view Anda mengerti perbedaannya.
        
        return view('admin.dashboard', [
            'totalSuratCount' => $totalSuratCount,
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $suratMasuk, // Ini adalah list 10 surat terbaru
        ]);
    }
}