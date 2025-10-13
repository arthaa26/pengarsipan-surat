<?php

namespace App\Http\Controllers;

use App\Models\Surat; // Model untuk data arsip surat
use App\Models\User; // Model untuk memilih penerima surat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    /**
     * Menampilkan daftar surat yang relevan dengan user yang sedang login.
     * (Dapat difilter berdasarkan 'masuk' atau 'keluar' dari Query String: ?type=...)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Ambil parameter 'type' dari URL query string
        $type = $request->query('type'); 
        $query = Surat::query();
        $title = "Daftar Semua Surat"; // Default title

        // Logika Filter
        if ($type === 'masuk') {
            // Surat Masuk adalah surat di mana user yang login adalah penerima (to_user_id)
            $query->where('to_user_id', $userId);
            $title = "Daftar Surat Masuk";

        } elseif ($type === 'keluar') {
            // Surat Keluar adalah surat di mana user yang login adalah pengirim (from_user_id)
            $query->where('from_user_id', $userId);
            $title = "Daftar Surat Keluar";
            
        } else {
            // Default: Tampilkan semua surat (masuk & keluar)
            $query->where('to_user_id', $userId)
                  ->orWhere('from_user_id', $userId);
        }

        // Ambil data yang sudah difilter
        $surat = $query->orderBy('created_at', 'desc')->get();

        // Ganti 'surat.index' dengan view yang menampilkan daftar surat
        // Kirim $title dan $type untuk digunakan di view
        return view('surat.index', compact('surat', 'title', 'type'));
    }

    // --- CRUD METHODS (CREATE, STORE, SHOW, EDIT, UPDATE, DESTROY) ---
    // (Tidak ada perubahan signifikan pada logika, hanya dihilangkan untuk brevity)

    public function create()
    {
        $recipients = User::where('id', '!=', Auth::id())->get();
        return view('surat.create', compact('recipients'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input, termasuk validasi untuk file lampiran
        $validatedData = $request->validate([
            'kode_surat' => 'required|string|max:50|unique:surat,kode_surat',
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // 2. Upload File Lampiran jika ada
        if ($request->hasFile('attachment_file')) {
            $filePath = $request->file('attachment_file')->store('attachments', 'public');
            $validatedData['file_path'] = $filePath;
        }

        // 3. Tambahkan informasi pengirim
        $validatedData['from_user_id'] = Auth::id();
        $validatedData['status'] = 'Sent';

        // 4. Simpan ke database
        Surat::create($validatedData);

        return redirect()->route('surat.index', ['type' => 'keluar'])->with('success', 'Surat berhasil dikirim!');
    }

    public function show($id)
    {
        $surat = Surat::findOrFail($id);

        if ($surat->to_user_id !== Auth::id() && $surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat surat ini.');
        }

        if ($surat->to_user_id === Auth::id() && $surat->status === 'Sent') {
            $surat->update(['status' => 'Read']);
        }
        
        return view('surat.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        
        if ($surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda hanya dapat mengedit surat yang Anda kirim.');
        }

        $recipients = User::where('id', '!=', Auth::id())->get();
        return view('surat.edit', compact('surat', 'recipients'));
    }

    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);
        
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
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ]);
        
        // 2. Handle File Update
        if ($request->hasFile('attachment_file')) {
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }
            $filePath = $request->file('attachment_file')->store('attachments', 'public');
            $validatedData['file_path'] = $filePath;
        }

        // 3. Perbarui data
        $surat->update($validatedData);

        return redirect()->route('surat.index', ['type' => 'keluar'])->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);

        if ($surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda hanya dapat menghapus surat yang Anda kirim.');
        }
        
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }

        $surat->delete();
        // Redirect ke daftar surat keluar setelah menghapus
        return redirect()->route('surat.index', ['type' => 'keluar'])->with('success', 'Surat berhasil dihapus.');
    }
    
    // --- FILE UTILITIES ---

    /**
     * Menampilkan file lampiran di browser.
     * Menggunakan model binding untuk mendapatkan objek Surat.
     */
    public function viewSurat(Surat $surat)
    {
         // Pengecekan izin (SAMA seperti di downloadSurat)
         if ($surat->to_user_id !== Auth::id() && $surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat lampiran surat ini.');
        }

        $filePath = $surat->file_path;

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            // response()->file() atau response()->make() dengan konten file
            // response()->file() seringkali lebih mudah untuk dilihat langsung
            return response()->file(Storage::disk('public')->path($filePath));
        }

        return back()->with('error', 'File lampiran tidak ditemukan.');
    }


    /**
     * Mengunduh file lampiran surat.
     */
    public function downloadSurat(Surat $surat)
    {
        // Pengecekan izin: hanya pengirim atau penerima yang boleh mengunduh
        if ($surat->to_user_id !== Auth::id() && $surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk mengunduh lampiran surat ini.');
        }

        $filePath = $surat->file_path; 

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $absolutePath = Storage::disk('public')->path($filePath);
            $fileName = $surat->kode_surat . '_' . basename($filePath);
            
            return response()->download($absolutePath, $fileName);
        }
        
        return back()->with('error', 'File lampiran tidak ditemukan atau telah dihapus.');
    }
}