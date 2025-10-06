<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kirim_surat extends Model
{
    protected $table = 'kirim_surat';

    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'no_surat',
        'tanggal_surat',
        'isi',
        'lampiran',
    ];

    public $timestamps = true;

    // Relasi ke user pengirim
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
