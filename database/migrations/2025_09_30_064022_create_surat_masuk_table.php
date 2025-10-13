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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->bigIncrements('id_surat_masuk'); 
            $table->string("kode_surat", 255)->unique(); // Tambah batas panjang
            $table->string("title", 255);
            $table->text("isi_surat");
            $table->string("lampiran", 255)->nullable(); // Lampiran mungkin kosong (nullable)
            $table->foreignId('user_id_1')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id_2')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};