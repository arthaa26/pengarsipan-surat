<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surat_keluar extends Model
{
    use HasFactory;
    
    protected $table = 'surat_keluar';
    protected $primaryKey = 'id'; 
    
    protected $fillable = [
        'id_surat_keluar',
        'kode_surat',
        'title',       
        'isi_surat',   
        'lampiran',
    ];

    public $timestamps = true;
}