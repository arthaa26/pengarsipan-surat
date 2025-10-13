<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Impor BelongsTo

// Catatan: Jika nama model Anda di aplikasi adalah 'Users' (plural), 
// ganti 'User' di sini dan di file Model.php Anda.
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username', // Dari UserSeeder
        'no_hp',    // Dari form update profil
        'profile_photo_url', // Dari form update profil
        'role_id',  // Kolom yang menyimpan ID Role
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // --- RELASI ROLE ---
    /**
     * Dapatkan Role (Jabatan) yang terkait dengan pengguna.
     */
    public function role(): BelongsTo
    {
        // Asumsi foreign key di tabel users adalah 'role_id'
        // dan Model Role bernama 'Role'
        return $this->belongsTo(Role::class, 'role_id'); 
    }
}
