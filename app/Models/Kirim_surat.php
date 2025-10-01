<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kirim_surat extends Model
{
    protected $table = 'kirim_surat';

    protected $fillable = [
        'id',
        'no_surat',
        'tanggal_surat',
        'isi',
        'lampiran',
    ];

    public $timestamps = true;
}
