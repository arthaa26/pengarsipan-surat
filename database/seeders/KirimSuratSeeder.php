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
                // user_id_1: Pengguna yang membuat/mengirim surat (e.g., Admin)
                // user_id_2: Pengguna yang menerima/menindaklanjuti (e.g., Dosen/Kepala Bagian)
                'user_id_1' => 1, 
                'user_id_2' => 2, 
                'no_surat' => 'PENG/001/X/2025',
                'tanggal_surat' => '2025-10-01',
                'isi' => 'Surat pemberitahuan resmi mengenai jadwal pertemuan dosen dan rektorat.',
                'lampiran' => 'jadwal_pertemuan.pdf', // Kolom ini 'nullable'
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id_1' => 2, 
                'user_id_2' => 1, 
                'no_surat' => 'BAL/002/X/2025',
                'tanggal_surat' => '2025-10-05',
                'isi' => 'Surat balasan terkait permintaan data mahasiswa berprestasi.',
                'lampiran' => null, // Contoh tanpa lampiran
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id_1' => 1, 
                'user_id_2' => 3, 
                'no_surat' => 'RES/003/IX/2025',
                'tanggal_surat' => '2025-09-20',
                'isi' => 'Permintaan reservasi aula kampus untuk acara wisuda bulan depan.',
                'lampiran' => 'form_reservasi_aula.docx',
                'created_at' => Carbon::now()->subWeeks(2),
                'updated_at' => Carbon::now()->subWeeks(2),
            ],
        ];

        // Masukkan data ke tabel 'kirim_surat'
        DB::table('kirim_surat')->insert($data_kirim_surat);
    }
}