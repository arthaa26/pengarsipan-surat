<?php

namespace App\Http\Controllers;

use App\Models\Kirim_surat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage; // Penting untuk penanganan file

class KirimSuratController extends Controller
{
    // --- PRIVATE METHOD UNTUK GENERASI KODE ---
    
    /**
     * Helper untuk mengubah angka bulan menjadi angka Romawi
     * @param int $number
     * @return string
     */
    private function convertToRoman(int $number): string
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$number] ?? 'X'; 
    }

    /**
     * Menghasilkan kode surat sekuensial berikutnya (misalnya, UM/001/X/2025)
     * @return string
     */
    private function generateSuratCode(): string
    {
        $prefix = 'UM'; // Prefix kode surat (misal: Umum)
        $currentYear = Carbon::now()->year;
        $currentMonthRoman = $this->convertToRoman(Carbon::now()->month);

        // Ambil surat terakhir yang dibuat di tahun ini
        $lastSurat = Kirim_surat::whereYear('created_at', $currentYear)
                                ->orderBy('id', 'desc')
                                ->first();

        $newSequence = 1;

        if ($lastSurat) {
            $parts = explode('/', $lastSurat->kode_surat);
            
            if (count($parts) >= 2) {
                // Ekstraksi dan tambahkan 1 ke nomor urut
                $lastSequence = (int) ltrim($parts[1], '0'); 
                $newSequence = $lastSequence + 1;
            }
        }

        // Format nomor urut menjadi 3 digit (misal: 1 -> 001)
        $formattedSequence = str_pad($newSequence, 3, '0', STR_PAD_LEFT);

        // Gabungkan menjadi kode akhir
        return "{$prefix}/{$formattedSequence}/{$currentMonthRoman}/{$currentYear}";
    }

    // --- CRUD METHODS ---

    public function index()
    {
        // Mendapatkan semua surat keluar
        $kirimSurat = Kirim_surat::all();
        // Anda mungkin perlu menyesuaikan view ini jika rutenya berbeda
        return view('surats.index', compact('kirimSurat'));
    }

    public function create()
    {
        // Meneruskan kode berikutnya untuk ditampilkan di formulir (read-only)
        $nextKode = $this->generateSuratCode(); 
        return view('surats.create', compact('nextKode'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus', 
            // Validasi file
            'file_surat' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // Maks 10MB
        ]);

        // 2. Penanganan Upload File
        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Simpan file ke direktori 'storage/app/public/surat_keluar'
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }

        // 3. Generate Kode Unik dan Kompilasi Data
        $validatedData['kode_surat'] = $this->generateSuratCode();
        // Petakan path file ke kolom database 'file_path'
        $validatedData['file_path'] = $filePath; 
        
        // Hapus instance file sementara sebelum disimpan
        unset($validatedData['file_surat']); 

        // 4. Simpan Record ke Database
        Kirim_surat::create($validatedData); 
        
        // 5. Redirect dan berikan pesan sukses
        return redirect()->route('kirim-surat.index')->with('success', 'Surat berhasil dikirim dengan kode: ' . $validatedData['kode_surat']);
    }

    public function show($id)
    {
        $surat = Kirim_surat::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Kirim_surat::findOrFail($id);
        return view('surats.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = Kirim_surat::findOrFail($id);
        
        // 1. Validasi Data (File bersifat 'nullable' karena tidak wajib di-upload ulang)
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ];

        $validatedData = $request->validate($validationRules);

        // 2. Penanganan Update File
        if ($request->hasFile('file_surat')) {
            // Hapus file lama jika ada
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }

            // Upload file baru
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
            
            $validatedData['file_path'] = $filePath;
        }

        // Hapus instance file sementara dan perbarui record
        unset($validatedData['file_surat']); 
        $surat->update($validatedData); 

        return redirect()->route('kirim-surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $surat = Kirim_surat::findOrFail($id);

        // Hapus file terkait dari storage sebelum menghapus record
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }

        $surat->delete(); 
        
        return redirect()->route('kirim-surat.index')->with('success', 'Surat berhasil dihapus.');
    }
}