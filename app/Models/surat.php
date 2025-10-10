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
        'file_path', // <--- PENTING: Untuk aksi Preview/Download
    ];

    // Relasi: Surat ditujukan ke (penerima)
    public function receiver()
    {
        // PERBAIKAN: Ganti 'User::class' menjadi 'Users::class' (sesuai Model jamak Anda)
        return $this->belongsTo(\App\Models\Users::class, 'to_user_id'); 
    }

    // Relasi: Surat dikirim dari (pengirim)
    public function sender()
    {
        // PERBAIKAN: Ganti 'User::class' menjadi 'Users::class'
        return $this->belongsTo(\App\Models\Users::class, 'from_user_id');
    }
}