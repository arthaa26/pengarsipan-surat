<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $type = $request->query('type'); 
        $query = Surat::query();
        $title = "Daftar Semua Surat";

        if ($type === 'masuk') {
            $query->where('to_user_id', $userId);
            $title = "Daftar Surat Masuk";

        } elseif ($type === 'keluar') {
            $query->where('from_user_id', $userId);
            $title = "Daftar Surat Keluar";
            
        } else {
            $query->where('to_user_id', $userId)
                  ->orWhere('from_user_id', $userId);
        }

        $surat = $query->orderBy('created_at', 'desc')->get();

        return view('surat.index', compact('surat', 'title', 'type'));
    }

    public function create()
    {
        $recipients = User::where('id', '!=', Auth::id())->get();
        return view('surat.create', compact('recipients'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_surat' => 'required|string|max:50|unique:surat,kode_surat',
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('attachment_file')) {
            $filePath = $request->file('attachment_file')->store('attachments', 'public');
            $validatedData['file_path'] = $filePath;
        }

        $validatedData['from_user_id'] = Auth::id();
        $validatedData['status'] = 'Sent';
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

        $validatedData = $request->validate([
            'kode_surat' => ['required', 'string', 'max:50', 
                              Rule::unique('surat', 'kode_surat')->ignore($surat->id)],
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ]);
        
        if ($request->hasFile('attachment_file')) {
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }
            $filePath = $request->file('attachment_file')->store('attachments', 'public');
            $validatedData['file_path'] = $filePath;
        }

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
        return redirect()->route('surat.index', ['type' => 'keluar'])->with('success', 'Surat berhasil dihapus.');
    }
    

    public function viewSurat(Surat $surat)
    {
         if ($surat->to_user_id !== Auth::id() && $surat->from_user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat lampiran surat ini.');
        }

        $filePath = $surat->file_path;

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return response()->file(Storage::disk('public')->path($filePath));
        }

        return back()->with('error', 'File lampiran tidak ditemukan.');
    }

    public function downloadSurat(Surat $surat)
    {
        // Permisionn izin: untuk pengirim atau penerima yang boleh mengunduh
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