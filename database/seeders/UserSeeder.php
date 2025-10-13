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
        // Fungsi pembantu untuk mendapatkan atau membuat Role ID (untuk ketahanan)
        $getRoleId = function ($name) {
            $role = Role::firstOrCreate(
                ['name' => strtolower($name)], 
                ['display_name' => ucwords(str_replace('_', ' ', $name))]
            );
            return $role->id;
        };

        // Ambil semua ID role yang diperlukan
        $adminId            = $getRoleId('admin');
        $rektorId           = $getRoleId('rektor');
        $dekanId            = $getRoleId('dekan');
        $kaprodiId          = $getRoleId('kaprodi');
        $dosenId            = $getRoleId('dosen');
        $tenagaPendidikId   = $getRoleId('tenaga_pendidik');
        $dosenTugasKhususId = $getRoleId('dosen_tugas_khusus');

        // DAFTAR FAKULTAS (Untuk referensi cepat)
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

            // AKUN KAPRODI (Contoh Kaprodi di Fakultas tersebut)
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
            
            // AKUN DOSEN (Contoh Dosen di Fakultas tersebut)
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

        // --- 3. AKUN LAINNYA (Tenaga Pendidik & Dosen Tugas Khusus) ---
        // Menggunakan Fakultas FAI (ID 6) dan FH (ID 7) sebagai contoh unit kerja
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
        
        // Masukkan data menggunakan Model::create()
        foreach ($data_users as $user_data) {
            Users::create($user_data);
        }
    }
}
