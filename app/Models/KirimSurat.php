<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Impor Model yang dibutuhkan
use App\Models\User; 
use App\Models\Faculty; 

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
        'tujuan_faculty_id',
    ];

    public $timestamps = true;

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    // Relasi ke user penerima (opsional)
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }
    
    // --- RELASI BARU ---
    /**
     * Relasi ke Fakultas tujuan surat (digunakan untuk Dekan/Dosen/Kaprodi).
     */
    public function tujuanFaculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'tujuan_faculty_id');
    }
}