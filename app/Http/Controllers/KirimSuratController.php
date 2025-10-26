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

    /**
     * Generates a new unique letter code in the format UM/001/ROMAN_MONTH/YEAR.
     */
          public function generateSuratCode(): string     {
        $prefix = 'UM';
        $currentYear = Carbon::now()->year;
        $currentMonthRoman = $this->convertToRoman(Carbon::now()->month);
        
        // Find the last letter code created this year
        $lastSurat = KirimSurat::whereYear('created_at', $currentYear)
                               ->orderBy('id', 'desc')
                               ->first();

        $newSequence = 1;

        if ($lastSurat && $lastSurat->kode_surat) {
            $parts = explode('/', $lastSurat->kode_surat);
            
            if (count($parts) >= 4) {
                // Get the sequence number (second part) and convert to integer
                $lastSequence = (int) ltrim($parts[1], '0'); 
                $newSequence = $lastSequence + 1;
            }
        }

        $formattedSequence = str_pad($newSequence, 3, '0', STR_PAD_LEFT);

        return "{$prefix}/{$formattedSequence}/{$currentMonthRoman}/{$currentYear}";
    }

    // --- LOGIKA CRUD UTAMA ---

    /**
     * Displays a list of sent letters grouped by kode_surat.
     */
    public function index()
    {
        // Display one row for each unique shipment (based on kode_surat)
        $kirimSurat = KirimSurat::where('user_id_1', Auth::id())
                               ->select('kode_surat', 'title', 'created_at', 'tujuan', 'tujuan_faculty_id')
                               ->groupBy('kode_surat', 'title', 'created_at', 'tujuan', 'tujuan_faculty_id')
                               ->orderBy('created_at', 'desc')
                               ->paginate(15);
                               
        return view('surats.index', compact('kirimSurat'));
    }

    /**
     * Displays the form for creating a new letter, supporting the Reply feature.
     */
    public function create(Request $request) 
    {
        $nextKode = $this->generateSuratCode(); 
        $allFaculties = Faculty::orderBy('name')->get(); 
        
        // Ambil data user dari database dan format untuk dropdown
        $allUsers = User::where('role_id', '!=', 1) 
                              ->orderBy('name')
                              ->with('role', 'faculty')
                              ->get()
                              ->map(function ($user) {
                                   // PERBAIKAN: Menggunakan Nullsafe Operator (?->) untuk mencegah NullPointer jika relasi null
                                   $roleName = $user->role?->name ?? 'N/A';
                                   $facultyCode = $user->faculty?->code ?? 'Pusat';
                                   
                                   // Menggunakan Null Coalescing Operator untuk username
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
                // If the target user is found, set default target to 'user_spesifik'
                $preSelectedTarget = [
                    'target_type' => 'user_spesifik',
                    'target_user_id' => $targetUser->id,
                    'target_user_text' => $targetUser->display_text 
                ];
            }
        }
        // --- AKHIR LOGIKA BALAS SURAT ---


        // Teruskan data user dan preSelectedTarget ke view
        // [PERBAIKAN] Menggunakan user.KirimSurat.index
        return view('user.KirimSurat.index', compact('nextKode', 'allFaculties', 'tujuanOptions', 'allUsers', 'preSelectedTarget')); 
    }

    /**
     * Stores the newly created letter.
     */
    public function store(Request $request)
    {
        // 1. VALIDATION INPUT
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_type' => ['required', 'string', Rule::in(['universitas', 'spesifik', 'user_spesifik'])], 
            'file_surat' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            
            'target_faculty_id' => ['required_if:target_type,spesifik', 'integer', Rule::exists('faculties', 'id')],
            'target_role_id' => ['required_if:target_type,spesifik', 'integer', Rule::exists('roles', 'id')], 
            'target_user_id' => ['required_if:target_type,user_spesifik', 'integer', Rule::exists('users', 'id')],
        ];

        $validatedData = $request->validate($validationRules);

        // 2. FILE UPLOAD PROCESS (Only once)
        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }

        // 3. DETERMINE FINAL DESTINATION TYPE (Role/Level)
        $tujuanRole = match ($validatedData['target_type']) {
            'universitas' => 'rektor', 
            'spesifik' => 'fakultas',       
            'user_spesifik' => 'personal',
            default => 'rektor',
        };
        
        // 4. PREPARE BASE LETTER DATA & GENERATE SINGLE KODE SURAT
        $kodeSurat = $this->generateSuratCode(); // Panggil sekali di luar loop
        
        $baseData = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
            'file_path' => $filePath,
            'user_id_1' => Auth::id(), // Sender
            'tujuan' => $tujuanRole, // Storing the validated destination type
            'kode_surat' => $kodeSurat, // Tetapkan kode surat di data dasar
        ];

        // 5. DETERMINE ALL RECIPIENTS (Can be one or many)
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

        // 6. CREATE LETTER RECORD FOR EACH RECIPIENT
        if (empty($recipientIds)) {
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Tidak ada pengguna tujuan yang ditemukan. Surat gagal dikirim.');
        }

        $recordsToInsert = [];
        $now = now();

        foreach ($recipientIds as $recipientId) {
            
            // HAPUS PANGGILAN generateSuratCode() dari dalam loop.
            // Gunakan $baseData['kode_surat'] yang sudah dibuat di langkah 4.

            $recordsToInsert[] = array_merge($baseData, [
                // Kode surat sudah di baseData, tidak perlu ditimpa kecuali jika Anda ingin kode unik per penerima.
                // Dalam kasus ini, kita asumsikan satu kode per pengiriman.
                'user_id_2' => $recipientId, // Specific recipient
                'tujuan_faculty_id' => $targetFacultyId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
        // Use bulk insert
        try {
            KirimSurat::insert($recordsToInsert);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            Log::error("Unique constraint violation during bulk insert: " . $e->getMessage());
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Gagal mengirim surat: Terdeteksi masalah unikitas kode surat. Mohon coba lagi. (' . $kodeSurat . ')');
        } catch (\Exception $e) {
            // Ini seharusnya sekitar BARIS 94-96 di file Anda
            Log::error("Gagal melakukan bulk insert: " . $e->getMessage());
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Gagal mengirim surat karena masalah database umum. Mohon hubungi administrator.');
        } 
        // BARIS 98 seharusnya dimulai di sini. Pastikan tidak ada karakter sisa sebelum return.

        return redirect()->route('user.kirim_surat.index')
                             ->with('success', 'Surat berhasil dikirim ke ' . count($recipientIds) . ' penerima. Kode surat: ' . $kodeSurat);
    }

    /**
     * Displays letter details.
     */
    public function show($id)
    {
        $surat = KirimSurat::with(['user1.faculty', 'tujuanFaculty', 'user2.faculty'])
                           ->findOrFail($id);
        
        if ($surat->user_id_1 !== Auth::id() && $surat->user_id_2 !== Auth::id() && Auth::user()->role_id !== 1) { 
             abort(403, 'Akses Ditolak. Anda tidak berhak melihat surat ini.');
        }

        return view('surats.show', compact('surat'));
    }

    /**
     * Displays the form for editing a letter.
     */
    public function edit($id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        if ($surat->user_id_1 !== Auth::id()) {
             abort(403, 'Akses Ditolak. Anda tidak berhak mengedit surat ini.');
        }
        
        $allFaculties = Faculty::orderBy('name')->get();
        $tujuanOptions = [ /* ... */ ];

        // Logic to determine target_type in the edit form
        $target_type = 'universitas';
        if ($surat->tujuan === 'fakultas') {
            $target_type = 'spesifik';
        } elseif ($surat->tujuan === 'personal') {
            $target_type = 'user_spesifik';
        }

        return view('surats.edit', compact('surat', 'allFaculties', 'tujuanOptions', 'target_type'));
    }

    /**
     * Updates the letter.
     */
    public function update(Request $request, $id)
    {
        // 1. Find the original record for authorization and getting kode_surat
        $surat = KirimSurat::findOrFail($id);

        if ($surat->user_id_1 !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }
        
        // 2. Validation (ONLY title, content, and file)
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:10240', 
        ];

        $validatedData = $request->validate($validationRules);
        
        // 3. Build update data
        $dataToUpdate = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
        ];

        // 4. File Upload Logic (If there is a new file)
        if ($request->hasFile('file_surat')) {
            // Delete old file (only once)
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }

            // Save new file
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
            
            $dataToUpdate['file_path'] = $filePath;
        }
        
        // 5. MAIN LOGIC: Update all records with the same kode_surat
        KirimSurat::where('kode_surat', $surat->kode_surat)
                     ->where('user_id_1', Auth::id()) // Additional security
                     ->update($dataToUpdate); 
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil diperbarui untuk semua penerima.');
    }

    /**
     * Deletes the letter.
     */
    public function destroy($id)
    {
        $surat = KirimSurat::findOrFail($id);
        
        // Authorization (only sender or Admin can delete)
        if ($surat->user_id_1 !== Auth::id() && Auth::user()->role_id !== 1) {
            abort(403, 'Akses Ditolak. Anda tidak berhak menghapus surat ini.');
        }

        // Get all related records BEFORE deleting the file
        $allSuratRecords = KirimSurat::where('kode_surat', $surat->kode_surat)
                                             ->where('user_id_1', Auth::id()) // Security
                                             ->get();

        if ($allSuratRecords->isEmpty()) {
             return redirect()->route('user.kirim_surat.index')->with('error', 'Surat tidak ditemukan.');
        }

        // Delete physical file (only once)
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }
        
        // Delete all records from the database
        KirimSurat::whereIn('id', $allSuratRecords->pluck('id'))->delete();
        
        return redirect()->route('user.kirim_surat.index')->with('success', 'Surat berhasil dihapus dari semua penerima.');
    }

    // --- LOGIKA KHUSUS BALAS SURAT (REPLY) ---

    /**
     * Displays the form to reply to a specific letter (using the route 'surat.reply').
     */
    public function replyForm(KirimSurat $surat)
    {
        // 1. Authorization: Ensure current user is the intended recipient (user_id_2)
        if ($surat->user_id_2 !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak berhak membalas surat ini.');
        }

        // PERBAIKAN 1: Memuat (eager load) relasi user1 untuk mencegah N/A di Blade
        $surat->load('user1'); 

        // 2. Prepare Form Data (Same dependencies as create)
        $nextKode = $this->generateSuratCode();
        $allFaculties = Faculty::orderBy('name')->get(); 
        
        // Ambil data user dari database dan format untuk dropdown
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

        // 3. Setup Pre-selected Target (The original sender, user_id_1)
        $targetUser = $allUsers->firstWhere('id', $surat->user_id_1); 
        $preSelectedTarget = null;
        
        if ($targetUser) {
            $preSelectedTarget = [
                'target_type' => 'user_spesifik',
                'target_user_id' => $targetUser->id,
                'target_user_text' => $targetUser->display_text,
                'reply_title' => 'Re: ' . Str::limit($surat->title, 50), // Suggest a reply subject
                'reply_to_surat_id' => $surat->id // Pass original surat ID
            ];
        } else {
             // Fallback if the original sender is somehow missing (e.g., deleted user)
             return back()->with('error', 'Gagal memuat form balasan: Pengirim asli tidak ditemukan.');
        }
        
        $tujuanOptions = [ /* ... */ ]; 

        // 🛑 PERBAIKAN KRUSIAL 2: Tambahkan '$surat' ke compact() agar tersedia di Blade
        return view('user.DaftarSurat.reply', compact('nextKode', 'allFaculties', 'tujuanOptions', 'allUsers', 'preSelectedTarget', 'surat')); 
    }

    /**
     * Stores the reply letter (using the route 'surat.reply.send').
     */
    public function sendReply(Request $request, KirimSurat $surat)
    {
        // 1. Authorization (Ensure the current sender is the one who received the original surat)
        if ($surat->user_id_2 !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak berhak membalas surat ini.');
        }

        // 2. Validation Input (file_surat sudah diubah menjadi nullable di Blade, kita ubah di sini juga)
        $validationRules = [
            'title' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_user_id' => ['required', 'integer', Rule::exists('users', 'id')], // Target must be the original sender
            // 🛑 PERBAIKAN 3: Ubah 'required' menjadi 'nullable'
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB
        ];

        $validatedData = $request->validate($validationRules);

        // Security check: Verify the target user ID matches the original sender ID
        if ((int) $validatedData['target_user_id'] !== $surat->user_id_1) {
             return back()->withInput()->with('error', 'Gagal memproses balasan: Tujuan balasan tidak sesuai dengan pengirim asli.');
        }

        // 3. FILE UPLOAD PROCESS (Only once)
        $filePath = null;
        // Logika upload sudah menangani jika file_surat null
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_keluar', $fileName, 'public'); 
        }
        
        // 4. PREPARE BASE LETTER DATA
        $baseData = [
            'title' => $validatedData['title'],
            'isi' => $validatedData['isi'],
            'file_path' => $filePath, // BISA NULL
            'user_id_1' => Auth::id(), // Sender is the current user
            'user_id_2' => $surat->user_id_1, // Recipient is the original sender
            'kode_surat' => $this->generateSuratCode(), 
            'tujuan' => 'personal',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 5. CREATE LETTER RECORD
        try {
            KirimSurat::create($baseData);
        } catch (\Exception $e) {
            Log::error("Gagal mengirim balasan: " . $e->getMessage());
            if ($filePath) { Storage::disk('public')->delete($filePath); }
            return back()->withInput()->with('error', 'Gagal mengirim balasan karena masalah database. Mohon coba lagi.');
        }
        
        // 6. Redirect with Success Message
        $recipientName = User::find($surat->user_id_1)->name ?? 'penerima';

        return redirect()->route('user.daftar_surat.masuk')
                             ->with('success', "Balasan untuk surat '{$surat->title}' berhasil dikirim ke {$recipientName}.");
    }
}