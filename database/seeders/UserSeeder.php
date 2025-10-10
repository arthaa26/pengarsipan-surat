<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Role; 
// Menggunakan Model Users yang sesuai dengan konvensi penamaan Anda
use App\Models\Users; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Mendapatkan ID peran dari tabel 'roles'.
        $adminId = Role::where('name', 'admin')->first()->id ?? 1;
        $rektorId = Role::where('name', 'rektor')->first()->id ?? 3;
        $dekanId = Role::where('name', 'dekan')->first()->id ?? 4;
        $kaprodiId = Role::where('name', 'kaprodi')->first()->id ?? 5;
        $dosenId = Role::where('name', 'dosen')->first()->id ?? 2;

        // Data pengguna contoh
        $data_users = [
            // 1. Admin Utama (Role ID 1)
            [
                'username' => 'admin_utama',
                'name' => 'Administrator Sistem',
                'no_hp' => '081234567890',
                'email' => 'admin@arsip.com',
                'password' => Hash::make('tes'), 
                'role_id' => $adminId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // 2. Rektor (Role ID 3)
            [
                'username' => 'rektor_utama',
                'name' => 'Rektor Prof. A',
                'no_hp' => '081122334455',
                'email' => 'rektor@kampus.ac.id',
                'password' => Hash::make('rektor'),
                'role_id' => $rektorId, 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // 3. Dekan (Role ID 4)
            [
                'username' => 'dekan_fakultas',
                'name' => 'Dr. Cici Dwi',
                'no_hp' => '085544332211',
                'email' => 'dekan@kampus.ac.id',
                'password' => Hash::make('dekan'),
                'role_id' => $dekanId, 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // 4. Kaprodi (Role ID 5)
            [
                'username' => 'kaprodi_ilkom',
                'name' => 'Bpk. Dani Eko',
                'no_hp' => '081999888777',
                'email' => 'kaprodi@kampus.ac.id',
                'password' => Hash::make('kaprodi'),
                'role_id' => $kaprodiId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 5. Dosen Biasa (Role ID 2)
            [
                'username' => 'dosen_fisika',
                'name' => 'Dr. Budi Santoso',
                'no_hp' => '089876543210',
                'email' => 'budi.santoso@kampus.ac.id',
                'password' => Hash::make('budi'),
                'role_id' => $dosenId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // 3. Masukkan data ke tabel 'users'
        DB::table('users')->insert($data_users);
    }
}