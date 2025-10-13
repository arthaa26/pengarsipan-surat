<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name']; // PERBAIKAN: Menambahkan 'display_name' agar bisa diisi oleh Seeder

    public function users()
    {
        return $this->hasMany(Users::class, 'role_id'); // PERBAIKAN: Menggunakan Users::class agar konsisten dengan Model User Anda
    }
}
