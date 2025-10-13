<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // WAJIB impor

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Pastikan semua kolom yang digunakan sudah ada di $fillable
    protected $fillable = [
        'name',
        'email',
        'password',
        'username', 
        'no_hp',    
        'profile_photo_url', 
        'role_id',  
        'faculty_id', // Pastikan kolom ini diizinkan
    ];

    // ... (metode tersembunyi dan cast tidak berubah) ...

    /**
     * Dapatkan Role (Jabatan) yang terkait dengan pengguna.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id'); 
    }
    
    // --- RELASI YANG HILANG ---
    /**
     * Dapatkan Fakultas/Unit yang terkait dengan pengguna.
     */
    public function faculty(): BelongsTo
    {
        // Asumsi: foreign key di tabel users adalah 'faculty_id'
        // dan Model Fakultas bernama 'Faculty'
        return $this->belongsTo(Faculty::class, 'faculty_id'); 
    }
}
