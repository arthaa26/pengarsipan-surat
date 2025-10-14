<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Role; 
use App\Models\Users;
use App\Models\Faculty; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. UNTUK MATIKAN FOREIGN KEY CHECKS BIAR GAK BENTROK
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); 
        
        // --- 2. UNTUK RESET TABLE
        DB::table('surat_masuk')->truncate();
        DB::table('surat_keluar')->truncate();
        DB::table('kirim_surat')->truncate();
        DB::table('surat')->truncate();
        
        DB::table('users')->truncate(); 
        DB::table('roles')->truncate();
        DB::table('faculties')->truncate(); 

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE roles AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE faculties AUTO_INCREMENT = 1;');

        // --- 3. UNTUK MENENTUKAN ROLE DAN FAKULTAS
        
        $now = Carbon::now();
        
        // UNTUK ISI FAKULTAS SECARA MANUAL DI ADMIN
        DB::table('faculties')->insert([
            [ 'id' => 1, 'name' => 'Fakultas Ekonomi dan Bisnis', 'code' => 'FEB', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 2, 'name' => 'Fakultas Ilmu Kesehatan', 'code' => 'FIKES', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 3, 'name' => 'Fakultas Perikanan dan Ilmu Kelautan', 'code' => 'FPIK', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 4, 'name' => 'Fakultas Keguruan dan Ilmu Pendidikan', 'code' => 'FKIP', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 5, 'name' => 'Fakultas Teknik dan Ilmu Komputer', 'code' => 'FTIK', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 6, 'name' => 'Fakultas Agama Islam', 'code' => 'FAI', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 7, 'name' => 'Fakultas Hukum', 'code' => 'FH', 'created_at' => $now, 'updated_at' => $now, ],
        ]);
        
        // DEFINISI ROLE SESUAI DENGAN ROLENYA
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

        // --- 4. UNTUK TES SEEDER USER
        $this->call([
            UserSeeder::class,      
        ]);
        
        // --- 5. UNTUK MENYESUAIKAN DATA DI DATABASE
        $this->call([
            KirimSuratSeeder::class,
            SuratMasukSeeder::class,
        ]);

        // --- 6. UNTUK NGECEK FOREIGN NI DAH KE PANGGIL BELUM
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
    }
}
