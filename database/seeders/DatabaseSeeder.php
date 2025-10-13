<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. MATIKAN FOREIGN KEY CHECKS ---
        Schema::disableForeignKeyConstraints();

        // --- 2. RESET TABEL BERURUTAN (Dari Anak ke Induk) ---
        // Ini mengatasi error 1701 Cannot truncate a table referenced...
        DB::table('surat_masuk')->truncate();
        DB::table('surat_keluar')->truncate();
        DB::table('kirim_surat')->truncate();
        DB::table('surat')->truncate();
        
        DB::table('users')->truncate(); 
        DB::table('roles')->truncate();
        DB::table('faculties')->truncate(); 

        // --- 3. HIDUPKAN KEMBALI FOREIGN KEY CHECKS ---
        Schema::enableForeignKeyConstraints();

        // --- 4. PENGISIAN DATA MASTER (FACULTIES & ROLES) ---
        
        $now = Carbon::now();
        
        // PENGISIAN FACULTIES (LOGIKA DIPINDAHKAN DARI FacultySeeder.php)
        DB::table('faculties')->insert([
            [ 'id' => 1, 'name' => 'Fakultas Ekonomi dan Bisnis', 'code' => 'FEB', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 2, 'name' => 'Fakultas Ilmu Kesehatan', 'code' => 'FIKES', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 3, 'name' => 'Fakultas Perikanan dan Ilmu Kelautan', 'code' => 'FPIK', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 4, 'name' => 'Fakultas Keguruan dan Ilmu Pendidikan', 'code' => 'FKIP', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 5, 'name' => 'Fakultas Teknik dan Ilmu Komputer', 'code' => 'FTIK', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 6, 'name' => 'Fakultas Agama Islam', 'code' => 'FAI', 'created_at' => $now, 'updated_at' => $now, ],
            [ 'id' => 7, 'name' => 'Fakultas Hukum', 'code' => 'FH', 'created_at' => $now, 'updated_at' => $now, ],
        ]);
        
        // --- 5. PANGGIL SISA SEEDER ---
        $this->call([
            // RoleSeeder harus dijalankan setelah Faculties di-truncate
            RoleSeeder::class,      
            
            // User Seeder (Memiliki Ketergantungan ke Role & Faculty)
            UserSeeder::class,      
            
            // Seeder lainnya (Disusun setelah UserSeeder)
            KirimSuratSeeder::class,
            SuratMasukSeeder::class,
            SuratKeluarSeeder::class,
        ]);
    }
}