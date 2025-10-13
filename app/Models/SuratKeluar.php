<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat_keluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'id_surat_keluar',
        'kode_surat',
        'title',
        'isi_surat',
        'lampiran',
    ];

    public $timestamps = true;
}
