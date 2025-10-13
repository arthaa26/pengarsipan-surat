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
<<<<<<< Updated upstream
        // 1. Mendapatkan ID peran dari tabel 'roles'.
        $adminId = Role::where('name', 'admin')->first()->id ?? 1;
        $rektorId = Role::where('name', 'rektor')->first()->id ?? 3;
        $dekanId = Role::where('name', 'dekan')->first()->id ?? 4;
        $kaprodiId = Role::where('name', 'kaprodi')->first()->id ?? 5;
        $dosenId = Role::where('name', 'dosen')->first()->id ?? 2;

        // Data pengguna contoh
        $data_users = [
            // 1. Admin Utama (Role ID 1)
=======
        // PENTING: Mendapatkan ID semua peran yang telah di-seed (diambil dari RoleSeeder)
        $adminRole = Role::where('name', 'admin')->first();
        $rektorRole = Role::where('name', 'rektor')->first();
        $dekanRole = Role::where('name', 'dekan')->first();
        $dosenRole = Role::where('name', 'dosen')->first();
        $tenagaPendidikRole = Role::where('name', 'tenaga_pendidik')->first();
        $dosenTugasKhususRole = Role::where('name', 'dosen_tugas_khusus')->first();

        // Data pengguna contoh
        $data_users = [
            // 1. User Admin Utama
>>>>>>> Stashed changes
            [
                'username' => 'admin_utama',
                'name' => 'Administrator Sistem',
                'no_hp' => '081234567890',
                'email' => 'admin@arsip.com',
<<<<<<< Updated upstream
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
=======
                'password' => Hash::make('tes'),
                'role_id' => $adminRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // 2. User Dosen 
>>>>>>> Stashed changes
            [
                'username' => 'dosen_fisika',
                'name' => 'Dr. Budi Santoso',
                'no_hp' => '089876543210',
                'email' => 'budi.santoso@kampus.ac.id',
                'password' => Hash::make('budi'),
<<<<<<< Updated upstream
                'role_id' => $dosenId,
=======
                'role_id' => $dosenRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // 3. User Dosen (Tambahan)
            [
                'username' => 'dosen_kimia',
                'name' => 'Prof. Siska Dewi',
                'no_hp' => '085000111222',
                'email' => 'siska.dewi@kampus.ac.id',
                'password' => Hash::make('siska'),
                'role_id' => $dosenRole->id ?? null,
>>>>>>> Stashed changes
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // 4. User REKTOR (Baru)
            [
                'username' => 'rektor_pusat',
                'name' => 'Prof. Agung Wijaya (REKTOR)',
                'no_hp' => '08111222333',
                'email' => 'rektor@kampus.ac.id',
                'password' => Hash::make('rektor'),
                'role_id' => $rektorRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // 5. User DEKAN (Baru)
            [
                'username' => 'dekan_ft',
                'name' => 'Dr. Lisa Sari (DEKAN)',
                'no_hp' => '08998877665',
                'email' => 'dekan@kampus.ac.id',
                'password' => Hash::make('dekan'),
                'role_id' => $dekanRole->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // 6. User Tenaga Pendidik (Baru)
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

        // 3. Masukkan data ke tabel 'users'
        DB::table('users')->insert($data_users);
    }
}