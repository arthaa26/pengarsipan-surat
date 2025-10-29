<?php

namespace App\Http\Controllers;

use App\Models\KirimSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Faculty; 
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 

class KirimSuratController extends Controller
{

    /**
     * Converts month number to Roman numeral.
     */
    private function convertToRoman(int $number): string
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$number] ?? 'X'; 
    }

    // --- LOGIKA UNTOK CRUD UTAMA ---

    public function index()
    {
        $kirimSurat = KirimSurat::where('user_id_1', Auth::id())
                               ->select('kode_surat', 'title', 'created_at', 'tujuan', 'tujuan_faculty_id')
                               ->groupBy('kode_surat', 'title', 'created_at', 'tujuan', 'tujuan_faculty_id')
                               ->orderBy('created_at', 'desc')
                               ->paginate(15);
                                
        return view('surats.index', compact('kirimSurat'));
    }
    public function create(Request $request) 
    {

        $nextKode = null; 
        $allFaculties = Faculty::orderBy('name')->get(); 
        
        $allUsers = User::where('role_id', '!=', 1) 
                             ->orderBy('name')
                             ->with('role', 'faculty')
                             ->get()
                             ->map(function ($user) {
                                $roleName = $user->role?->name ?? 'N/A';
                                $facultyCode = $user->faculty?->code ?? 'Pusat';
                                $usernameDisplay = $user->username ?? 'N/A'; 
                                $user->display_text = "{$user->name} ({$usernameDisplay}) - {$roleName} {$facultyCode}";
                                return $user;
                             });
        
        $tujuanOptions = [ /* ... */ ]; 

        // --- LOGIKA BALAS SURAT ---
        $replyToUserId = $request->query('reply_to_user_id');
        $preSelectedTarget = null;
        
        if ($replyToUserId) {
            $targetUser = $allUsers->firstWhere('id', (int)$replyToUserId); 
            
            if ($targetUser) {
                $preSelectedTarget = [
                    'target_type' => 'user_spesifik',
                    'target_user_id' => $targetUser->id,
                    'target_user_text' => $targetUser->display_text 
                ];
            }
        }
        // --- AKHIR LOGIKA BALAS SURAT ---

        return view('user.KirimSurat.index', compact('nextKode', 'allFaculties', 'tujuanOptions', 'allUsers', 'preSelectedTarget')); 
    }


    public function store(Request $request)
    {
        $validationRules = [
            'kode_surat' => 'required|string|max:255|unique:kirim_surat,kode_surat',
            
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_type' => ['required', 'string', Rule::in(['universitas', 'spesifik', 'user_spesifik'])], 
            'file_surat' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            
            'target_faculty_id' => ['required_if:target_type,spesifik', 'integer', Rule::exists('faculties', 'id')],
            'target_role_id' => ['required_if:target_type,spesifik', 'integer', Rule::exists('roles', 'id')], 
            'target_user_id' => ['required_if:target_type,user_spesifik', 'integer', Rule::exists('users', 'id')],
        ];

        $validatedData = $request->validate($validationRules);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }

        $tujuanRole = match ($validatedData['target_type']) {
            'universitas' => 'rektor', 
            'spesifik' => 'fakultas',      
            'user_spesifik' => 'personal',
            default => 'rektor',
        };
        
        $kodeSurat = $validatedData['kode_surat'];
        
        $baseData = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
            'file_path' => $filePath,
            'user_id_1' => Auth::id(), 
            'tujuan' => $tujuanRole,
            'kode_surat' => $kodeSurat,
        ];

        $recipientIds = [];
        $targetFacultyId = null; 

        switch ($validatedData['target_type']) {
            case 'universitas':
                $recipientIds = User::whereNotNull('faculty_id')->pluck('id')->all();
                break;

            case 'spesifik':
                $targetFacultyId = $validatedData['target_faculty_id'];
                $targetRoleId = $validatedData['target_role_id']; 
                
                $recipientIds = User::where('faculty_id', $targetFacultyId)
                                         ->where('role_id', $targetRoleId)
                                         ->pluck('id')
                                         ->all();
                break;
            
            case 'user_spesifik':
                 $recipientIds[] = $validatedData['target_user_id'];
                 break;
        }

        if (empty($recipientIds)) {
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Tidak ada pengguna tujuan yang ditemukan. Surat gagal dikirim.');
        }

        $recordsToInsert = [];
        $now = now();

        foreach ($recipientIds as $recipientId) {
            $recordsToInsert[] = array_merge($baseData, [
                'user_id_2' => $recipientId, 
                'tujuan_faculty_id' => $targetFacultyId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
        try {
            KirimSurat::insert($recordsToInsert);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            Log::error("Unique constraint violation during bulk insert: " . $e->getMessage());
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Gagal mengirim surat: Kode surat sudah ada. Mohon gunakan kode surat yang unik. (' . $kodeSurat . ')');
        } catch (\Exception $e) {
            Log::error("Gagal melakukan bulk insert: " . $e->getMessage());
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Gagal mengirim surat karena masalah database umum. Mohon hubungi administrator.');
        } 

        return redirect()->route('user.kirim_surat.index')
                             ->with('success', 'Surat berhasil dikirim ke ' . count($recipientIds) . ' penerima. Kode surat: ' . $kodeSurat);
    }

    public function show($id)
    {
        $surat = KirimSurat::with(['user1.faculty', 'tujuanFaculty', 'user2.faculty'])
                           ->findOrFail($id);
        
        if ($surat->user_id_1 !== Auth::id() && $surat->user_id_2 !== Auth::id() && Auth::user()->role_id !== 1) { 
             abort(403, 'Akses Ditolak. Anda tidak berhak melihat surat ini.');
        }

        return view('surats.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        if ($surat->user_id_1 !== Auth::id()) {
             abort(403, 'Akses Ditolak. Anda tidak berhak mengedit surat ini.');
        }
        
        $allFaculties = Faculty::orderBy('name')->get();
        $tujuanOptions = [ /* ... */ ];

        $target_type = 'universitas';
        if ($surat->tujuan === 'fakultas') {
            $target_type = 'spesifik';
        } elseif ($surat->tujuan === 'personal') {
            $target_type = 'user_spesifik';
        }

        return view('surats.edit', compact('surat', 'allFaculties', 'tujuanOptions', 'target_type'));
    }

    public function update(Request $request, $id)
    {
        $surat = KirimSurat::findOrFail($id);

        if ($surat->user_id_1 !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }
        
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:10240', 
        ];

        $validatedData = $request->validate($validationRules);
        
        $dataToUpdate = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
        ];

        if ($request->hasFile('file_surat')) {
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }

            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
            
            $dataToUpdate['file_path'] = $filePath;
        }
        
        KirimSurat::where('kode_surat', $surat->kode_surat)
                      ->where('user_id_1', Auth::id())
                      ->update($dataToUpdate); 
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil diperbarui untuk semua penerima.');
    }


    public function destroy($id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        if ($surat->user_id_1 !== Auth::id() && Auth::user()->role_id !== 1) {
            abort(403, 'Akses Ditolak. Anda tidak berhak menghapus surat ini.');
        }

        $allSuratRecords = KirimSurat::where('kode_surat', $surat->kode_surat)
                                             ->where('user_id_1', Auth::id()) // Security
                                             ->get();

        if ($allSuratRecords->isEmpty()) {
             return redirect()->route('user.kirim_surat.index')->with('error', 'Surat tidak ditemukan.');
        }

        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }
        
        KirimSurat::whereIn('id', $allSuratRecords->pluck('id'))->delete();
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil dihapus dari semua penerima.');
    }

    // --- LOGIKA KHUSUS BALAS SURAT (REPLY) ---
    public function replyForm(KirimSurat $surat)
    {
        if ($surat->user_id_2 !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak berhak membalas surat ini.');
        }

        $surat->load('user1'); 

        $nextKode = null;
        $allFaculties = Faculty::orderBy('name')->get(); 
        
        $allUsers = User::where('role_id', '!=', 1) 
                             ->orderBy('name')
                             ->with('role', 'faculty')
                             ->get()
                             ->map(function ($user) {
                                $roleName = $user->role?->name ?? 'N/A';
                                $facultyCode = $user->faculty?->code ?? 'Pusat';
                                $usernameDisplay = $user->username ?? 'N/A'; 
                                $user->display_text = "{$user->name} ({$usernameDisplay}) - {$roleName} {$facultyCode}";
                                return $user;
                             });

        $targetUser = $allUsers->firstWhere('id', $surat->user_id_1); 
        $preSelectedTarget = null;
        
        if ($targetUser) {
            $preSelectedTarget = [
                'target_type' => 'user_spesifik',
                'target_user_id' => $targetUser->id,
                'target_user_text' => $targetUser->display_text,
                'reply_title' => 'Re: ' . Str::limit($surat->title, 50), 
                'reply_to_surat_id' => $surat->id 
            ];
        } else {
             return back()->with('error', 'Gagal memuat form balasan: Pengirim asli tidak ditemukan.');
        }
        
        $tujuanOptions = [ /* ... */ ]; 

        return view('user.DaftarSurat.reply', compact('nextKode', 'allFaculties', 'tujuanOptions', 'allUsers', 'preSelectedTarget', 'surat')); 
    }
    public function sendReply(Request $request, KirimSurat $surat)
    {
        if ($surat->user_id_2 !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak berhak membalas surat ini.');
        }

        $validationRules = [
            'kode_surat' => 'required|string|max:255|unique:kirim_surat,kode_surat',
            
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_user_id' => ['required', 'integer', Rule::exists('users', 'id')], 
        ];

        $validatedData = $request->validate($validationRules);

        if ((int) $validatedData['target_user_id'] !== $surat->user_id_1) {
             return back()->withInput()->with('error', 'Gagal memproses balasan: Tujuan balasan tidak sesuai dengan pengirim asli.');
        }

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }
        
        $baseData = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
            'file_path' => $filePath, 
            'user_id_1' => Auth::id(), 
            'user_id_2' => $surat->user_id_1, 
            'kode_surat' => $validatedData['kode_surat'], 
            'tujuan' => 'personal',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        try {
            KirimSurat::create($baseData);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            Log::error("Unique constraint violation during reply insert: " . $e->getMessage());
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Gagal mengirim balasan: Kode surat sudah ada. Mohon gunakan kode surat yang unik. (' . $validatedData['kode_surat'] . ')');
        } catch (\Exception $e) {
            Log::error("Gagal mengirim balasan: " . $e->getMessage());
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Gagal mengirim balasan karena masalah database. Mohon coba lagi.');
        }
        
        $recipientName = User::find($surat->user_id_1)->name ?? 'penerima';

        return redirect()->route('user.daftar_surat.masuk')
                             ->with('success', "Balasan untuk surat '{$surat->title}' berhasil dikirim ke {$recipientName}. Kode surat: {$baseData['kode_surat']}");
    }
}
