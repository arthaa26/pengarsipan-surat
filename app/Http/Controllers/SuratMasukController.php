<?php

namespace App\Http\Controllers;

use App\Models\Surat_masuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = Surat_masuk::all();
        return view('surats.index', compact('suratMasuk'));
    }

    public function create()
    {
        return view('surats.create');
    }

    public function store(Request $request)
    {
        Surat_masuk::create($request->all());
        return redirect()->route('surat-masuk.index');
    }

    public function show($id)
    {
        $surat = Surat_masuk::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Surat_masuk::findOrFail($id);
        return view('surats.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = Surat_masuk::findOrFail($id);
        $surat->update($request->all());
        return redirect()->route('surat-masuk.index');
    }
    <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    // Menghilangkan Error "Class not found"
    // Perbaikan: Pastikan Model ini terhubung ke tabel yang benar.
    protected $table = 'surat_masuk'; 

    // Anda perlu menambahkan kolom yang digunakan di Controller ke $fillable
    protected $fillable = [
        'kode_surat', 
        'title', 
        'isi',
        'created_at',
        // Tambahkan kolom lain di tabel surat_masuk yang Anda butuhkan
    ];
}

    public function destroy($id)
    {
        Surat_masuk::destroy($id);
        return redirect()->route('surat-masuk.index');
    }
}