<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\surat; 
use App\Models\Surat_keluar; 
use App\Models\Surat_masuk;  
use App\Models\KirimSurat;

class DaftarSuratController extends Controller
{
    /**
     * Menampilkan daftar semua surat (History Surat) dengan penggabungan data.
     * Route: admin.daftarsurat.index
     */
    public function index()
    {
        try {
            // 1. Ambil data dari Surat Masuk (Menggunakan aliasing yang akurat)
            $dataMasuk = KirimSurat::select(
                'id_surat_keluar as id', // Alias ID dari kolom id_surat_keluar
                'kode_surat', 
                'tittle as title',      // Alias tittle (salah ketik di DB) menjadi title (di View)
                'isi_surat as isi',     // Alias isi_surat menjadi isi
                'created_at as tanggal_surat'
            )->get();
            
            $dataKeluar = KirimSurat::select(
                'id as id',            
                'kode_surat', 
                'title',                
                'isi_surat as isi',     
                'created_at as tanggal_surat'
            )->get();
            
            // 3. Gabungkan semua data, urutkan, dan terapkan Pagination Manual
            $allData = $dataMasuk->merge($dataKeluar)->sortByDesc('tanggal_surat');
            
            // Logika Pagination Manual
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $suratHistory = new LengthAwarePaginator(
                $currentItems, 
                $allData->count(), 
                $perPage, 
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );

            
        } catch (\Exception $e) {
          
            $suratHistory = collect([
                (object)['id' => 101, 'kode_surat' => 'GAGAL', 'title' => 'KONEKSI / MODEL ERROR', 'isi' => 'Data untuk memastikan tombol muncul'],
            ]);
        
        }

        return view('admin.daftarsurat.index', compact('suratHistory'));
    }

    public function showDetail($id)
    {
        $surat = Surat::findOrFail($id); 
        return view('admin.daftarsurat.show_detail', compact('surat'));
    }
    public function downloadFile($id)
    {
        // Asumsi file_surat ada di Model Surat
        $surat = Surat::findOrFail($id);
        $filePath = 'public/' . $surat->file_surat; 

        if (!Storage::exists($filePath)) {
            abort(404, "File lampiran tidak ditemukan.");
        }

        $fileName = $surat->kode_surat . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        return Storage::download($filePath, $fileName);
    }

    /**
     * Mengambil file surat untuk dilihat (Preview/Cetak). Route: surat.preview_file
     */
    public function previewFile($id)
    {
        $surat = Surat::findOrFail($id);
        $filePath = 'public/' . $surat->file_surat; 

        if (!Storage::exists($filePath)) {
            abort(404, "File lampiran tidak ditemukan.");
        }

        return Storage::response($filePath, null, [
            'Content-Type' => Storage::mimeType($filePath),
            'Content-Disposition' => 'inline; filename="' . basename($surat->kode_surat) . '"'
        ]);
    }
    
    // Method resource lainnya
    public function create() { return view('admin.daftarsurat.create'); }
    public function store(Request $request) { /* ... */ }
    public function show($id) { return $this->showDetail($id); }
    public function edit($id) { /* ... */ }
    public function update(Request $request, $id) { /* ... */ }
    public function destroy($id) { /* ... */ }
}