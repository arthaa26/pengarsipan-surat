<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;
    
    protected $table = 'surat'; 
    
    protected $fillable = [
        'kode_surat', 
        'title', 
        'isi', 
        'to_user_id', 
        'from_user_id', 
        'status', 
        'file_path', 
    ];

    public function receiver()
    {
        return $this->belongsTo(\App\Models\Users::class, 'to_user_id'); 
    }

    public function sender()
    {
        return $this->belongsTo(\App\Models\Users::class, 'from_user_id');
    }
}