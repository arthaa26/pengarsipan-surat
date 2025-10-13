<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KirimSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data contoh pengiriman surat
        $data_kirim_surat = [
            [
                // user_id_1: Pengguna yang membuat/mengirim surat
                // user_id_2: Pengguna yang menerima/menindaklanjuti
                'user_id_1' => 1, 
                'user_id_2' => 2, 
                
                // Kolom baru sesuai migrasi
                'kode_surat' => 'PENG/001/X/2025',
                'title' => 'Pemberitahuan Jadwal Pertemuan',
                'tujuan' => 'rektor', // Contoh nilai tujuan
                
                'isi' => 'Surat pemberitahuan resmi mengenai jadwal pertemuan dosen dan rektorat.',
                'file_path' => 'jadwal_pertemuan.pdf', // Nama kolom baru
                
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id_1' => 2, 
                'user_id_2' => 1, 
                
                // Kolom baru sesuai migrasi
                'kode_surat' => 'BAL/002/X/2025',
                'title' => 'Balasan Permintaan Data Mahasiswa',
                'tujuan' => 'dekan', // Contoh nilai tujuan
                
                'isi' => 'Surat balasan terkait permintaan data mahasiswa berprestasi.',
                'file_path' => null, // Contoh tanpa file
                
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id_1' => 1, 
                'user_id_2' => 3, 
                
                // Kolom baru sesuai migrasi
                'kode_surat' => 'RES/003/IX/2025',
                'title' => 'Permintaan Reservasi Aula',
                'tujuan' => 'tenaga_pendidik', // Contoh nilai tujuan
                
                'isi' => 'Permintaan reservasi aula kampus untuk acara wisuda bulan depan.',
                'file_path' => 'form_reservasi_aula.docx',
                
                'created_at' => Carbon::now()->subWeeks(2),
                'updated_at' => Carbon::now()->subWeeks(2),
            ],
        ];

        // Masukkan data ke tabel 'kirim_surat'
        DB::table('kirim_surat')->insert($data_kirim_surat);
    }
}
