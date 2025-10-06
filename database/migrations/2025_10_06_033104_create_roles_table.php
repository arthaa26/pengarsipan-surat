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
        // ✅ Perbaikan 1: Gunakan nama tabel jamak 'roles' (Konvensi Laravel)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            
            // ✅ Perbaikan 2: Tambahkan kolom 'name' yang unik
            $table->string('name')->unique(); 
            
            // ✅ Perbaikan 3: Tambahkan kolom 'description'
            $table->string('description')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ Gunakan nama tabel jamak 'roles'
        Schema::dropIfExists('roles');
    }
};