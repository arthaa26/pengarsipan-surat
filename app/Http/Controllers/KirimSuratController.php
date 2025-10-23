<?php

namespace App\Http\Controllers;

use App\Models\KirimSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Ditambahkan: Untuk Rule::in atau Rule::exists

use App\Models\Faculty; 
use App\Models\User; // DITAMBAHKAN: Jika Anda perlu mencari user tertentu

class KirimSuratController extends Controller
{

    /**
     * Mengkonversi bulan menjadi angka romawi.
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
     * Menghasilkan kode surat baru dengan format UM/001/BULAN_ROMAWI/TAHUN.
     */
    private function generateSuratCode(): string
    {
        $prefix = 'UM';
        $currentYear = Carbon::now()->year;
        $currentMonthRoman = $this->convertToRoman(Carbon::now()->month);
        
        // Cari kode surat terakhir yang dibuat di tahun ini
        $lastSurat = KirimSurat::whereYear('created_at', $currentYear)
                               ->orderBy('id', 'desc')
                               ->first();

        $newSequence = 1;

        if ($lastSurat && $lastSurat->kode_surat) {
            $parts = explode('/', $lastSurat->kode_surat);
            
            // Periksa jika formatnya valid (minimal 4 bagian: UM/001/X/2025)
            if (count($parts) >= 4) {
                // Ambil urutan (bagian kedua) dan konversi ke integer
                $lastSequence = (int) ltrim($parts[1], '0'); 
                $newSequence = $lastSequence + 1;
            }
        }

        $formattedSequence = str_pad($newSequence, 3, '0', STR_PAD_LEFT);

        return "{$prefix}/{$formattedSequence}/{$currentMonthRoman}/{$currentYear}";
    }

    // --- LOGIKA CRUD UTAMA ---

    /**
     * Menampilkan daftar semua surat.
     * (Asumsi ini adalah index untuk user yang membuat surat, bukan Admin)
     */
    public function index()
    {
        // Hanya tampilkan surat yang dibuat oleh user yang login
        $kirimSurat = KirimSurat::where('user_id_1', Auth::id())->paginate(15);
        return view('surats.index', compact('kirimSurat'));
    }

    /**
     * Menampilkan form untuk membuat surat baru.
     */
    public function create()
    {
        $nextKode = $this->generateSuratCode(); 
        $allFaculties = Faculty::orderBy('name')->get(); 
        
        // Daftar tujuan valid
        $tujuanOptions = [
            'rektor' => 'Rektor', 
            'dekan' => 'Dekan', 
            'kaprodi' => 'Ketua Program Studi (Kaprodi)',
            'dosen' => 'Dosen',
            'tenaga_pendidik' => 'Tenaga Kependidikan', 
            'dosen_tugas_khusus' => 'Dosen Tugas Khusus', 
        ];

        return view('surats.create', compact('nextKode', 'allFaculties', 'tujuanOptions')); 
    }

    /**
     * Menyimpan surat yang baru dibuat.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => ['required', 'string', 
                         Rule::in(['rektor', 'dekan', 'kaprodi', 'dosen', 'tenaga_pendidik', 'dosen_tugas_khusus'])], 
            'target_type' => ['required', 'string', Rule::in(['universitas', 'spesifik'])],
            'file_surat' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];
        
        // Validasi kondisional untuk target_faculty_id
        if ($request->input('target_type') === 'spesifik') {
            $validationRules['target_faculty_id'] = ['required', 'integer', Rule::exists('faculties', 'id')]; 
        }

        $validatedData = $request->validate($validationRules);

        // --- Proses File Upload ---
        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }

        // --- Membentuk Data Akhir untuk Disimpan ---
        $dataToStore = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
            'tujuan' => $validatedData['tujuan'],
            'user_id_1' => Auth::id(), // Pengirim adalah user yang login
            'kode_surat' => $this->generateSuratCode(),
            'file_path' => $filePath,
            // Menentukan tujuan_faculty_id
            'tujuan_faculty_id' => ($validatedData['target_type'] === 'spesifik') 
                                   ? $validatedData['target_faculty_id'] 
                                   : null,
            // user_id_2 (Penerima user spesifik) dikosongkan/null di sini, 
            // karena logikanya mungkin ditangani di middleware atau proses lain.
            'user_id_2' => null,
        ];
        
        KirimSurat::create($dataToStore); 
        
        return redirect()->route('user.kirim_surat.index')
                         ->with('success', 'Surat berhasil dikirim dengan kode: ' . $dataToStore['kode_surat']);
    }

    /**
     * Menampilkan detail surat.
     */
    public function show($id)
    {
        $surat = KirimSurat::with(['user1.faculty', 'tujuanFaculty'])
                           ->findOrFail($id);
        
        // Optional: Tambahkan otorisasi
        if ($surat->user_id_1 !== Auth::id() && Auth::user()->role_id !== 1) { // Asumsi role_id 1 adalah Admin
             abort(403, 'Akses Ditolak. Anda tidak berhak melihat surat ini.');
        }

        return view('surats.show', compact('surat'));
    }

    /**
     * Menampilkan form untuk mengedit surat.
     */
    public function edit($id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        // Optional: Tambahkan otorisasi (hanya pembuat yang bisa edit)
        if ($surat->user_id_1 !== Auth::id()) {
             abort(403, 'Akses Ditolak. Anda tidak berhak mengedit surat ini.');
        }

        $allFaculties = Faculty::orderBy('name')->get();

        $tujuanOptions = [
            'rektor' => 'Rektor', 
            'dekan' => 'Dekan', 
            'kaprodi' => 'Ketua Program Studi (Kaprodi)',
            'dosen' => 'Dosen',
            'tenaga_pendidik' => 'Tenaga Kependidikan', 
            'dosen_tugas_khusus' => 'Dosen Tugas Khusus', 
        ];

        return view('surats.edit', compact('surat', 'allFaculties', 'tujuanOptions'));
    }

    /**
     * Memperbarui surat.
     */
    public function update(Request $request, $id)
    {
        $surat = KirimSurat::findOrFail($id);

        // Optional: Otorisasi
        if ($surat->user_id_1 !== Auth::id()) {
             abort(403, 'Akses Ditolak.');
        }
        
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => ['required', 'string', 
                         Rule::in(['rektor', 'dekan', 'kaprodi', 'dosen', 'tenaga_pendidik', 'dosen_tugas_khusus'])],
            'target_type' => ['required', 'string', Rule::in(['universitas', 'spesifik'])],
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ];

        // Validasi kondisional untuk target_faculty_id
        if ($request->input('target_type') === 'spesifik') {
            $validationRules['target_faculty_id'] = ['required', 'integer', Rule::exists('faculties', 'id')]; 
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
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
            
            $dataToUpdate['file_path'] = $filePath;
        }
        
        // Menangani tujuan_faculty_id
        $dataToUpdate['tujuan_faculty_id'] = ($validatedData['target_type'] === 'spesifik') 
                                             ? $validatedData['target_faculty_id'] ?? null 
                                             : null;

        $surat->update($dataToUpdate); 
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    /**
     * Menghapus surat.
     */
    public function destroy($id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        // Optional: Otorisasi (hanya pembuat surat atau Admin yang boleh menghapus)
        if ($surat->user_id_1 !== Auth::id() && Auth::user()->role_id !== 1) {
            abort(403, 'Akses Ditolak. Anda tidak berhak menghapus surat ini.');
        }

        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }
        $surat->delete(); 
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil dihapus.');
    }
}