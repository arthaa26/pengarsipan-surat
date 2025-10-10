<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Edit Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* CSS DARI DASHBOARD UTAMA */
        :root {
            --color-bg-body: #4db8ff;
            --color-table-accent: #f7c948;
            --color-text-white: #fff;
            --color-text-dark: #000000;
        }
        body {
            background: var(--color-bg-body);
            font-family: 'Arial', sans-serif;
            color: var(--color-text-white);
        }
    </style>
</head>
<body>

<div class="container mt-5">
    {{-- Pastikan variabel $user dikirim dari Controller: compact('user') --}}
    <h2 class="fw-bold text-white mb-4">Edit Pengguna: {{ $user->name }}</h2>

    {{-- Kartu Form --}}
    <div class="card shadow mx-auto" style="background-color: var(--color-table-accent); border: none; max-width: 600px;">
        <div class="card-body">
            
            {{-- Form mengirim data ke route: admin.manajemenuser.update dengan ID pengguna --}}
            <form action="{{ route('admin.manajemenuser.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Wajib menggunakan method spoofing untuk HTTP PUT/PATCH --}}

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label text-dark fw-bold">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $user->name) }}" required>
                </div>

                {{-- FIELD: Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label text-dark fw-bold">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="{{ old('username', $user->username) }}">
                    <div class="form-text text-muted">Opsional, harus unik.</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-dark fw-bold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $user->email) }}" required>
                </div>
                
                {{-- FIELD: No. HP --}}
                <div class="mb-3">
                    <label for="no_hp" class="form-label text-dark fw-bold">No. HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" 
                           value="{{ old('no_hp', $user->no_hp) }}">
                    <div class="form-text text-muted">Opsional.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-dark fw-bold">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="form-text text-muted">Hanya isi jika Anda ingin mengganti password. Minimal 8 karakter.</div>
                </div>

                <div class="mb-4">
                    <label for="role_id" class="form-label text-dark fw-bold">Role</label>
                    <select class="form-select" id="role_id" name="role_id" required>
                        <option value="">Pilih Role</option>
                        {{-- Logika Role: Memilih nilai lama dari DB atau input lama --}}
                        <option value="1" {{ old('role_id', $user->role_id) == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ old('role_id', $user->role_id) == 2 ? 'selected' : '' }}>Dosen</option>
                        <option value="3" {{ old('role_id', $user->role_id) == 3 ? 'selected' : '' }}>Rektor</option>
                        <option value="4" {{ old('role_id', $user->role_id) == 4 ? 'selected' : '' }}>Dekan</option>
                        <option value="5" {{ old('role_id', $user->role_id) == 5 ? 'selected' : '' }}>Kaprodi</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.manajemenuser.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">
                        <i class="bi bi-save"></i> Perbarui Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>