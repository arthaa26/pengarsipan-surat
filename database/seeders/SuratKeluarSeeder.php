<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SuratKeluarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data contoh surat keluar
        $data_surat = [
            [
                'kode_surat' => 'SK/AKD/001/2025',
                'title' => 'Surat Balasan Permohonan Cuti',
                'isi_surat' => 'Surat resmi untuk membalas permohonan cuti semester yang diajukan.',
                'lampiran' => 'form_izin_cuti.pdf',
                // user_id_1: Contoh ID Pengguna yang menandatangani/mengirim surat (e.g., Kepala Bagian)
                // user_id_2: Contoh ID Pengguna yang bertugas memproses/mengarsipkan (e.g., Admin)
                'user_id_1' => 2, 
                'user_id_2' => 1, 
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'kode_surat' => 'SK/RKN/002/2025',
                'title' => 'Pengiriman Laporan Keuangan Tahunan',
                'isi_surat' => 'Pengiriman laporan keuangan resmi kampus kepada Rektorat.',
                'lampiran' => 'lap_keu_2024.xlsx',
                'user_id_1' => 1, 
                'user_id_2' => 3, 
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'kode_surat' => 'SK/UMM/003/2025',
                'title' => 'Surat Tugas Pengawasan Ujian',
                'isi_surat' => 'Surat tugas untuk Dosen terkait pengawasan pelaksanaan ujian akhir semester.',
                'lampiran' => 'daftar_dosen_pengawas.pdf',
                'user_id_1' => 2, 
                'user_id_2' => 1, 
                'created_at' => Carbon::now()->subWeeks(2), 
                'updated_at' => Carbon::now()->subWeeks(2),
            ],
        ];

        // Masukkan data ke tabel 'surat_keluar'
        DB::table('surat_keluar')->insert($data_surat);
    }
}