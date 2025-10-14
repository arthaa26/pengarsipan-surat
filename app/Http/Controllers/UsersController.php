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
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    private function getSuratMasukQuery($user)
    {
        $userId = $user->id;
        $userFacultyId = $user->faculty_id;
        // Gunakan nilai default yang aman jika role atau name null
        $userRoleName = $user->role->name ?? 'dosen'; 
        
        // Konversi nama role ke format tujuan DB (snake_case)
        $roleTujuan = strtolower(str_replace(' ', '_', $userRoleName));
        
        // Role Rektor dan Admin biasanya melihat semua surat yang ditujukan ke role mereka
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
                      // Jika pengguna BUKAN level Universitas (e.g., Dekan, Dosen, Kaprodi)
                      if (!$isUniversityLevel) {
                          // Pengguna level Fakultas harus melihat surat yang:
                          $q->where(function($subQ) use ($userFacultyId) {
                              // A) Ditujukan spesifik ke Fakultas mereka (ID Fakultas match)
                              $subQ->where('tujuan_faculty_id', $userFacultyId)
                                   // B) ATAU Ditujukan ke SELURUH FAKULTAS (tujuan_faculty_id IS NULL)
                                   ->orWhereNull('tujuan_faculty_id');
                          });
                      }
                      // Jika pengguna level Universitas (Rektor, Admin), filter fakultas tidak diterapkan,
                      // sehingga mereka akan melihat SEMUA surat universal yang ditujukan ke role mereka.
                  });
        });
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        $user->load(['role', 'faculty']); 

        $userId = $user->id;
        
        $rawRoleName = $user->role->name ?? 'N/A';
        $formattedRoleName = ucwords(str_replace('_', ' ', $rawRoleName));

        $suratMasukQuery = $this->getSuratMasukQuery($user); 
        $suratMasukCount = $suratMasukQuery->count();
        // Memuat relasi untuk data di dashboard
        $suratMasuk = $suratMasukQuery->with('user1.faculty')->orderBy('created_at', 'desc')->limit(10)->get();

        $suratKeluarQuery = KirimSurat::where('user_id_1', $userId);
        $suratKeluarCount = $suratKeluarQuery->count();
        
        return view('user.dashboard', [ 
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $suratMasuk,
            'userRoleName' => $formattedRoleName, // Kirim role yang diformat
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
        
        $rawRoleName = $user->role->name ?? 'N/A';
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
                                 ->with(['user2', 'user2.faculty', 'tujuanFaculty']) // Tambahkan relasi tujuanFaculty untuk melihat tujuan
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(15);
                                 
        $rawRoleName = $user->role->name ?? 'N/A';
        $formattedRoleName = ucwords(str_replace('_', ' ', $rawRoleName));

        return view('user.DaftarSurat.keluar', compact('suratList', 'formattedRoleName'));
    }


    public function createSurat()
    {
        $allFaculties = collect([]); 
        try {
            $allFaculties = Faculty::all();
        } catch (\Exception $e) {
            \Log::error("Failed to load faculties for Kirim Surat: " . $e->getMessage());
        }
        return view('user.kirimsurat.index', compact('allFaculties'));
    }
    
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
        return view('surat.view', compact('surat'));
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

    public function create() { return view('users.create'); }
    public function store(Request $request) { /* ... */ }
    public function show($id) { $user = User::findOrFail($id); return view('users.show', compact('user')); }
    public function edit($id) { $user = User::findOrFail($id); return view('users.edit', compact('user')); }
    public function update(Request $request, $id) { /* ... */ }
    public function destroy($id) { User::destroy($id); return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.'); }
}
