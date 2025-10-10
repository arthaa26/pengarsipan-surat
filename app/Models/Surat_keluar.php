<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- Wajib

class Surat_keluar extends Model
{
    use HasFactory;
    
    protected $table = 'surat_keluar';
    protected $primaryKey = 'id'; // Asumsi: Primary key tabel ini adalah 'id'
    
    protected $fillable = [
        'id_surat_keluar',
        'kode_surat',
        'title',        // Nama kolom di DB Anda
        'isi_surat',    // Nama kolom di DB Anda
        'lampiran',
        // Tambahkan 'file_surat' jika ada di tabel keluar!
    ];

    public $timestamps = true;
}