<?php

namespace App\Http\Controllers;

use App\Models\Kirim_surat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage; 

class KirimSuratController extends Controller
{
    
    /**
     * Helper to convert an integer month to Roman numeral
     * @param int $number
     * @return string
     */
    private function convertToRoman(int $number): string
    {
        // Simple array for months 1-12
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$number] ?? 'X'; 
    }

    /**
     * Generates the next sequential surat code (e.g., UM/001/X/2025)
     * @return string
     */
    private function generateSuratCode(): string
    {
        $prefix = 'UM'; 
        $currentYear = Carbon::now()->year;
        $currentMonthRoman = $this->convertToRoman(Carbon::now()->month);

        $lastSurat = Kirim_surat::whereYear('created_at', $currentYear)
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


    public function index()
    {
        $kirimSurat = Kirim_surat::all();
        return view('surats.index', compact('kirimSurat'));
    }

    public function create()
    {
        $nextKode = $this->generateSuratCode(); 
        return view('surats.create', compact('nextKode'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus', // Validate required radio button
            'file_surat' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }

        $validatedData['kode_surat'] = $this->generateSuratCode();
        $validatedData['file_path'] = $filePath; 
        
        unset($validatedData['file_surat']); 

        Kirim_surat::create($validatedData); 
        
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
        
        // 1. Validation 
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus',
            // File is optional on update, and we include 'nullable' for file input
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ];

        $validatedData = $request->validate($validationRules);

        // 2. Handle File Update (if a new file is uploaded)
        if ($request->hasFile('file_surat')) {
            // Delete old file if it exists
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }

            // Upload new file
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
            
            $validatedData['file_path'] = $filePath;
        }

        // 3. Update the record
        // We ensure kode_surat is never overwritten here, even if it were passed in the request.
        unset($validatedData['file_surat']); 
        $surat->update($validatedData); 

        return redirect()->route('kirim-surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $surat = Kirim_surat::findOrFail($id);

        // Delete the associated file from storage before deleting the record
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }

        $surat->delete(); 
        
        return redirect()->route('kirim-surat.index')->with('success', 'Surat berhasil dihapus.');
    }
}