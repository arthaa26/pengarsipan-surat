<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SuratMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data contoh surat masuk
        $data_surat = [
            [
                'kode_surat' => 'A/001/2025',
                'title' => 'Permohonan Kerjasama Penelitian',
                'isi_surat' => 'Detail permohonan kerjasama penelitian dari Universitas X.',
                'lampiran' => 'dokumen_penelitian.pdf',
                'user_id_1' => 1, // Contoh: ID Pengguna yang mencatat surat (e.g., Admin)
                'user_id_2' => 2, // Contoh: ID Pengguna yang ditujukan (e.g., Dosen/Kepala Bagian)
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_surat' => 'B/002/2025',
                'title' => 'Undangan Rapat Koordinasi',
                'isi_surat' => 'Undangan rapat koordinasi terkait persiapan akreditasi.',
                'lampiran' => 'agenda_rapat.docx',
                'user_id_1' => 1, 
                'user_id_2' => 3, // Contoh: ID Pengguna yang ditujukan berbeda
                'created_at' => Carbon::now()->subDays(5), // 5 hari yang lalu
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'kode_surat' => 'C/003/2025',
                'title' => 'Pemberitahuan Cuti Semester Ganjil',
                'isi_surat' => 'Pemberitahuan resmi mengenai jadwal cuti semester.',
                'lampiran' => 'sk_cuti_akademik.pdf',
                'user_id_1' => 2, // Contoh: Pencatat bisa saja Dosen
                'user_id_2' => 1, 
                'created_at' => Carbon::now()->subMonths(1), // 1 bulan yang lalu
                'updated_at' => Carbon::now()->subMonths(1),
            ],
        ];

        // Masukkan data ke tabel 'surat_masuk'
        DB::table('surat_masuk')->insert($data_surat);
    }
}