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
        Schema::table('kirim_surat', function (Blueprint $table) {
            // Kolom ini digunakan untuk memfilter surat berdasarkan fakultas tujuan
            if (!Schema::hasColumn('kirim_surat', 'tujuan_faculty_id')) {
                // Menambahkan foreign key ke tabel 'faculties'
                $table->foreignId('tujuan_faculty_id')
                      ->nullable()
                      ->after('tujuan')
                      ->constrained('faculties');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kirim_surat', function (Blueprint $table) {
            if (Schema::hasColumn('kirim_surat', 'tujuan_faculty_id')) {
                $table->dropConstrainedForeignId('tujuan_faculty_id');
            }
        });
    }
};
