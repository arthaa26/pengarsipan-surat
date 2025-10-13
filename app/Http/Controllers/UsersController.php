<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KirimSurat; // Menggunakan model KirimSurat yang benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Tambahkan import Storage

class UsersController extends Controller
{
    /**
     * Menampilkan Dashboard Dosen.
     * Mengambil data agregat dan histori surat untuk user yang sedang login.
     */
    public function index()
    {
        // Mendapatkan objek user yang sedang login
        $user = Auth::user();
        $userId = $user->id;

        // --- MENGHITUNG DAN MENGAMBIL DATA DARI TABEL kirim_surat ---
        
        // 1. Hitung Surat Masuk (Surat yang DITUJUKAN kepada user ini: user_id_2)
        $suratMasukQuery = KirimSurat::where('user_id_2', $userId);
        $suratMasukCount = $suratMasukQuery->count();

        // 2. Hitung Surat Keluar (Surat yang DIKIRIM dari user ini: user_id_1)
        $suratKeluarQuery = KirimSurat::where('user_id_1', $userId);
        $suratKeluarCount = $suratKeluarQuery->count();

        // 3. Ambil Detail Data Surat Masuk untuk Tabel (dibatasi 10)
        $suratMasuk = $suratMasukQuery->orderBy('created_at', 'desc') 
                                     ->limit(10)
                                     ->get();
        
        // 4. Mengarahkan ke view dashboard user
        // Pastikan nama view yang dikembalikan adalah 'user.dashboard' (sesuai rute Anda)
        return view('user.dashboard', [ 
            'suratMasukCount' => $suratMasukCount,
            'suratKeluarCount' => $suratKeluarCount,
            'suratMasuk' => $suratMasuk,
        ]);
    }

    /**
     * Method yang dibutuhkan oleh rute user.daftar_surat.index (jika diperlukan)
     */
    public function daftarSurat()
    {
        // Logika untuk menampilkan semua surat
        // ...
        return view('user.daftarsurat.index');
    }

    /**
     * Method yang dibutuhkan oleh rute user.kirim_surat.create
     */
    public function createSurat()
    {
        // Logika untuk menampilkan form kirim surat
        // ...
        return view('user.kirimsurat.index');
    }

    /**
     * Method untuk melihat detail surat. Dipanggil oleh rute surat.view
     */
    public function viewSurat(KirimSurat $surat)
    {
        // Logika untuk menampilkan detail surat
        // ...
        return view('surat.view', compact('surat'));
    }

    /**
     * METHOD BARU: Menampilkan file lampiran di browser.
     */
    public function viewFileSurat(KirimSurat $surat)
    {
        if (Storage::disk('public')->exists($surat->file_path)) {
            // Menggunakan response() untuk menampilkan file (misalnya PDF) di browser
            return Storage::disk('public')->response($surat->file_path);
        }
        
        // Jika file tidak ditemukan
        abort(404, 'File lampiran tidak ditemukan.');
    }
    
    /**
     * METHOD BARU: Mendownload file lampiran.
     */
    public function downloadSurat(KirimSurat $surat)
    {
        if (Storage::disk('public')->exists($surat->file_path)) {
            // Menggunakan download() untuk memaksa download file
            return Storage::disk('public')->download($surat->file_path);
        }
        
        // Jika file tidak ditemukan
        abort(404, 'File lampiran tidak ditemukan.');
    }

    /**
     * Method untuk menghapus surat. Dipanggil oleh rute surat.delete
     */
    public function deleteSurat(KirimSurat $surat)
    {
        // Hapus file terkait dari storage sebelum menghapus record
        if ($surat->file_path) {
            Storage::disk('public')->delete($surat->file_path);
        }
        
        // Hapus surat
        $surat->delete();
        
        // Redirect kembali ke dashboard dengan pesan sukses
        return redirect()->route('user.dashboard')->with('success', 'Surat berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi dan Hashing Password
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'username' => 'nullable|string|max:255|unique:users',
            'no_hp' => 'nullable|string|max:15',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        
        User::create($validatedData);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail user tertentu.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui user di database.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Validasi data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'username' => 'nullable|string|max:255|unique:users,username,' . $id,
            'no_hp' => 'nullable|string|max:15',
            'role_id' => 'required|integer|exists:roles,id', 
        ]);
        
        // Hashing password hanya jika password baru diisi
        if ($request->filled('password')) {
             $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // Hapus field password dari data update jika tidak diisi
            unset($validatedData['password']); 
        }

        $user->update($validatedData);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
