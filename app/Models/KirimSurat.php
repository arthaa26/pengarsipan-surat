<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class KirimSurat extends Model 
{
    protected $table = 'kirim_surat';

    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'kode_surat',   
        'title',       
        'isi',
        'tujuan',      
        'file_path',    
    ];

    public $timestamps = true;

    public function user1()
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    // Relasi ke user penerima (opsional)
    public function user2()
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }
}
