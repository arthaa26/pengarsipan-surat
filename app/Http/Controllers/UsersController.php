<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Surat; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        // 1. Hitung Surat Masuk (sesuai nama variabel di view: $suratMasukCount)
        $suratMasukCount = Surat::where('to_user_id', $userId)->count();

        // 2. Hitung Surat Keluar (sesuai nama variabel di view: $suratKeluarCount)
        $suratKeluarCount = Surat::where('from_user_id', $userId)->count();

        // 3. Ambil Detail Data Surat Masuk untuk Tabel (sesuai nama variabel di view: $suratMasuk)
        // Kita hanya mengambil surat yang ditujukan kepada user ini
        $suratMasuk = Surat::where('to_user_id', $userId)
                             ->orderBy('created_at', 'desc') 
                             ->limit(10) // Batasi 10 surat terbaru
                             ->get();
        
        // 4. Mengarahkan ke view dashboard user
        return view('user.dashboard', [ 
            'suratMasukCount' => $suratMasukCount, // Diperbaiki: count_masuk -> suratMasukCount
            'suratKeluarCount' => $suratKeluarCount, // Diperbaiki: count_keluar -> suratKeluarCount
            'suratMasuk' => $suratMasuk, // Diperbaiki: history_surat -> suratMasuk
        ]);
    }

    /**
     * Method yang dibutuhkan oleh rute user.daftar_surat.index (jika diperlukan)
     */
    public function daftarSurat()
    {
        // Logika untuk menampilkan semua surat
        // ...
        return view('user.daftar_surat.index');
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
    public function viewSurat(Surat $surat)
    {
        // Logika untuk menampilkan detail surat
        // ...
        return view('surat.view', compact('surat'));
    }

    /**
     * Method untuk menghapus surat. Dipanggil oleh rute surat.delete
     */
    public function deleteSurat(Surat $surat)
    {
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
