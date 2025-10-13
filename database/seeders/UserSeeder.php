<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Role; 
use App\Models\Users; // Impor Model Users

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID role berdasarkan nama yang sudah ada di tabel roles
        // Gunakan getRoleId function untuk ketahanan (seperti pada jawaban sebelumnya)
        $getRoleId = function ($name) {
            $role = Role::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
            return $role->id ?? null;
        };

        $adminId            = $getRoleId('admin');
        $rektorId           = $getRoleId('rektor');
        $dekanId            = $getRoleId('dekan');
        $kaprodiId          = $getRoleId('kaprodi');
        $dosenId            = $getRoleId('dosen');
        $tenagaPendidikId   = $getRoleId('tenaga_pendidik');
        $dosenTugasKhususId = $getRoleId('dosen_tugas_khusus');

        // Data pengguna contoh (Disederhanakan untuk penggunaan Users::create)
        $data_users = [
            // 1. Admin Utama
            [
                'username' => 'admin_utama',
                'name' => 'Administrator Sistem',
                'no_hp' => '081234567890',
                'profile_photo_url' => null,
                'email' => 'admin@arsip.com',
                'password' => Hash::make('tes'),
                'role_id' => $adminId, // Hapus fallback ?? 1, karena RoleSeeder WAJIB berhasil
            ],

            // 2. Rektor
            [
                'username' => 'rektor_pusat',
                'name' => 'Prof. Agung Wijaya (Rektor)',
                'no_hp' => '08111222333',
                'profile_photo_url' => null,
                'email' => 'rektor@kampus.ac.id',
                'password' => Hash::make('rektor'),
                'role_id' => $rektorId,
            ],

            // 3. Dekan
            [
                'username' => 'dekan_ft',
                'name' => 'Dr. Lisa Sari (Dekan)',
                'no_hp' => '08998877665',
                'profile_photo_url' => null,
                'email' => 'dekan@kampus.ac.id',
                'password' => Hash::make('dekan'),
                'role_id' => $dekanId,
            ],

            // 4. Kaprodi
            [
                'username' => 'kaprodi_ilkom',
                'name' => 'Bpk. Dani Eko (Kaprodi)',
                'no_hp' => '081999888777',
                'profile_photo_url' => null,
                'email' => 'kaprodi@kampus.ac.id',
                'password' => Hash::make('kaprodi'),
                'role_id' => $kaprodiId,
            ],

            // 5. Dosen (contoh)
            [
                'username' => 'dosen_fisika',
                'name' => 'Dr. Budi Santoso',
                'no_hp' => '089876543210',
                'profile_photo_url' => null,
                'email' => 'budi.santoso@kampus.ac.id',
                'password' => Hash::make('budi'),
                'role_id' => $dosenId,
            ],
            
            // 6. Tenaga Pendidik
            [
                'username' => 'staff_adm',
                'name' => 'Iwan Susanto (Staff Adm)',
                'no_hp' => '0877553311',
                'profile_photo_url' => null,
                'email' => 'iwan.susanto@kampus.ac.id',
                'password' => Hash::make('staff'),
                'role_id' => $tenagaPendidikId,
            ],
        ];

        // Masukkan data menggunakan Model::create()
        foreach ($data_users as $user_data) {
            Users::create($user_data);
        }
    }
}