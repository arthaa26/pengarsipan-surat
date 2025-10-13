<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Pastikan model User diimport untuk relasi

class KirimSurat extends Model // DIGANTI DARI Kirim_surat menjadi KirimSurat
{
    protected $table = 'kirim_surat';

    protected $fillable = [
        'user_id_1',
        'user_id_2',
        // --- Kolom yang Ditambahkan/Disesuaikan ---
        'kode_surat',   // Diperbarui dari 'no_surat'
        'title',        // Ditambahkan (digunakan di Controller)
        'isi',
        'tujuan',       // Ditambahkan (digunakan di Controller)
        'file_path',    // Diperbarui dari 'lampiran' (path file)
        // 'tanggal_surat' - Dihapus karena tidak digunakan/tidak diisi
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
