<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FacultySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('faculties')->insert([
            [
                'id' => 1, 
                'name' => 'Fakultas Ekonomi dan Bisnis', 
                'code' => 'FEB',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2, 
                'name' => 'Fakultas Ilmu Kesehatan', 
                'code' => 'FIKES',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3, 
                'name' => 'Fakultas Perikanan dan Ilmu Kelautan', 
                'code' => 'FPIK',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4, 
                'name' => 'Fakultas Keguruan dan Ilmu Pendidikan', 
                'code' => 'FKIP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5, 
                'name' => 'Fakultas Teknik dan Ilmu Komputer', 
                'code' => 'FTIK',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6, 
                'name' => 'Fakultas Agama Islam', 
                'code' => 'FAI',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7, 
                'name' => 'Fakultas Hukum', 
                'code' => 'FH',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
