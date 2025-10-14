<?php
namespace App\Http\Controllers;
use App\Models\KirimSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 

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
        return view('surats.create', compact('nextKode'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus', 
            'file_surat' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // Maks 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }

        $validatedData['user_id_1'] = Auth::id(); 
        $validatedData['kode_surat'] = $this->generateSuratCode();
        $validatedData['file_path'] = $filePath; 
        unset($validatedData['file_surat']); 
        KirimSurat::create($validatedData); 
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil dikirim dengan kode: ' . $validatedData['kode_surat']);
    }

    public function show($id)
    {
        $surat = KirimSurat::findOrFail($id);
        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = KirimSurat::findOrFail($id);
        return view('surats.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = KirimSurat::findOrFail($id);
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'tujuan' => 'required|string|in:rektor,dekan,dosen,tenaga_pendidik,dosen_tugas_khusus',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
        ];

        $validatedData = $request->validate($validationRules);
        if ($request->hasFile('file_surat')) {
            // Hapus file lama jika ada
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }

            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
            
            $validatedData['file_path'] = $filePath;
        }

        unset($validatedData['file_surat']); 
        $surat->update($validatedData); 
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
