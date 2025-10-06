<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kirim_surat;
use App\Models\Surat_masuk;
use App\Models\Surat_keluar;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Admin
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@unmuhpnk.ac.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Dosen
        $dosen1 = User::factory()->create([
            'name' => 'Dosen Satu',
            'email' => 'dosen1@unmuhpnk.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);
        $dosen2 = User::factory()->create([
            'name' => 'Dosen Dua',
            'email' => 'dosen2@unmuhpnk.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        // Surat Masuk
        Surat_masuk::create([
            'kode_surat' => 'SM-001',
            'tittle' => 'Undangan Rapat',
            'isi_surat' => 'Undangan rapat dosen.',
            'lampiran' => 'undangan.pdf',
            'file_surat' => 'undangan.pdf',
            'id_surat_keluar' => null,
        ]);

        // Surat Keluar
        Surat_keluar::create([
            'kode_surat' => 'SK-001',
            'tittle' => 'Surat Tugas',
            'isi_surat' => 'Surat tugas mengajar.',
            'lampiran' => 'tugas.pdf',
            'file_surat' => 'tugas.pdf',
        ]);

        // Kirim Surat
        Kirim_surat::create([
            'user_id_1' => $dosen1->id,
            'user_id_2' => $dosen2->id,
            'no_surat' => 'SK-001',
            'tanggal_surat' => '2025-10-02',
            'isi' => 'Contoh isi surat kirim dari dosen satu ke dosen dua.',
            'lampiran' => 'lampiran.pdf',
        ]);
    }
}
