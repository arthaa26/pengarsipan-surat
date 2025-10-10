<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Tambah Pengguna</title>
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
    <h2 class="fw-bold text-white mb-4">Tambah Pengguna Baru</h2>

    {{-- Kartu Form dengan Styling Admin yang Konsisten --}}
    <div class="card shadow mx-auto" style="background-color: var(--color-table-accent); border: none; max-width: 600px;">
        <div class="card-body">
            
            {{-- Form mengirim data ke route: admin.manajemenuser.store --}}
            <form action="{{ route('admin.manajemenuser.store') }}" method="POST">
                @csrf

                {{-- Alert untuk menampilkan error validasi --}}
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
                           value="{{ old('name') }}" required>
                </div>
                
                {{-- FIELD: Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label text-dark fw-bold">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="{{ old('username') }}">
                    <div class="form-text text-muted">Opsional, harus unik.</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-dark fw-bold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email') }}" required>
                </div>
                
                {{-- FIELD: No. HP --}}
                <div class="mb-3">
                    <label for="no_hp" class="form-label text-dark fw-bold">No. HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" 
                           value="{{ old('no_hp') }}">
                    <div class="form-text text-muted">Opsional.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-dark fw-bold">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           required>
                    <div class="form-text text-muted">Minimal 8 karakter.</div>
                </div>

                <div class="mb-4">
                    <label for="role_id" class="form-label text-dark fw-bold">Role</label>
                    <select class="form-select" id="role_id" name="role_id" required>
                        <option value="">Pilih Role</option>
                        <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ old('role_id') == 2 || old('role_id') === null ? 'selected' : '' }}>Dosen</option>
                        <option value="3" {{ old('role_id') == 3 || old('role_id') === null ? 'selected' : '' }}>Rektor</option>
                        <option value="4" {{ old('role_id') == 4 || old('role_id') === null ? 'selected' : '' }}>Dekan</option>
                        <option value="5" {{ old('role_id') == 5 || old('role_id') === null ? 'selected' : '' }}>Kaprodi</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.manajemenuser.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-plus-fill"></i> Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>