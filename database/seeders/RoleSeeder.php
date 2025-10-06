<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ Import DB
use Illuminate\Support\Carbon; // ✅ Import Carbon

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rolesData = [
            [
                'name'        => 'admin', 
                'description' => 'Administrator Sistem.',
                'created_at'  => $now, // ✅ Tambahkan timestamps
                'updated_at'  => $now,
            ],
            [
                'name'        => 'dosen', 
                'description' => 'Akses surat',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        // ✅ Gunakan Query Builder untuk seeding massal yang cepat
        DB::table('roles')->insert($rolesData);
    }
}