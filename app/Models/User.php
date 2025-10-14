<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id'); 
    }
    

    public function faculty(): BelongsTo
    {

        return $this->belongsTo(Faculty::class, 'faculty_id'); 
    }
}
