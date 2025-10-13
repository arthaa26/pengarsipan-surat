<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KirimSurat;
use App\Models\Role; // Digunakan untuk mendapatkan nama role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Diperlukan untuk validasi update profile

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

    /**
     * Menampilkan Dashboard Dosen/User (Hanya menampilkan COUNT dan tabel ringkasan).
     */
    public function index()
    {
        $userId = Auth::id();
        
        // 1. QUERY SURAT MASUK (Ambil Query dari fungsi utility)
        $suratMasukQuery = $this->getSuratMasukQuery();
        $suratMasukCount = $suratMasukQuery->count();
        $suratMasuk = $suratMasukQuery->orderBy('created_at', 'desc')->limit(10)->get(); // Hanya 10 terbaru untuk dashboard

        // 2. QUERY SURAT KELUAR (YANG ANDA KIRIM)
        $suratKeluarQuery = KirimSurat::where('user_id_1', $userId);
        $suratKeluarCount = $suratKeluarQuery->count();
        
        // 3. Mengarahkan ke view dashboard user
        return view('user.dashboard', [ 
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $suratMasuk,
        ]);
    }

    // --- METODE BARU UNTUK HALAMAN DAFTAR SURAT TERPISAH ---

    /**
     * Menampilkan daftar LENGKAP Surat Masuk dengan pagination.
     */
    public function daftarSuratMasuk()
    {
        // Menggunakan query utility dan menambahkan pagination
        $suratList = $this->getSuratMasukQuery()
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('user.DaftarSurat.masuk', compact('suratList'));
    }

    /**
     * Menampilkan daftar LENGKAP Surat Keluar dengan pagination.
     */
    public function daftarSuratKeluar()
    {
        $userId = Auth::id();

        // Query Surat Keluar
        $suratList = KirimSurat::where('user_id_1', $userId)
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);
                                
        // Anda perlu memastikan view 'user.DaftarSurat.keluar' ada
        return view('user.DaftarSurat.keluar', compact('suratList'));
    }

    // --- METODE PROFIL (Sesuai permintaan sebelumnya) ---

    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], 
            'password_new' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password_new')) {
            $user->password = Hash::make($request->password_new);
        }
        
        $user->save();

        return redirect()->route('user.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }


    // --- METODE LAINNYA ---
    
    public function daftarSurat()
    {
        // Karena kita memisahkan ke daftarSuratMasuk/Keluar, 
        // metode ini bisa diubah menjadi redirect ke daftarSuratMasuk
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
        
        // Redirect ke halaman daftar surat yang sesuai
        $redirectRoute = ($surat->user_id_1 == Auth::id()) 
                         ? 'user.daftar_surat.keluar' 
                         : 'user.daftar_surat.masuk';
                         
        return redirect()->route($redirectRoute)->with('success', 'Surat berhasil dihapus.');
    }

    // --- CRUD USERS (Metode ini lebih cocok berada di AdminController) ---
    public function create() { return view('users.create'); }
    public function store(Request $request) { /* ... */ }
    public function show($id) { $user = User::findOrFail($id); return view('users.show', compact('user')); }
    public function edit($id) { $user = User::findOrFail($id); return view('users.edit', compact('user')); }
    public function update(Request $request, $id) { /* ... */ }
    public function destroy($id) { User::destroy($id); return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.'); }
}