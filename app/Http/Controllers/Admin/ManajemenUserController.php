<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users; // Menggunakan Model Users

class ManajemenUserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna (admin.manajemenuser.index).
     */
    public function index()
    {
        try {
            $users = Users::latest()->paginate(10); 
        } catch (\Exception $e) {
            $users = collect([]); 
        }
        
        return view('admin.manajemenuser.index', compact('users'));
    }

    /**
     * Menampilkan form untuk menambah pengguna baru (admin.manajemenuser.create).
     */
    public function create() 
    { 
        return view('admin.manajemenuser.create'); 
    }

    /**
     * Menyimpan pengguna baru ke database (admin.manajemenuser.store).
     */
    public function store(Request $request) 
    { 
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer', // Menggunakan role_id
            'username' => 'nullable|string|max:255|unique:users',
            'no_hp' => 'nullable|string|max:15',
        ]);
        
        $validatedData['password'] = Hash::make($validatedData['password']);
        Users::create($validatedData);

        return redirect()->route('admin.manajemenuser.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit pengguna (admin.manajemenuser.edit).
     */
    public function edit($id) 
    { 
        $user = Users::findOrFail($id);
        return view('admin.manajemenuser.edit', compact('user')); 
    }

    /**
     * Memperbarui pengguna di database (admin.manajemenuser.update).
     */
    public function update(Request $request, $id) 
    { 
        $user = Users::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|integer', // Menggunakan role_id
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); 
        }

        $user->update($validatedData);

        return redirect()->route('admin.manajemenuser.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database (admin.manajemenuser.destroy).
     */
    public function destroy($id) 
    { 
        Users::destroy($id); 
        
        return redirect()->route('admin.manajemenuser.index')->with('success', 'Pengguna berhasil dihapus.');
    }
    
    public function show($id) 
    { 
        $user = Users::findOrFail($id);
        return view('admin.manajemenuser.show', compact('user')); 
    }
}