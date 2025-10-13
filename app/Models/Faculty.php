<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = ['name', 'code'];
    // Relasi ke tabel 'users'
    public function users()
    {
        return $this->hasMany(User::class, 'faculty_id');
    }
}
