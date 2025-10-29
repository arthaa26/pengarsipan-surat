<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username', 
        'no_hp', 
        'profile_photo_url', 
        'role_id', 
        'faculty_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    // --- RELATIONS ---

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id'); 
    }
    

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id'); 
    }
    
    public function suratKeluar(): HasMany
    {
        return $this->hasMany(KirimSurat::class, 'user_id_1');
    }

    public function suratMasukPersonal(): HasMany
    {
        return $this->hasMany(KirimSurat::class, 'user_id_2');
    }
}
