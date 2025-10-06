<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;
    
    // Tentukan nama tabel di database jika tidak menggunakan konvensi Laravel (plural: surats)
    // Asumsi nama tabel adalah 'surat'
    protected $table = 'surat'; 
    
    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'kode_surat', 
        'title', 
        'isi', 
        'to_user_id', // ID penerima (Dosen/Admin)
        'from_user_id', // ID pengirim (Dosen/Admin)
        'status', // Contoh: Draft, Sent, Approved
    ];

    // Relasi: Surat ditujukan ke (penerima)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    // Relasi: Surat dikirim dari (pengirim)
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
