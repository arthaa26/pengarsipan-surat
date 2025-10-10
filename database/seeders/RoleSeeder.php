<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
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
    }
}