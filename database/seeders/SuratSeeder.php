<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari user yang sudah ada
        // ASUMSI: Admin/Rektorat role_id 1, Dosen role_id 2
        $admin = User::where('role_id', 1)->first(); 
        
        // Ambil setidaknya DUA user Dosen untuk skenario Dosen ke Dosen
        $dosen = User::where('role_id', 2)->get();
        
        $dosen_a = $dosen->first();
        $dosen_b = $dosen->skip(1)->first(); // Dosen kedua

        // Pastikan user ditemukan
        if (!$admin || $dosen->count() < 2) {
            echo "Peringatan: User Admin tidak ditemukan atau kurang dari 2 user Dosen (role_id 2) tidak ditemukan. Jalankan UserSeeder/Factory terlebih dahulu.\n";
            return;
        }

        $data_surat = [
            // --- SKENARIO 1: Dosen ke Dosen (Dosen A ke Dosen B) ---
            [
                'kode_surat' => 'C/KOOR/001/2025',
                'title' => 'Permintaan Materi Kuliah Interaksi Manusia dan Komputer',
                'isi' => 'Mohon bantuan Bapak/Ibu untuk mengirimkan slide materi pertemuan ke-5 MK IMK sebelum hari Rabu.',
                'to_user_id' => $dosen_b->id,
                'from_user_id' => $dosen_a->id,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            // --- SKENARIO 2: Dosen ke Rektorat/Admin (Perizinan) ---
            [
                'kode_surat' => 'D/IZ/002/2025',
                'title' => 'Permohonan Cuti Akademik (Dosen A)',
                'isi' => 'Dengan hormat, saya mengajukan permohonan cuti akademik selama 1 bulan terhitung mulai 15 Oktober 2025.',
                'to_user_id' => $admin->id, // Ditujukan ke Rektorat/Admin
                'from_user_id' => $dosen_a->id,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            // --- SKENARIO 3: Dosen ke Dosen (Informasi) ---
            [
                'kode_surat' => 'C/INFO/002/2025',
                'title' => 'Revisi Waktu Bimbingan Tugas Akhir',
                'isi' => 'Waktu bimbingan yang semula pukul 14:00 diundur menjadi 15:30 WIB. Mohon segera diinfokan ke mahasiswa.',
                'to_user_id' => $dosen_b->id,
                'from_user_id' => $dosen_a->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            // --- SKENARIO 4: Dosen ke Rektorat/Admin (Laporan) ---
            [
                'kode_surat' => 'D/LP/003/2025',
                'title' => 'Laporan Penggunaan Dana Penelitian',
                'isi' => 'Terlampir adalah laporan pertanggungjawaban dana penelitian yang sudah disetujui.',
                'to_user_id' => $admin->id,
                'from_user_id' => $dosen_b->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Masukkan data ke tabel 'surat'
        DB::table('surat')->insert($data_surat);
    }
}