<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- Wajib

class Surat_masuk extends Model
{
    use HasFactory;
    
    protected $table = 'surat_masuk';
    protected $primaryKey = 'id_surat_keluar';
    
    protected $fillable = [
        'id_surat_keluar',
        'kode_surat',
        'tittle',      
        'isi_surat',    
        'lampiran',
        'file_surat',
    ];

    public $timestamps = true;
}