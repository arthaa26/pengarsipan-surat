<?php

namespace App\Http\Controllers;

use App\Models\Surat; // Model untuk data arsip surat
use App\Models\User; // Model untuk memilih penerima surat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // <-- [NEW] Import untuk file storage

class SuratController extends Controller
{
    /**
     * Menampilkan daftar surat yang relevan dengan user yang sedang login.
     * (Surat yang dikirim atau diterima oleh user ini).
     */
    public function index()
    {
        $userId = Auth::id();

        // Mengambil surat di mana user ini adalah pengirim ATAU penerima
        $surat = Surat::where('to_user_id', $userId)
                         ->orWhere('from_user_id', $userId)
                         ->orderBy('created_at', 'desc')
                         ->get();

        // Ganti 'surat.index' dengan view yang menampilkan daftar surat
        return view('surat.index', compact('surat'));
    }

    /**
     * Menampilkan formulir untuk membuat surat baru.
     * Dibutuhkan daftar user untuk memilih penerima.
     */
    public function create()
    {
        // Ambil semua user kecuali user yang sedang login
        $recipients = User::where('id', '!=', Auth::id())->get();
        return view('surat.create', compact('recipients'));
    }

    /**
     * Menyimpan surat baru ke database, termasuk file lampiran.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input, termasuk validasi untuk file lampiran
        $validatedData = $request->validate([
            'kode_surat' => 'required|string|max:50|unique:surat,kode_surat',
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
            // [UPDATED] Validasi file: opsional, tipe file (pdf, gambar), maks 10MB
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ]);

        // 2. Upload File Lampiran jika ada
        if ($request->hasFile('attachment_file')) {
            // Simpan file ke direktori 'public/attachments'. 'public' adalah disk storage.
            $filePath = $request->file('attachment_file')->store('attachments', 'public');
            // [UPDATED] Simpan path file di kolom 'file_path'
            $validatedData['file_path'] = $filePath; 
        }

        // 3. Tambahkan informasi pengirim (dari user yang sedang login)
        $validatedData['from_user_id'] = Auth::id();
        $validatedData['status'] = 'Sent'; // Status default: Terkirim

        // 4. Simpan ke database
        Surat::create($validatedData);

        // Redirect kembali ke halaman daftar surat dengan pesan sukses
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dikirim!');
    }

    /**
     * Menampilkan detail surat tertentu.
     */
    public function show($id)
    {
        $surat = Surat::findOrFail($id);

        // ⚠️ Tambahkan pengecekan kepemilikan
        if ($surat->to_user_id !== Auth::id() && $surat->from_user_id !== Auth::id()) {
             abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat surat ini.');
        }

        // Opsional: Jika user yang melihat adalah penerima, ubah status menjadi 'Read'
        if ($surat->to_user_id === Auth::id() && $surat->status === 'Sent') {
             $surat->update(['status' => 'Read']);
        }
        
        return view('surat.show', compact('surat'));
    }

    /**
     * Menampilkan formulir untuk mengedit surat.
     */
    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        
        // Hanya pengirim yang boleh mengedit
        if ($surat->from_user_id !== Auth::id()) {
             abort(403, 'Akses Ditolak. Anda hanya dapat mengedit surat yang Anda kirim.');
        }

        $recipients = User::where('id', '!=', Auth::id())->get();
        return view('surat.edit', compact('surat', 'recipients'));
    }

    /**
     * Memperbarui surat di database.
     */
    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);
        
        // Pengecekan izin (hanya pengirim yang bisa update)
        if ($surat->from_user_id !== Auth::id()) {
             abort(403, 'Akses Ditolak.');
        }

        // 1. Validasi Input
        $validatedData = $request->validate([
            'kode_surat' => ['required', 'string', 'max:50', 
                             Rule::unique('surat', 'kode_surat')->ignore($surat->id)],
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
            // [UPDATED] Validasi file untuk update (opsional)
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ]);
        
        // 2. Handle File Update
        if ($request->hasFile('attachment_file')) {
            // Hapus file lama jika ada
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }
            // Upload file baru
            $filePath = $request->file('attachment_file')->store('attachments', 'public');
            $validatedData['file_path'] = $filePath;
        }

        // 3. Perbarui data
        $surat->update($validatedData);

        return redirect()->route('surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    /**
     * Menghapus surat dari database, termasuk file lampiran.
     */
    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);

        // Pengecekan izin (hanya pengirim yang boleh menghapus)
        if ($surat->from_user_id !== Auth::id()) {
             abort(403, 'Akses Ditolak. Anda hanya dapat menghapus surat yang Anda kirim.');
        }
        
        // [UPDATED] Hapus file lampiran dari storage jika ada
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }

        $surat->delete();
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus.');
    }
    
    // --------------------------------------------------------------------------------------
    // [NEW] METHOD UNTUK MENGUNDUH FILE LAMPIRAN
    // --------------------------------------------------------------------------------------
    /**
     * Mengunduh file lampiran surat.
     * Menggunakan model binding untuk mendapatkan objek Surat.
     */
    public function downloadSurat(Surat $surat)
    {
        // Pengecekan izin: hanya pengirim atau penerima yang boleh mengunduh
        if ($surat->to_user_id !== Auth::id() && $surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk mengunduh lampiran surat ini.');
        }

        // Asumsi nama kolom di database adalah 'file_path'
        $filePath = $surat->file_path; 

        // Pastikan path file ada dan file-nya benar-benar tersimpan di storage 'public'
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            
            // [FIXED] Mengambil path absolut dan menggunakan response()->download()
            $absolutePath = Storage::disk('public')->path($filePath);
            
            // response()->download akan memaksa browser untuk mengunduh file
            // Nama file yang diunduh akan digabungkan dengan kode surat untuk kejelasan
            $fileName = $surat->kode_surat . '_' . basename($filePath);
            
            return response()->download($absolutePath, $fileName);
        }
        
        // Jika file tidak ditemukan di storage
        return back()->with('error', 'File lampiran tidak ditemukan atau telah dihapus.');
    }
    // --------------------------------------------------------------------------------------
    // [NEW] METHOD UNTUK MELIHAT FILE LAMPIRAN DI BROWSER
    // --------------------------------------------------------------------------------------
    /**
     * Menampilkan file lampiran di browser.
     */
    public function viewFileSurat(Surat $surat)
    {
        // Pengecekan izin
        if ($surat->to_user_id !== Auth::id() && $surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        // Asumsi nama kolom di database adalah 'file_path'
        $filePath = $surat->file_path; 

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            // response()->file() akan mencoba menampilkan file di browser (inline)
            // Ini sangat cocok untuk PDF atau gambar
            return response()->file(Storage::disk('public')->path($filePath));
        }
        
        return back()->with('error', 'File lampiran tidak ditemukan.');
    }
    // --------------------------------------------------------------------------------------
}
