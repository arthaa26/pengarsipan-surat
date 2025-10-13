<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel roles.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $rolesData = [
            // 1. Administrator Sistem
            [
                'name'        => 'admin', 
                'description' => 'Administrator Sistem. Akses penuh.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 2. Rektor
            [
                'name'        => 'rektor', 
                'description' => 'Pimpinan tertinggi universitas.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 3. Dekan
            [
                'name'        => 'dekan', 
                'description' => 'Pimpinan fakultas/sekolah.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 4. Dosen
            [
                'name'        => 'dosen', 
                'description' => 'Staf pengajar dan peneliti.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // 5. Kaprodi
            [
                'name'        => 'kaprodi', 
                'description' => 'Kepala Program Studi.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        DB::table('roles')->insert($rolesData);
    }
}
