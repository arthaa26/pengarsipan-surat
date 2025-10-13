<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
<<<<<<< Updated upstream
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Masukkan data roles dengan ID yang sudah ditentukan secara eksplisit
        $roles = [
            ['id' => 1, 'name' => 'admin', 'description' => 'Administrator Sistem', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'dosen', 'description' => 'Pengguna Dosen Biasa', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'rektor', 'description' => 'Pejabat Rektor', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'name' => 'dekan', 'description' => 'Pejabat Dekan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'name' => 'kaprodi', 'description' => 'Pejabat Kepala Program Studi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('roles')->insert($roles);
=======

    public function run(): void
    {
        $now = Carbon::now();

        $rolesData = [
            // 1. Administrasi Utama
            [
                'name'        => 'admin', 
                'description' => 'Administrator Sistem. Akses penuh.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 2. Pimpinan Tertinggi (REKTOR)
            [
                'name'        => 'rektor', 
                'description' => 'Pimpinan tertinggi universitas.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 3. Pimpinan Fakultas (DEKAN)
            [
                'name'        => 'dekan', 
                'description' => 'Pimpinan fakultas/sekolah.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 4. Staf Akademik (DOSEN)
            [
                'name'        => 'dosen', 
                'description' => 'Staf pengajar dan peneliti.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 5. Staf Non-Akademik (TENAGA PENDIDIK)
            [
                'name'        => 'tenaga_pendidik', 
                'description' => 'Staf non-akademik/administrasi.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 6. Dosen yang Ditugaskan
            [
                'name'        => 'dosen_tugas_khusus', 
                'description' => 'Dosen yang ditugaskan ke posisi struktural/khusus.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        DB::table('roles')->insert($rolesData);
>>>>>>> Stashed changes
    }
}