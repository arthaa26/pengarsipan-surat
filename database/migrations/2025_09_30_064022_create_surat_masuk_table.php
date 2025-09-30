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
            $table->unsignedBigInteger('id_surat_masuk');
            $table->string("kode_surat")->unique();
            $table->string("title");
            $table->string("isi_surat");
            $table->string("lampiran");
            $table->unsignedBigInteger('user_id_1');
            $table->unsignedBigInteger('user_id_2');
            $table->foreign('user_id_1')->references('id')->on('users');
            $table->foreign('user_id_2')->references('id')->on('users');
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
