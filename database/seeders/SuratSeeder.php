<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User; // Digunakan untuk mencari ID Admin dan Dosen

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari user yang sudah ada (Admin dan Dosen)
        // ASUMSI: Admin memiliki role_id 1 dan Dosen memiliki role_id 2
        $admin = User::where('role_id', 1)->first(); 
        $dosen = User::where('role_id', 2)->first();

        // Pastikan user ditemukan sebelum membuat surat
        if (!$admin || !$dosen) {
            echo "Peringatan: User Admin atau Dosen tidak ditemukan. Jalankan UserSeeder terlebih dahulu.\n";
            return;
        }

        $data_surat = [
            // 1. Contoh Surat Masuk untuk Dosen (Dikirim oleh Admin)
            [
                'kode_surat' => 'A/SK/001/2025',
                'title' => 'Pemberitahuan Jadwal Kuliah Semester Genap',
                'isi' => 'Mohon diperhatikan jadwal terbaru kuliah Fisika Dasar untuk Semester Genap.',
                'to_user_id' => $dosen->id,
                'from_user_id' => $admin->id,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            // 2. Contoh Surat Keluar dari Dosen (Ditujukan ke Admin)
            [
                'kode_surat' => 'B/LP/001/2025',
                'title' => 'Laporan Nilai Mata Kuliah Statistika',
                'isi' => 'Terlampir adalah rekapitulasi nilai akhir mata kuliah Statistika.',
                'to_user_id' => $admin->id,
                'from_user_id' => $dosen->id,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            // 3. Contoh Surat Masuk untuk Dosen (Dikirim oleh Admin)
            [
                'kode_surat' => 'A/IN/002/2025',
                'title' => 'Undangan Rapat Koordinasi Program Studi',
                'isi' => 'Diharapkan kehadiran pada rapat koordinasi prodi pada hari Jumat, 10 Oktober 2025.',
                'to_user_id' => $dosen->id,
                'from_user_id' => $admin->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        // Masukkan data ke tabel 'surat'
        DB::table('surat')->insert($data_surat);
    }
}
