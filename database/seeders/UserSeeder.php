<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // PENTING: Class ini digunakan untuk mengenkripsi password
use Illuminate\Support\Carbon;
use App\Models\Role; // Menggunakan model Role

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan ID peran (asumsi RoleSeeder sudah dijalankan)
        // Jika Anda tidak menggunakan sistem role_id, Anda bisa menghapus 3 baris ini
        $adminRole = Role::where('name', 'admin')->first();
        $dosenRole = Role::where('name', 'dosen')->first();

        // Data pengguna contoh
        $data_users = [
            // 1. User Admin
            [
                'username' => 'admin_utama',
                'name' => 'Administrator Sistem',
                'no_hp' => '081234567890',
                'email' => 'admin@arsip.com',
                'password' => Hash::make('password'), // <-- Password dienkripsi di sini
                'role_id' => $adminRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // 2. User Dosen 1
            [
                'username' => 'dosen_fisika',
                'name' => 'Dr. Budi Santoso',
                'no_hp' => '089876543210',
                'email' => 'budi.santoso@kampus.ac.id',
                'password' => Hash::make('budi'), // <-- Password dienkripsi di sini
                'role_id' => $dosenRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // 3. User Dosen 2
            [
                'username' => 'dosen_kimia',
                'name' => 'Prof. Siska Dewi',
                'no_hp' => '085000111222',
                'email' => 'siska.dewi@kampus.ac.id',
                'password' => Hash::make('password'), // <-- Password dienkripsi di sini
                'role_id' => $dosenRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Masukkan data ke tabel 'users'
        DB::table('users')->insert($data_users);
    }
}
