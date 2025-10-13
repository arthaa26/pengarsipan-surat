<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu, untuk menghindari error jika kolom sudah ada (untuk amannya)
            if (!Schema::hasColumn('users', 'faculty_id')) {
                // Tambahkan kolom faculty_id yang merupakan foreign key ke tabel faculties
                // Asumsi tabel 'faculties' sudah ada dan 'role_id' adalah kolom terakhir
                $table->foreignId('faculty_id')->nullable()->after('role_id')->constrained('faculties');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'faculty_id')) {
                // Drop foreign key dulu
                $table->dropForeign(['faculty_id']); 
                $table->dropColumn('faculty_id');
            }
        });
    }
};

    
