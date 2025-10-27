<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Role; 
use App\Models\User; // <-- PERBAIKAN: Ganti 'Users' menjadi 'User' (sesuai konvensi Laravel)

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // UNTUK HAPUS DATA ATAU ROLE DENGAN AUTO            
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); 
        DB::table('roles')->truncate(); 
        DB::statement('ALTER TABLE roles AUTO_INCREMENT = 1;'); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //  BUAT ROLE DENGAN ID BERURUTAN (1-7) ---
        $rolesData = [
            'admin' => ['display_name' => 'Admin'],
            'rektor' => ['display_name' => 'Rektor'],
            'dekan' => ['display_name' => 'Dekan'],
            'dosen' => ['display_name' => 'Dosen'],
            'kaprodi' => ['display_name' => 'Kaprodi'],
            'tenaga_pendidik' => ['display_name' => 'Tenaga Pendidik'],
            'dosen_tugas_khusus' => ['display_name' => 'Dosen Tugas Khusus'],
        ];

        $rolesMap = [];
        foreach ($rolesData as $name => $data) {
            $role = Role::create(['name' => $name, 'display_name' => $data['display_name']]);
            $rolesMap[$name] = $role->id;
        }

        // ID ROLE USER
        $adminId             = $rolesMap['admin']; // ID 1
        $rektorId            = $rolesMap['rektor']; // ID 2
        $dekanId             = $rolesMap['dekan']; // ID 3
        $kaprodiId           = $rolesMap['kaprodi']; // ID 5
        $dosenId             = $rolesMap['dosen']; // ID 4
        $tenagaPendidikId    = $rolesMap['tenaga_pendidik']; // ID 6
        $dosenTugasKhususId  = $rolesMap['dosen_tugas_khusus']; // ID 7

        // ID FAKULTAS SESUAI DENGAN DATABASE, BIAR GA BUG SESUAIKAN DARI DATABASE
        $faculties = [
            1 => 'Fakultas Ekonomi dan Bisnis',
            2 => 'Fakultas Ilmu Kesehatan',
            3 => 'Fakultas Perikanan dan Ilmu Kelautan',
            4 => 'Fakultas Keguruan dan Ilmu Pendidikan',
            5 => 'Fakultas Teknik dan Ilmu Komputer',
            6 => 'Fakultas Agama Islam',
            7 => 'Fakultas Hukum',
        ];
        
        $data_users = [];
        
        // --- 1. AKUN UNIVERSITAS (Admin & Rektor) ---
        $data_users[] = [
            'username' => 'admin_utama',
            'name' => 'Administrator Sistem',
            'no_hp' => '081234567890',
            'profile_photo_url' => null,
            'faculty_id' => null, 
            'email' => 'admin@arsip.com',
            'password' => Hash::make('admin'),
            'role_id' => $adminId,
        ];

        $data_users[] = [
            'username' => 'rektor_pusat',
            'name' => 'Prof. Agung Wijaya (Rektor)',
            'no_hp' => '08111222333',
            'profile_photo_url' => null,
            'faculty_id' => null, 
            'email' => 'rektor@unmuh.ac.id',
            'password' => Hash::make('rektor'),
            'role_id' => $rektorId,
        ];

        // --- 2. AKUN PER FAKULTAS (Dekan, Kaprodi, Dosen) ---
        foreach ($faculties as $id => $name) {
            $code = strtolower(str_replace(['Fakultas ', ' dan ', ' '], ['', '_', ''], $name));
            
            // AKUN DEKAN
            $data_users[] = [
                'username' => 'dekan_' . $code,
                'name' => 'Dekan ' . $name,
                'no_hp' => '0899' . str_pad($id, 3, '0', STR_PAD_LEFT) . '000',
                'profile_photo_url' => null,
                'faculty_id' => $id, 
                'email' => 'dekan.' . $code . '@unmuh.ac.id',
                'password' => Hash::make('dekan'),
                'role_id' => $dekanId,
            ];

            // AKUN KAPRODI (Contoh Kaprodi di Salah Satu Fakultas Itu
            $data_users[] = [
                'username' => 'kaprodi_' . $code,
                'name' => 'Kaprodi ' . $name,
                'no_hp' => '0899' . str_pad($id, 3, '0', STR_PAD_LEFT) . '100',
                'profile_photo_url' => null,
                'faculty_id' => $id, 
                'email' => 'kaprodi.' . $code . '@unmuh.ac.id',
                'password' => Hash::make('kaprodi'),
                'role_id' => $kaprodiId,
            ];
            
            // AKUN DOSEN (Contoh Dosen di Salah Satu Fakultas Itu)
            $data_users[] = [
                'username' => 'dosen_' . $code,
                'name' => 'Dosen ' . $name,
                'no_hp' => '0898' . str_pad($id, 3, '0', STR_PAD_LEFT) . '200',
                'profile_photo_url' => null,
                'faculty_id' => $id, 
                'email' => 'dosen.' . $code . '@unmuh.ac.id',
                'password' => Hash::make('dosen'),
                'role_id' => $dosenId,
            ];
        }

        // --- 3. AKUN LAENNYA MISAL (Tenaga Pendidik & Dosen Tugas Khusus) ---
        // Ini pake  (ID 6) dan FH (ID 7) NANTI ROLENYA NULL ATAU TAK TERINDETIFIKASI
        $data_users[] = [
            'username' => 'staff_adm',
            'name' => 'Iwan Susanto (Staff Adm FAI)',
            'no_hp' => '0877553311',
            'profile_photo_url' => null,
            'faculty_id' => 6, 
            'email' => 'iwan.susanto@unmuh.ac.id',
            'password' => Hash::make('staff'),
            'role_id' => $tenagaPendidikId,
        ];

        $data_users[] = [
            'username' => 'dosen_khusus',
            'name' => 'Siti Aminah (Dosen Khusus FH)',
            'no_hp' => '0877553312',
            'profile_photo_url' => null,
            'faculty_id' => 7, 
            'email' => 'siti.aminah@unmuh.ac.id',
            'password' => Hash::make('khusus'),
            'role_id' => $dosenTugasKhususId,
        ];
        
        foreach ($data_users as $user_data) {
            User::create($user_data); // <-- PERBAIKAN: Ganti 'Users' menjadi 'User'
        }
    }
}
