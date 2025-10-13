<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users; 
use App\Models\Role;
use App\Models\Faculty; 
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ManajemenUserController extends Controller
{
    /**
     * Menyimpan pengguna baru ke database (admin.manajemenuser.store).
     */
    public function store(Request $request) 
    { 
        // NOTE: Type casting dihapus karena validasi Laravel sudah menangani tipe integer
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'faculty_id' => 'nullable|integer|exists:faculties,id',
            'username' => 'nullable|string|max:255|unique:users',
            'no_hp' => 'nullable|string|max:15',
        ]);
        
        $validatedData['password'] = Hash::make($validatedData['password']);
        
        // Simpan data
        Users::create($validatedData); 

        return redirect()->route('admin.manajemenuser.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Memperbarui pengguna di database (admin.manajemenuser.update).
     */
    public function update(Request $request, $id) 
    { 
        $user = Users::findOrFail($id);

        // Validasi data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
            'password' => 'nullable|string|min:8', // Password nullable
            'role_id' => 'required|integer|exists:roles,id',
            'faculty_id' => 'nullable|integer|exists:faculties,id', 
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
        ]);

        // Hapus password dari data jika input kosong, agar password lama tetap utuh
        if ($request->filled('password')) {
            // Hash password baru jika diisi
            $validatedData['password'] = Hash::make($request->input('password'));
        } else {
            // Hapus kunci password dari array update
            unset($validatedData['password']); 
        }

        // Update pengguna
        $user->update($validatedData);

        return redirect()->route('admin.manajemenuser.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }
    
    // NOTE: Fungsi index, create, edit, destroy, dan show harus berada di sini
    // Saya asumsikan Anda telah menggabungkannya ke dalam Canvas Anda
    public function index()
    {
        $users = Users::with(['role', 'faculty'])->latest()->paginate(10);
        return view('admin.manajemenuser.index', compact('users'));
    }

    public function create()
    {
        // Mengasumsikan Anda memiliki logic aman untuk memuat roles/faculties di sini
        $roles = \App\Models\Role::all();
        $faculties = \App\Models\Faculty::all();
        return view('admin.manajemenuser.create', compact('roles', 'faculties'));
    }
    
    public function edit($id)
    {
        $user = Users::with(['role', 'faculty'])->findOrFail($id);
        $roles = \App\Models\Role::all();
        $faculties = \App\Models\Faculty::all();
        // Pastikan nama view yang benar adalah 'edit' jika Anda menggunakan file terpisah.
        return view('admin.manajemenuser.edit', compact('user', 'roles', 'faculties')); 
    }

    public function destroy($id)
    {
        Users::destroy($id);
        return redirect()->route('admin.manajemenuser.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function show($id)
    {
        $user = Users::with(['role', 'faculty'])->findOrFail($id);
        return view('admin.manajemenuser.show', compact('user'));
    }
}
