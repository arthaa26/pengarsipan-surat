<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 
use App\Models\Role;
use App\Models\Faculty; 
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        try {
            $query = User::with(['role', 'faculty']); 
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhereHas('faculty', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }
            $users = $query->orderBy('created_at', 'desc')->paginate(10);
        } catch (\Exception $e) {
            Log::error("Error memuat data pengguna: " . $e->getMessage());
            $users = User::paginate(10)->setCollection(collect());
            return redirect()->route('admin.manajemenuser.index')->with('error', 'Gagal memuat data pengguna. Lihat log server.');
        }
        return view('admin.manajemenuser.index', compact('users'));
    }
    
    public function create()
    {
        $roles = Role::all();
        $faculties = Faculty::all();
        return view('admin.manajemenuser.create', compact('roles', 'faculties'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'faculty_id' => 'nullable|exists:faculties,id'
        ]);

        try {
            // Buat user baru
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'faculty_id' => $request->faculty_id,
            ]);

            return redirect()->route('admin.manajemenuser.index')
                             ->with('success', 'Pengguna baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan pengguna baru: " . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
    
    public function edit($id)
    {
        $user = User::with(['role', 'faculty'])->findOrFail($id);
        $roles = Role::all();
        $faculties = Faculty::all();
        return view('admin.manajemenuser.edit', compact('user', 'roles', 'faculties')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'faculty_id' => 'nullable|exists:faculties,id'
        ]);
        
        try {
            $dataToUpdate = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'faculty_id' => $request->faculty_id,
            ];

            if ($request->filled('password')) {
                $dataToUpdate['password'] = Hash::make($request->password);
            }

            $user->update($dataToUpdate);

            return redirect()->route('admin.manajemenuser.index')
                             ->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal memperbarui pengguna (ID: {$id}): " . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }
    
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.manajemenuser.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function show($id)
    {
        $user = User::with(['role', 'faculty'])->findOrFail($id);
        return view('admin.manajemenuser.show', compact('user'));
    }
}