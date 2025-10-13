<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KirimSurat;
use App\Models\Role; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    // --- UTILITY: Mendapatkan Query Surat Masuk untuk User saat ini ---
    private function getSuratMasukQuery()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Ambil Nama Role untuk filter Jabatan/Tujuan
        $userRole = Role::find($user->role_id);
        $userRoleName = $userRole->name ?? 'dosen'; 
        $roleTujuan = strtolower(str_replace(' ', '_', $userRoleName));

        return KirimSurat::where(function ($query) use ($userId, $roleTujuan) {
            // KONDISI 1 (Pribadi): Surat ditujukan LANGSUNG ke ID pengguna
            $query->where('user_id_2', $userId)
                  
                  // KONDISI 2 (Jabatan/Grup): Surat ditujukan ke JABATAN pengguna
                  ->orWhere(function ($q) use ($roleTujuan) {
                      $q->whereNull('user_id_2')
                        ->where('tujuan', $roleTujuan);
                  });
        });
    }

    // ... (Metode index(), daftarSuratMasuk(), daftarSuratKeluar() tidak berubah) ...
    public function index()
    {
        $userId = Auth::id();
        $suratMasukQuery = $this->getSuratMasukQuery();
        $suratMasukCount = $suratMasukQuery->count();
        $suratMasuk = $suratMasukQuery->orderBy('created_at', 'desc')->limit(10)->get();

        $suratKeluarQuery = KirimSurat::where('user_id_1', $userId);
        $suratKeluarCount = $suratKeluarQuery->count();
        
        return view('user.dashboard', [ 
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $suratMasuk,
        ]);
    }
    public function daftarSuratMasuk()
    {
        $suratList = $this->getSuratMasukQuery()
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        return view('user.DaftarSurat.masuk', compact('suratList'));
    }
    public function daftarSuratKeluar()
    {
        $userId = Auth::id();
        $suratList = KirimSurat::where('user_id_1', $userId)
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);
        return view('user.DaftarSurat.keluar', compact('suratList'));
    }

    // --- METODE PROFIL (Diperbaiki) ---

    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi utnuk Input (Ditambahkan validasi no_hp dan profile_photo)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], 
            'no_hp' => ['nullable', 'string', 'max:15'], // Validasi No. HP
            'password_new' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Validasi Foto
        ]);

        // 2. Simpan File Foto Baru (Kalo ada)
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_url) {
                // Asumsi: URL foto profil disimpan relatif ke storage/app/public
                $oldPath = str_replace(Storage::url('/'), '', $user->profile_photo_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            // Untuk menyimpan file baru dan dapatkan path-nya
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            
            // untuk menyimpan profil ke database/storage
            $user->profile_photo_url = Storage::url($path);
        }

        // 3. Controller untuk Update Data Teks
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp; // Menyimpan Nomor HP
        

        // 4. Comtroller untuk Update Password
        if ($request->filled('password_new')) {
            $user->password = Hash::make($request->password_new);
        }
        
        $user->save();

        return redirect()->route('user.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }


    // ... (Metode untuk daftarSurat(), createSurat(), viewSurat(), viewFileSurat(), downloadSurat(), deleteSurat(), dan CRUD USERS lainnya tidak berubah) ...
    public function daftarSurat()
    {
        return redirect()->route('user.daftar_surat.masuk');
    }

    public function createSurat()
    {
        return view('user.kirimsurat.index');
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
            return Storage::disk('public')->download($surat->file_path);
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

    // crud user
    public function create() { return view('users.create'); }
    public function store(Request $request) { /* ... */ }
    public function show($id) { $user = User::findOrFail($id); return view('users.show', compact('user')); }
    public function edit($id) { $user = User::findOrFail($id); return view('users.edit', compact('user')); }
    public function update(Request $request, $id) { /* ... */ }
    public function destroy($id) { User::destroy($id); return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.'); }
}