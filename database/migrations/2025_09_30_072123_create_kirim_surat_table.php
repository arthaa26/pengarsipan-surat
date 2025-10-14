<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('kirim_surat', function (Blueprint $table) {
            $table->id(); 
            // Kolom User ID
            $table->foreignId('user_id_1')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id_2')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('kode_surat')->unique()->nullable(); 
            $table->string('title');
            $table->text('isi');
            $table->string('tujuan');
            $table->string('file_path')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kirim_surat');
    }
};
