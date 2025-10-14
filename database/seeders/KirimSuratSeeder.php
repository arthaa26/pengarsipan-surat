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
        DB::table('kirim_surat')->truncate(); 

        // --- ROLE USER KHUSUS UNTUK TESTING SCENARIO ---
        // CONTOHNU=YA: Role Admin=1, Dekan=3, Dosen=4. Faculty FTIK=5, FKIP=4, FEB=1.

        // Ambil user yang dibutuhkan, INI KALO UDAH ADA DI SEEDER, JADI WAJIB
        $admin = User::where('role_id', 1)->first(); 
        
        // FTIK (ID 5)
        $dekan_ftik = User::where('role_id', 3)->where('faculty_id', 5)->first(); 
        $dosen_ftik = User::where('role_id', 4)->where('faculty_id', 5)->first();
        
        // FKIP (ID 4)
        $dosen_fkip = User::where('role_id', 4)->where('faculty_id', 4)->first();
        
        // FEB (ID 1)
        $dosen_feb = User::where('role_id', 4)->where('faculty_id', 1)->first(); 
        
        $rektor = User::where('role_id', 2)->first();

        if (!$admin || !$dekan_ftik || !$dosen_fkip || !$dosen_feb || !$rektor) {
            echo "Peringatan: User Admin, Dekan, atau Dosen yang dibutuhkan tidak ditemukan. Pastikan RoleSeeder, FacultySeeder, dan UserSeeder sudah berjalan dengan data yang cukup.\n";
            return;
        }

        $data_surat = [
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
            

            [
                'kode_surat' => 'D/IZ/002/2025',
                'title' => 'Permohonan Cuti Akademik (Dosen FEB)',
                'isi' => 'Saya mengajukan permohonan cuti akademik selama 1 bulan.',
                'tujuan' => 'rektor', 
                'tujuan_faculty_id' => null, 
                'user_id_2' => null, 
                'user_id_1' => $dosen_feb->id,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            
            [
                'kode_surat' => 'C/INFO/002/2025',
                'title' => 'Revisi Waktu Bimbingan Tugas Akhir',
                'isi' => 'Waktu bimbingan yang semula pukul 14:00 diundur menjadi 15:30 WIB.',
                'tujuan' => 'dosen', 
                'tujuan_faculty_id' => $dekan_ftik->faculty_id, 
                'user_id_2' => null, 
                'user_id_1' => $dekan_ftik->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            
            [
                'kode_surat' => 'C/INFO/003/2025',
                'title' => 'Koordinasi Pemasukan Nilai',
                'isi' => 'Mohon segera selesaikan pemasukan nilai mata kuliah Pengantar Akuntansi.',
                'tujuan' => 'dosen', 
                'tujuan_faculty_id' => null, 
                'user_id_2' => $dosen_feb->id, 
                'user_id_1' => $dekan_ftik->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        DB::table('kirim_surat')->insert($data_surat);
    }
}
