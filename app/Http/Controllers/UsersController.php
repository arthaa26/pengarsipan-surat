<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KirimSurat;
use App\Models\Role;
use App\Models\Faculty; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\Str; // Tambahkan Str
use App\Http\Controllers\KirimSuratController; // Diperlukan untuk generateSuratCode

class UsersController extends Controller
{

    /**
     * Membangun query untuk mengambil surat masuk berdasarkan role dan fakultas user.
     */
    private function getSuratMasukQuery($user)
    {
        $userId = $user->id;
        $userFacultyId = $user->faculty_id;
        // Menggunakan Nullsafe pada relasi role
        $userRoleName = $user->role?->name ?? 'dosen'; 
        
        $roleTujuan = strtolower(str_replace(' ', '_', $userRoleName));
        
        $isUniversityLevel = in_array($roleTujuan, ['rektor', 'admin', 'dosen_tugas_khusus']);
        
        return KirimSurat::where(function ($query) use ($userId, $roleTujuan, $userFacultyId, $isUniversityLevel) {
            
            // Kriteria 1: Surat ditujukan secara PERSONAL (user_id_2 tidak NULL)
            $query->where('user_id_2', $userId)
                    
                    // Kriteria 2: Surat ditujukan secara ROLE/FAKULTAS (user_id_2 NULL)
                    ->orWhere(function ($q) use ($roleTujuan, $userFacultyId, $isUniversityLevel) {
                        
                        // Filter awal: Surat ditujukan ke role user ini
                        $q->whereNull('user_id_2')
                            ->where('tujuan', $roleTujuan); 

                        // Logika Filter Fakultas:
                        if (!$isUniversityLevel) {
                            $q->where(function($subQ) use ($userFacultyId) {
                                // A) Ditujukan spesifik ke Fakultas mereka (ID Fakultas match)
                                $subQ->where('tujuan_faculty_id', $userFacultyId)
                                            // B) ATAU Ditujukan ke SELURUH FAKULTAS (tujuan_faculty_id IS NULL)
                                            ->orWhereNull('tujuan_faculty_id');
                            });
                        }
                    });
        });
    }

    // --- LOGIKA DASHBOARD DAN DAFTAR SURAT ---
    public function index()
    {
        $user = Auth::user();
        if (!$user) { return redirect()->route('login'); }
        
        $user->load(['role', 'faculty']); 

        $userId = $user->id;
        
        $rawRoleName = $user->role?->name ?? 'N/A'; // Menggunakan Nullsafe
        $formattedRoleName = ucwords(str_replace('_', ' ', $rawRoleName));

        $suratMasukQuery = $this->getSuratMasukQuery($user); 
        $suratMasukCount = $suratMasukQuery->count();
        $suratMasuk = $suratMasukQuery->with('user1.faculty')->orderBy('created_at', 'desc')->limit(10)->get();

        $suratKeluarQuery = KirimSurat::where('user_id_1', $userId);
        $suratKeluarCount = $suratKeluarQuery->count();
        
        return view('user.dashboard', [ 
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $suratMasuk,
            'userRoleName' => $formattedRoleName, 
        ]);
    }
    
    public function daftarSuratMasuk()
    {
        $user = Auth::user();
        if (!$user) { return redirect()->route('login'); }
        $user->load(['role', 'faculty']); 

        $suratList = $this->getSuratMasukQuery($user)
                             ->with('user1.faculty')
                             ->orderBy('created_at', 'desc')
                             ->paginate(15);
        
        $rawRoleName = $user->role?->name ?? 'N/A'; // Menggunakan Nullsafe
        $formattedRoleName = ucwords(str_replace('_', ' ', $rawRoleName));

        return view('user.DaftarSurat.masuk', compact('suratList', 'formattedRoleName'));
    }
    
    public function daftarSuratKeluar()
    {
        $user = Auth::user();
        if (!$user) { return redirect()->route('login'); }
        $user->load(['role', 'faculty']);
        
        $userId = $user->id;
        $suratList = KirimSurat::where('user_id_1', $userId)
                                   ->with(['user2', 'user2.faculty', 'tujuanFaculty']) 
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(15);
                                   
        $rawRoleName = $user->role?->name ?? 'N/A'; // Menggunakan Nullsafe
        $formattedRoleName = ucwords(str_replace('_', ' ', $rawRoleName));

        return view('user.DaftarSurat.keluar', compact('suratList', 'formattedRoleName'));
    }


    /**
     * Menampilkan formulir Kirim Surat.
     */
    public function createSurat(Request $request) 
    {
        // 1. Ambil semua data Fakultas
        $allFaculties = Faculty::select('id', 'name', 'code')->get();

        // 2. Ambil semua data Pengguna (User) dengan relasi Role dan Faculty
        $allUsers = User::where('role_id', '!=', 1)
            ->with(['role', 'faculty'])
            ->select('id', 'name', 'username', 'role_id', 'faculty_id') 
            ->get()
            ->map(function ($user) {
                // Menggunakan Nullsafe Operator (?->)
                $roleName = $user->role?->name ?? 'N/A';
                $facultyCode = $user->faculty?->code ?? 'Pusat';
                
                // PERBAIKAN SINTAKS: Memisahkan operator ?? dari interpolasi string kompleks.
                $usernameDisplay = $user->username ?? 'N/A'; 
                
                // Format tampilan untuk Select2 / Dropdown Blade
                $user->display_text = "{$user->name} ({$usernameDisplay}) - [{$roleName}] ({$facultyCode})";
                
                return $user;
            });
        
        // 3. LOGIKA BALAS SURAT
        $replyToUserId = $request->query('reply_to_user_id');
        $preSelectedTarget = null;

        if ($replyToUserId) {
            // PERBAIKAN: Cast ke integer saat memfilter koleksi untuk konsistensi tipe.
            $targetUser = $allUsers->firstWhere('id', (int)$replyToUserId); 
            
            if ($targetUser) {
                $preSelectedTarget = [
                    'target_type' => 'user_spesifik',
                    'target_user_id' => (int)$replyToUserId,
                    'target_user_text' => $targetUser->display_text 
                ];
            }
        }

        // 4. GENERATE KODE SURAT
        $nextKode = 'S-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); 
        
        // Coba instansiasi KirimSuratController jika tersedia
        if (class_exists(KirimSuratController::class) && method_exists(KirimSuratController::class, 'generateSuratCode')) {
            $kirimSuratController = new KirimSuratController();
            $nextKode = $kirimSuratController->generateSuratCode();
        }

        // 5. Kirim data ke View Blade
        return view('user.KirimSurat.index', [
            'allFaculties' => $allFaculties, 
            'allUsers' => $allUsers, 
            'nextKode' => $nextKode,
            'preSelectedTarget' => $preSelectedTarget, 
        ]);
    }
    
    // --- LOGIKA BALAS SURAT (REPLY FORM) DARI KIRIMSURATCONTROLLER ---

    /**
     * Displays the form to reply to a specific letter.
     * @param KirimSurat $surat The original incoming letter instance.
     */
    public function replyForm(KirimSurat $surat)
    {
        // 1. Otorisasi: Pastikan user yang login adalah penerima (user_id_2)
        if ($surat->user_id_2 !== Auth::id()) {
            abort(403, 'Akses Ditolak. Anda tidak berhak membalas surat ini.');
        }

        // 2. PERBAIKAN KRUSIAL: Eager Load relasi user1 untuk mendapatkan nama pengirim
        $surat->load('user1'); 

        // 3. Persiapan data form (membutuhkan KirimSuratController)
        if (!class_exists(KirimSuratController::class) || !method_exists(KirimSuratController::class, 'generateSuratCode')) {
             return back()->with('error', 'Gagal memuat form balasan: KirimSuratController atau generateSuratCode tidak ditemukan.');
        }
        
        $kirimSuratController = new KirimSuratController(); 
        $nextKode = $kirimSuratController->generateSuratCode(); 

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
                               
        // 4. Setup Pre-selected Target (Pengirim asli)
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
        
        // 5. Return view
        return view('user.DaftarSurat.reply', compact('nextKode', 'allFaculties', 'tujuanOptions', 'allUsers', 'preSelectedTarget', 'surat')); 
    }

    /**
     * Stores the reply letter.
     * @param KirimSurat $surat The original incoming letter instance.
     */
    public function sendReply(Request $request, KirimSurat $surat)
    {
        // Panggil method store/sendReply dari KirimSuratController
        $kirimSuratController = new KirimSuratController();
        return $kirimSuratController->sendReply($request, $surat);
    }
    
    // --- LOGIKA API ---
    public function searchTargetUsers(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10; 
        
        $query = User::query()->select('id', 'name', 'faculty_id', 'role_id');
        
        if (Schema::hasColumn('users', 'username')) {
            $query->addSelect('username'); 
        }

        $query->with(['faculty:id,code', 'role:id,name']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                
                if (Schema::hasColumn('users', 'username')) {
                    $q->orWhere('username', 'like', '%' . $search . '%');
                }
            });
        }
        
        try {
            $users = $query->orderBy('name')
                             ->paginate($perPage);

            $formattedUsers = $users->map(function ($user) {
                $idNumberDisplay = isset($user->username) ? ' (' . $user->username . ')' : '';
                // Menggunakan Nullsafe Operator (?->)
                $facultyCode = $user->faculty?->code;
                $facultyDisplay = $facultyCode ? " ({$facultyCode})" : ''; 
                
                // Menggunakan Nullsafe Operator (?->)
                $roleName = $user->role?->name;
                $roleDisplay = $roleName ? ' [' . ucwords(str_replace('_', ' ', $roleName)) . ']' : '';
                
                return [
                    'id' => $user->id,
                    'text' => $user->name . $idNumberDisplay . $facultyDisplay . $roleDisplay, 
                ];
            });

            return response()->json([
                'results' => $formattedUsers,
                'pagination' => [
                    'more' => $users->hasMorePages()
                ],
                'total' => $users->total() 
            ]);

        } catch (\Exception $e) {
             Log::error("FATAL ERROR di searchTargetUsers: " . $e->getMessage() . " di baris " . $e->getLine());
             return response()->json(['results' => [], 'error' => 'Gagal memuat data pencarian. Pesan: ' . $e->getMessage()], 500);
        }
    }


    // --- LOGIKA PROFILE DAN DELETE ---
    public function editProfile()
    {
        $user = Auth::user();
        if ($user) {
             $user->load(['role', 'faculty']);
        }
        return view('user.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], 
            'no_hp' => ['nullable', 'string', 'max:15'], 
            'password_new' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], 
        ]);

        // 2. Simpan Foto
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_url) {
                $oldPath = str_replace(Storage::url('/'), '', $user->profile_photo_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_url = Storage::url($path);
        }

        // 3. Update Data Teks
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp; 

        // 4. Update Password
        if ($request->filled('password_new')) {
            $user->password = Hash::make($request->password_new);
        }
        
        $user->save();

        return redirect()->route('user.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    
    public function daftarSurat()
    {
        return redirect()->route('user.daftar_surat.masuk');
    }


    public function viewSurat(KirimSurat $surat)
    {
        // PERBAIKAN: Mengganti 'surat.show' dengan path view yang benar
        return view('user.DaftarSurat.show_detail', compact('surat'));
    }

    public function viewFileSurat(KirimSurat $surat)
    {
        if (Storage::disk('public')->exists($surat->file_path)) {
            return Storage::disk('public')->response($surat->file_path);
        }
        abort(404, 'File lampiran tidak ditemukan.');
    }
    
    public function downloadSurat(KirimSurat $surat)
    {
        if (Storage::disk('public')->exists($surat->file_path)) {
            $fileName = $surat->kode_surat . '_' . basename($surat->file_path);
            return Storage::disk('public')->download($surat->file_path, $fileName);
        }
        abort(404, 'File lampiran tidak ditemukan.');
    }

    public function deleteSurat(KirimSurat $surat)
    {
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }
        $surat->delete();
        
        $redirectRoute = ($surat->user_id_1 == Auth::id()) 
                            ? 'user.daftar_surat.keluar' 
                            : 'user.daftar_surat.masuk';
                            
        return redirect()->route($redirectRoute)->with('success', 'Surat berhasil dihapus.');
    }

    // Metode CRUD User Placeholder
    public function create() { return view('users.create'); }
    public function store(Request $request) { /* ... */ }
    public function show($id) { $user = User::findOrFail($id); return view('users.show', compact('user')); }
    public function edit($id) { $user = User::findOrFail($id); return view('users.edit', compact('user')); }
    public function update(Request $request, $id) { /* ... */ }
    public function destroy($id) { User::destroy($id); return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.'); }
}