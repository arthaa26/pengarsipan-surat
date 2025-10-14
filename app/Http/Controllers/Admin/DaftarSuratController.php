<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\KirimSurat; 
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\Log; 

class DaftarSuratController extends Controller
{

    public function index()
    {
        try {
            $suratList = KirimSurat::with(['user1.faculty'])
                                           ->orderBy('created_at', 'desc')
                                           // ✅ FIX 2: Use Eloquent's built-in paginate() for simplicity and efficiency
                                           ->paginate(10);
            
        } catch (\Exception $e) {
            Log::error('Error loading Admin DaftarSurat index: ' . $e->getMessage());
            
            $suratList = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            session()->flash('error', 'Gagal memuat data surat. Lihat log server.');
        }

        return view('admin.daftarsurat.index', compact('suratList'));
    }

    /**
     * Menampilkan detail surat tertentu.
     * Metode ini dipanggil oleh rute admin.surat.view (GET /admin/surat/{id}/detail)
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Cari surat berdasarkan ID, memuat relasi yang diperlukan (user1, user2, tujuanFaculty).
        $surat = KirimSurat::with(['user1.faculty', 'user2.faculty', 'tujuanFaculty'])
                      ->findOrFail($id);
        
        // Mengirim data ke view Blade
        // Anda harus membuat file view ini di resources/views/admin/surat/show_detail.blade.php
        return view('admin.surat.show_detail', compact('surat'));
    }

    public function downloadFile($id)
    {
        $surat = KirimSurat::findOrFail($id); 
        $filePath = $surat->file_path; 

        if (!$surat->file_path || !Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', "File lampiran tidak ditemukan.");
        }

        $fileName = $surat->kode_surat . '_' . basename($surat->file_path);
        
        return Storage::disk('public')->download($filePath, $fileName);
    }

    public function previewFile($id)
    {
        $surat = KirimSurat::findOrFail($id);
        $filePath = $surat->file_path; 

        if (!$surat->file_path || !Storage::disk('public')->exists($filePath)) {
             abort(404, "File lampiran tidak ditemukan.");
        }

        return Storage::disk('public')->response($filePath, null, [
            'Content-Type' => Storage::disk('public')->mimeType($filePath),
            'Content-Disposition' => 'inline; filename="' . basename($surat->kode_surat) . '"' 
        ]);
    }
    
    public function destroy($id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        // Untuk menghapus file dari storage
        if ($surat->file_path && Storage::disk('public')->exists($surat->file_path)) {
            Storage::disk('public')->delete($surat->file_path);
        }
        
        $surat->delete();

        return redirect()->route('admin.daftarsurat.index')->with('success', 'Surat berhasil dihapus.');
    }
}
