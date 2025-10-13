<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Role; 
use App\Models\Users; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID role berdasarkan nama yang sudah ada di tabel roles
        $adminRole = Role::where('name', 'admin')->first();
        $rektorRole = Role::where('name', 'rektor')->first();
        $dekanRole = Role::where('name', 'dekan')->first();
        $kaprodiRole = Role::where('name', 'kaprodi')->first();
        $dosenRole = Role::where('name', 'dosen')->first();
        $tenagaPendidikRole = Role::where('name', 'tenaga_pendidik')->first();
        $dosenTugasKhususRole = Role::where('name', 'dosen_tugas_khusus')->first();

        // Data pengguna contoh
        $data_users = [
            // 1. Admin Utama
            [
                'username' => 'admin_utama',
                'name' => 'Administrator Sistem',
                'no_hp' => '081234567890',
                'email' => 'admin@arsip.com',
                'password' => Hash::make('tes'),
                'role_id' => $adminRole->id ?? 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2. Rektor
            [
                'username' => 'rektor_pusat',
                'name' => 'Prof. Agung Wijaya (Rektor)',
                'no_hp' => '08111222333',
                'email' => 'rektor@kampus.ac.id',
                'password' => Hash::make('rektor'),
                'role_id' => $rektorRole->id ?? 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 3. Dekan
            [
                'username' => 'dekan_ft',
                'name' => 'Dr. Lisa Sari (Dekan)',
                'no_hp' => '08998877665',
                'email' => 'dekan@kampus.ac.id',
                'password' => Hash::make('dekan'),
                'role_id' => $dekanRole->id ?? 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 4. Kaprodi
            [
                'username' => 'kaprodi_ilkom',
                'name' => 'Bpk. Dani Eko (Kaprodi)',
                'no_hp' => '081999888777',
                'email' => 'kaprodi@kampus.ac.id',
                'password' => Hash::make('kaprodi'),
                'role_id' => $kaprodiRole->id ?? 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 5. Dosen (beberapa contoh)
            [
                'username' => 'dosen_fisika',
                'name' => 'Dr. Budi Santoso',
                'no_hp' => '089876543210',
                'email' => 'budi.santoso@kampus.ac.id',
                'password' => Hash::make('budi'),
                'role_id' => $dosenRole->id ?? 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'dosen_kimia',
                'name' => 'Prof. Siska Dewi',
                'no_hp' => '085000111222',
                'email' => 'siska.dewi@kampus.ac.id',
                'password' => Hash::make('siska'),
                'role_id' => $dosenRole->id ?? 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 6. Tenaga Pendidik
            [
                'username' => 'staff_adm',
                'name' => 'Iwan Susanto (Staff Adm)',
                'no_hp' => '0877553311',
                'email' => 'iwan.susanto@kampus.ac.id',
                'password' => Hash::make('staff'),
                'role_id' => $tenagaPendidikRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Masukkan data ke tabel users
        DB::table('users')->insert($data_users);
    }
}
