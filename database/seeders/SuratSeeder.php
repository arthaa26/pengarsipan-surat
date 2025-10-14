<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Faculty; // Wajib diimpor

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user yang sudah ada
        $admin = User::where('role_id', 1)->first(); 
        
        // Ambil Dosen yang merupakan Dekan FTIK (Faculty ID 5)
        $dekan_ftik = User::where('role_id', 3)->where('faculty_id', 5)->first(); 
        
        // Ambil Dosen biasa dari FKIP (Faculty ID 4)
        $dosen_fkip = User::where('role_id', 4)->where('faculty_id', 4)->first();
        
        // Ambil Dosen dari Fakultas Ekonomi (Faculty ID 1)
        $dosen_feb = User::where('role_id', 4)->where('faculty_id', 1)->first(); 
        

        // Pastikan user ditemukan
        if (!$admin || !$dekan_ftik || !$dosen_fkip || !$dosen_feb) {
            echo "Peringatan: Tidak dapat menemukan user Dekan/Dosen yang dibutuhkan untuk seeding surat. Pastikan RoleSeeder, FacultySeeder, dan UserSeeder sudah berjalan dengan data yang cukup.\n";
            return;
        }

        $data_surat = [
            // --- SKENARIO 1: Dosen (FKIP) mengirim ke Dekan (FTIK) ---
            [
                'kode_surat' => 'C/KOOR/001/2025',
                'title' => 'Permintaan Materi Kuliah Interaksi Manusia dan Komputer',
                'isi' => 'Mohon bantuan Bapak/Ibu untuk mengirimkan slide materi pertemuan ke-5 MK IMK sebelum hari Rabu.',
                'tujuan' => 'dekan', // Ditujukan ke jabatan dekan
                'tujuan_faculty_id' => $dekan_ftik->faculty_id, // Fakultas Teknik (ID 5)
                'user_id_2' => null, // Ditujukan ke group, bukan individu
                'user_id_1' => $dosen_fkip->id,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            
            // --- SKENARIO 2: Dosen (FEB) mengirim ke Rektorat (Universitas Level) ---
            [
                'kode_surat' => 'D/IZ/002/2025',
                'title' => 'Permohonan Cuti Akademik (Dosen FEB)',
                'isi' => 'Dengan hormat, saya mengajukan permohonan cuti akademik selama 1 bulan terhitung mulai 15 Oktober 2025.',
                'tujuan' => 'rektor', // Ditujukan ke jabatan rektor (Universitas Level)
                'tujuan_faculty_id' => null, // Tidak perlu filter fakultas
                'user_id_2' => null, 
                'user_id_1' => $dosen_feb->id,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            
            // --- SKENARIO 3: Dosen (FTIK) mengirim ke Dosen lain (FTIK) ---
            [
                'kode_surat' => 'C/INFO/002/2025',
                'title' => 'Revisi Waktu Bimbingan Tugas Akhir',
                'isi' => 'Waktu bimbingan yang semula pukul 14:00 diundur menjadi 15:30 WIB. Mohon segera diinfokan ke mahasiswa.',
                'tujuan' => 'dosen', // Ditujukan ke jabatan dosen
                'tujuan_faculty_id' => $dekan_ftik->faculty_id, // Fakultas Teknik (ID 5)
                'user_id_2' => null, // Dosen lain akan menerima jika logika user_id_2 null diterapkan
                'user_id_1' => $dekan_ftik->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            
            // --- SKENARIO 4: Dosen (FEB) mengirim ke Dosen (FEB) secara individu ---
            [
                'kode_surat' => 'C/INFO/003/2025',
                'title' => 'Koordinasi Pemasukan Nilai',
                'isi' => 'Mohon segera selesaikan pemasukan nilai mata kuliah Pengantar Akuntansi ke sistem.',
                'tujuan' => 'dosen', // Ditujukan ke jabatan dosen
                'tujuan_faculty_id' => null, // Tidak relevan, karena ada user_id_2
                'user_id_2' => $dosen_feb->id, // Langsung ke individu (Dosen FEB)
                'user_id_1' => $dekan_ftik->id, // Dikirim dari Dekan FTIK (sebagai contoh)
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        // Masukkan data ke tabel 'kirim_surat'
        DB::table('kirim_surat')->insert($data_surat);
    }
}