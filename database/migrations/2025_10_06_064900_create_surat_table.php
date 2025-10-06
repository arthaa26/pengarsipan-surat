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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();

            $table->string('kode_surat', 50)->unique();
            $table->string('title');
            $table->text('isi');
            $table->foreignId('to_user_id') 
                  ->constrained('users') 
                  ->onDelete('cascade'); 
            $table->foreignId('from_user_id')
                  ->constrained('users') 
                  ->onDelete('cascade'); 
            $table->string('status')->default('Sent'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
