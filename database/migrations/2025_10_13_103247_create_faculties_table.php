<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama Fakultas (Fakultas Ekonomi dan Bisnis)
            $table->string('code', 10)->nullable()->unique(); // Kode singkat (FEB, FIKES, dll)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
