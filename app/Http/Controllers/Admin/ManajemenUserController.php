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
    public function index(Request $request) // <-- Menerima Request
    {
        // Ambil string pencarian dari request
        $search = $request->query('search');

        try {
            // 1. Inisialisasi query dengan eager loading untuk Faculty dan Role
            $query = User::with(['role', 'faculty']); 

            // 2.  logika pencarian jika ada pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    // Cari berdasarkan Nama atau Email
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                })
                // Untuk mencari berdasarkan nama Fakultas pake relasi (orWhereHas)
                ->orWhereHas('faculty', function ($q) use ($search) {
                    // Note: Asumsi nama kolom di tabel faculties adalah 'name'
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }
            $users = $query->orderBy('created_at', 'desc')->paginate(10);

        } catch (\Exception $e) {
            Log::error("Error memuat data pengguna: " . $e->getMessage());
            $users = User::paginate(10)->setCollection(collect());
            return redirect()->route('admin.manajemenuser.index')->with('error', 'Gagal memuat data pengguna. Lihat log server.');
        }

        // Untuk mengirim variabel $users ke view
        return view('admin.manajemenuser.index', compact('users'));
    }
    
    public function create()
    {
        $roles = Role::all();
        $faculties = Faculty::all();
        return view('admin.manajemenuser.create', compact('roles', 'faculties'));
    }
    
    public function edit($id)
    {
        $user = User::with(['role', 'faculty'])->findOrFail($id);
        $roles = Role::all();
        $faculties = Faculty::all();
        return view('admin.manajemenuser.edit', compact('user', 'roles', 'faculties')); 
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
