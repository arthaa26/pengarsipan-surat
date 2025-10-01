<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat_masuk extends Model
{
    protected $table = 'surat_masuk';

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
