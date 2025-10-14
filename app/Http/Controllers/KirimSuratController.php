<?php

namespace App\Http\Controllers;

use App\Models\KirimSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 
use App\Models\Faculty; // DITAMBAHKAN: Memuat model Faculty

class KirimSuratController extends Controller
{

    private function convertToRoman(int $number): string
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$number] ?? 'X'; 
    }


    private function generateSuratCode(): string
    {
        $prefix = 'UM';
        $currentYear = Carbon::now()->year;
        $currentMonthRoman = $this->convertToRoman(Carbon::now()->month);
        $lastSurat = KirimSurat::whereYear('created_at', $currentYear)
                               ->orderBy('id', 'desc')
                               ->first();

        $newSequence = 1;

        if ($lastSurat) {
            $parts = explode('/', $lastSurat->kode_surat);
            
            if (count($parts) >= 2) {
                $lastSequence = (int) ltrim($parts[1], '0'); 
                $newSequence = $lastSequence + 1;
            }
        }

        $formattedSequence = str_pad($newSequence, 3, '0', STR_PAD_LEFT);

        return "{$prefix}/{$formattedSequence}/{$currentMonthRoman}/{$currentYear}";
    }

    // logika metode crud

    public function index()
    {
        $kirimSurat = KirimSurat::all();
        return view('surats.index', compact('kirimSurat'));
    }

    public function create()
    {
        $nextKode = $this->generateSuratCode(); 
        // FIX: Mengirim daftar semua Fakultas ke view Kirim Surat
        $allFaculties = Faculty::all(); 
        return view('surats.create', compact('nextKode', 'allFaculties')); 
    }

    public function store(Request $request)
    {
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            // FIX: Tambahkan 'kaprodi' dan 'target_type'
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus,kaprodi', 
            'target_type' => 'required|string|in:universitas,spesifik', // Menambah target_type
            'file_surat' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];
        
        // FIX: Tambahkan validasi kondisional untuk target_faculty_id
        if ($request->input('target_type') === 'spesifik') {
            // Kita validasi target_faculty_id (dropdown)
            $validationRules['target_faculty_id'] = 'required|integer|exists:faculties,id'; 
        }

        $validatedData = $request->validate($validationRules);

        // --- Proses File Upload ---
        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }

        // --- Membentuk Data Akhir untuk Disimpan ---
        $dataToStore = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
            'tujuan' => $validatedData['tujuan'],
            'user_id_1' => Auth::id(), 
            'kode_surat' => $this->generateSuratCode(),
            'file_path' => $filePath,
        ];
        
        // FIX: Menangani tujuan_faculty_id - NULL jika Universitas, ID jika Spesifik
        if ($request->input('target_type') === 'spesifik') {
            // Mengambil ID Fakultas yang sudah tervalidasi dari dropdown
            $dataToStore['tujuan_faculty_id'] = $validatedData['target_faculty_id'];
        } else {
            // Jika universitas, ID Fakultas NULL (menargetkan semua Fakultas)
            $dataToStore['tujuan_faculty_id'] = null;
        }
        
        // --- TIDAK PERLU UNSET: $dataToStore sudah bersih ---

        KirimSurat::create($dataToStore); 
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil dikirim dengan kode: ' . $dataToStore['kode_surat']);
    }

    public function show($id)
    {
        $surat = KirimSurat::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
        // DITAMBAHKAN: Mengirim daftar semua Fakultas ke view Edit Surat
    {
        $surat = KirimSurat::findOrFail($id);
        $allFaculties = Faculty::all();
        return view('surats.edit', compact('surat', 'allFaculties'));
    }

    public function update(Request $request, $id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            // FIX: Tambahkan 'kaprodi' dan 'target_type'
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus,kaprodi',
            'target_type' => 'required|string|in:universitas,spesifik',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ];

        // FIX: Tambahkan validasi kondisional untuk target_faculty_id (select dropdown)
        if ($request->input('target_type') === 'spesifik') {
            $validationRules['target_faculty_id'] = 'required|integer|exists:faculties,id'; 
        }

        $validatedData = $request->validate($validationRules);
        
        // Membangun data update secara eksplisit
        $dataToUpdate = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
            'tujuan' => $validatedData['tujuan'],
        ];


        // --- File Upload Logic ---
        if ($request->hasFile('file_surat')) {
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }

            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
            
            $dataToUpdate['file_path'] = $filePath;
        }
        
        // FIX: Menangani tujuan_faculty_id untuk update
        if ($request->input('target_type') === 'spesifik') {
            $dataToUpdate['tujuan_faculty_id'] = $validatedData['target_faculty_id'] ?? null;
        } else {
            $dataToUpdate['tujuan_faculty_id'] = null;
        }

        // --- TIDAK PERLU UNSET: $dataToUpdate sudah bersih ---

        $surat->update($dataToUpdate); 
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $surat = KirimSurat::findOrFail($id);
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }
        $surat->delete(); 
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil dihapus.');
    }
}
