<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\KirimSurat;
use App\Models\Role;
use App\Models\Faculty; 

class KirimSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama di tabel 'kirim_surat' sebelum seeding
        // Ini WAJIB dilakukan jika Anda menjalankan seeder secara terpisah
        DB::table('kirim_surat')->truncate(); 

        // --- AMBIL USER KHUSUS UNTUK TESTING SCENARIO ---
        // Asumsi: Role Admin=1, Dekan=3, Dosen=4. Faculty FTIK=5, FKIP=4, FEB=1.

        // Ambil user yang dibutuhkan (Wajib ada setelah UserSeeder)
        $admin = User::where('role_id', 1)->first(); 
        
        // FTIK (ID 5)
        $dekan_ftik = User::where('role_id', 3)->where('faculty_id', 5)->first(); 
        $dosen_ftik = User::where('role_id', 4)->where('faculty_id', 5)->first();
        
        // FKIP (ID 4)
        $dosen_fkip = User::where('role_id', 4)->where('faculty_id', 4)->first();
        
        // FEB (ID 1)
        $dosen_feb = User::where('role_id', 4)->where('faculty_id', 1)->first(); 
        
        // Rektor (Level Universitas)
        $rektor = User::where('role_id', 2)->first();

        // Peringatan jika user tidak ditemukan (FK violation)
        if (!$admin || !$dekan_ftik || !$dosen_fkip || !$dosen_feb || !$rektor) {
            echo "Peringatan: User Admin, Dekan, atau Dosen yang dibutuhkan tidak ditemukan. Pastikan RoleSeeder, FacultySeeder, dan UserSeeder sudah berjalan dengan data yang cukup.\n";
            return;
        }

        $data_surat = [
            // --- SKENARIO 1: Dosen (FKIP) mengirim ke Dekan (FTIK) ---
            // Dekan FTIK (ID 5) harus menerima. Dekan FEB tidak menerima.
            [
                'kode_surat' => 'C/KOOR/001/2025',
                'title' => 'Permintaan Materi Kuliah IMK',
                'isi' => 'Mohon bantuan Bapak/Ibu untuk mengirimkan slide materi pertemuan ke-5.',
                'tujuan' => 'dekan', 
                'tujuan_faculty_id' => $dekan_ftik->faculty_id, // TARGET: FTIK (ID 5)
                'user_id_2' => null, 
                'user_id_1' => $dosen_fkip->id,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            
            // --- SKENARIO 2: Dosen (FEB) mengirim ke Rektorat (Universitas Level) ---
            // Rektor harus menerima. Tidak ada filter fakultas.
            [
                'kode_surat' => 'D/IZ/002/2025',
                'title' => 'Permohonan Cuti Akademik (Dosen FEB)',
                'isi' => 'Saya mengajukan permohonan cuti akademik selama 1 bulan.',
                'tujuan' => 'rektor', // TARGET: Rektor (Universitas)
                'tujuan_faculty_id' => null, // Tidak perlu filter fakultas
                'user_id_2' => null, 
                'user_id_1' => $dosen_feb->id,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            
            // --- SKENARIO 3: Dosen (FTIK) mengirim ke Dosen (FTIK) ---
            // Surat ditujukan ke jabatan 'dosen' di FTIK (ID 5). Semua dosen FTIK akan melihat.
            [
                'kode_surat' => 'C/INFO/002/2025',
                'title' => 'Revisi Waktu Bimbingan Tugas Akhir',
                'isi' => 'Waktu bimbingan yang semula pukul 14:00 diundur menjadi 15:30 WIB.',
                'tujuan' => 'dosen', // TARGET: Jabatan dosen
                'tujuan_faculty_id' => $dekan_ftik->faculty_id, // TARGET: FTIK (ID 5)
                'user_id_2' => null, 
                'user_id_1' => $dekan_ftik->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            
            // --- SKENARIO 4: Dosen (FEB) mengirim ke Dosen (FEB) secara individu ---
            // Surat masuk langsung ke user_id_2 (Dosen FEB).
            [
                'kode_surat' => 'C/INFO/003/2025',
                'title' => 'Koordinasi Pemasukan Nilai',
                'isi' => 'Mohon segera selesaikan pemasukan nilai mata kuliah Pengantar Akuntansi.',
                'tujuan' => 'dosen', 
                'tujuan_faculty_id' => null, // Tidak relevan, karena ada user_id_2
                'user_id_2' => $dosen_feb->id, // TARGET: Langsung ke individu (Dosen FEB)
                'user_id_1' => $dekan_ftik->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        // Masukkan data ke tabel 'kirim_surat'
        DB::table('kirim_surat')->insert($data_surat);
    }
}
