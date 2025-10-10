<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Manajemen Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* CSS DARI DASHBOARD UTAMA */
        :root {
            --color-bg-body: #4db8ff;
            --color-sidebar-primary: #0066cc;
            --color-sidebar-link: #0080ff;
            --color-sidebar-link-hover: #0059b3;
            --color-card-green: #22c55e;
            --color-table-accent: #f7c948;
            --color-text-white: #fff;
            --color-text-dark: #000000;
        }

        body {
            background: var(--color-bg-body);
            font-family: 'Arial', sans-serif;
            color: var(--color-text-white);
        }

        /* LAYOUT & SIDEBAR */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            background: var(--color-sidebar-primary);
            padding: 20px 10px;
            width: 250px; 
            flex-shrink: 0;
        }
        .sidebar-menu a {
            display: flex; 
            align-items: center;
            background: var(--color-sidebar-link);
            color: var(--color-text-white);
            text-decoration: none;
            margin: 8px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .sidebar-menu a.active-menu {
            background: var(--color-sidebar-link-hover);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .sidebar-menu a:hover {
            background: var(--color-sidebar-link-hover);
        }
        .main-content-col {
            flex-grow: 1;
            padding: 20px;
        }
        
        /* LOGO STYLING */
        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;    
        }
        .logo-img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #fefefeff;
            margin-right: 10px;
            display: block;
            border: 2px solid var(--color-text-white);
        }
        .logo-text {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--color-text-white);
            margin: 0;
        }

        /* TABLE STYLES */
        .table-container {
            background: var(--color-table-accent); 
            border-radius: 10px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-header {
            background: var(--color-table-accent);
            color: var(--color-text-dark);
            padding: 15px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: left;
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.7);
        }
        .table-striped > tbody > tr:nth-of-type(even) > * {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .table th, .table td {
            color: var(--color-text-dark); 
            padding: 15px 10px;
            vertical-align: middle;
        }
        .table thead tr th {
            color: var(--color-text-dark);
            background-color: var(--color-table-accent);
        }
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            padding: 0;
            margin: 0 2px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
            justify-content: center;
        }
        
        /* PROFILE STYLING */
        .user-info { 
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--color-text-white);
            border: 2px solid var(--color-text-white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem; 
        }

    </style>
</head>
<body>

<div class="app-layout">
    <div class="sidebar">
        <div class="sidebar-header">
            <img 
                src="/images/unmuh.png" 
                alt="Logo Muhammadiyah" 
                class="logo-img" 
                title="Logo Muhammadiyah"
                onerror="this.onerror=null; this.src='https://placehold.co/35x35/f7c948/0066cc?text=M';"
            >
            <p class="logo-text">E-ARSIP</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            <a href="{{ route('admin.daftarsurat.index') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT</a>
            <a href="{{ route('admin.manajemenuser.index') ?? '#' }}" class="active-menu"><i class="bi bi-person-fill-gear me-2"></i>MANAJEMEN USER</a>
        </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">MANAJEMEN PENGGUNA</h2> 
            
            <div class="dropdown">
                <div class="user-info dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                    @else
                        <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                    @endauth
                    <i class="bi bi-chevron-down ms-2 fs-5 text-white"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-header">@auth {{ Auth::user()?->name ?? 'Admin' }} @else Admin @endauth</li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Logout</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                </ul>
            </div>
        </div>

        {{-- START CONTENT MANAJEMEN USER --}}
        <div class="container-fluid mt-4 p-0">
            <div class="row mb-3">
                <div class="col-12">
                    {{-- Tombol Tambah User --}}
                    <a href="{{ route('admin.manajemenuser.create') ?? '#' }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna Baru
                    </a>
                </div>
            </div>

            {{-- Tabel Daftar Pengguna --}}
            <div class="table-container mt-4">
                <div class="table-header">DAFTAR PENGGUNA SISTEM</div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mt-0">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 25%;">Nama</th>
                                <th style="width: 25%;">Email</th>
                                <th style="width: 15%;">Role</th>
                                <th style="width: 15%;">Dibuat Pada</th>
                                <th style="width: 15%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- LOOPING: $users dipastikan tidak null oleh Controller --}}
                            @forelse ($users as $index => $user)
                                <tr style="color: black;">
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->name ?? 'Nama Pengguna' }}</td>
                                    <td>{{ $user->email ?? 'email@example.com' }}</td>
                                    {{-- LOGIKA ROLE: 1=Admin, Selainnya=User (Aman untuk BIGINT) --}}
                                    <td>
                                        @if ($user->role_id == 1)
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif ($user->role_id == 3)
                                            <span class="badge bg-primary">Rektor</span>
                                        @elseif ($user->role_id == 4)
                                            <span class="badge bg-info">Dekan</span>
                                        @elseif ($user->role_id == 5)
                                            <span class="badge bg-warning">Kaprodi</span>
                                        @elseif ($user->role_id == 2)
                                            <span class="badge bg-success">Dosen</span>
                                        @else
                                            <span class="badge bg-secondary">Undefined</span> 
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at ?? now())->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="action-buttons justify-content-center">
                                            
                                            {{-- Tombol Edit (Proteksi ID) --}}
                                            <a href="{{ route('admin.manajemenuser.edit', $user->id ?? 0) ?? '#' }}" class="btn btn-action btn-warning" title="Edit Pengguna">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            
                                            {{-- Tombol Hapus (Proteksi ID) --}}
                                            <button class="btn btn-action btn-danger" title="Hapus Pengguna"
                                                onclick="confirmDelete('{{ $user->id ?? 0 }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        {{-- Form Hapus (Proteksi ID) --}}
                                        <form id="delete-form-{{ $user->id ?? 0 }}" method="POST" action="{{ route('admin.manajemenuser.destroy', $user->id ?? 0) }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr style="color: black;">
                                    <td colspan="6" class="text-center">Tidak ada data pengguna ditemukan.</td> 
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{-- Tampilkan pagination --}}
                    @if(is_object($users) && method_exists($users, 'links'))
                        <div class="p-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- END CONTENT MANAJEMEN USER --}}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /**
     * Fungsi untuk konfirmasi penghapusan pengguna.
     */
    function confirmDelete(userId) {
        if (confirm("Apakah Anda yakin ingin menghapus pengguna ini?")) {
            document.getElementById('delete-form-' + userId).submit();
        }
    }
</script>
</body>
</html>