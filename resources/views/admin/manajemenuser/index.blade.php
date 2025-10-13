<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Manajemen Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* === WARNA UTAMA === */
        :root {
            --color-bg-body: #4db8ff;
            --color-sidebar-primary: #0066cc;
            --color-sidebar-link: #0080ff;
            --color-sidebar-link-hover: #0059b3;
            --color-card-green: #22c55e;
            --color-table-accent: #f7c948;
            --color-text-white: #fff;
            --color-text-dark: #000;
        }

        /* === GLOBAL STYLES === */
        body {
            background-color: var(--color-bg-body); /* Perbaikan: gunakan background-color */
            font-family: 'Arial', sans-serif;
            color: var(--color-text-white);
            margin: 0; 
            padding: 0; 
        }

        /* === LAYOUT & SIDEBAR === */
        .app-layout {
            display: flex;
            min-height: 100vh;
            width: 100%; /* Agar lebih solid */
        }
        .sidebar {
            background: var(--color-sidebar-primary);
            padding: 20px 10px;
            width: 250px; 
            flex-shrink: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
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
        .sidebar-menu a i {
            font-size: 1.2rem;
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

        /* === LOGO === */
        .logo-img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #fff;
            margin-right: 10px;
            border: 2px solid var(--color-text-white);
        }
        .logo-text {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--color-text-white);
            margin: 0;
        }

        /* === TABLE STYLES (REFINED) === */
        .table-container {
            background: var(--color-text-white); /* Ubah ke putih/latar belakang */
            border-radius: 10px;
            padding: 0;
            overflow-x: auto;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .table-header {
            background: var(--color-table-accent);
            color: var(--color-text-dark);
            padding: 15px 20px;
            font-size: 1.3rem;
            font-weight: bold;
            text-align: left;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .table {
            margin-bottom: 0;
        }
        /* Isi dan Header Tabel */
        .table th, .table td {
            color: var(--color-text-dark); /* Selalu gelap */
            padding: 12px 10px; /* Padding lebih ringkas */
            vertical-align: middle;
            border: none; /* Hapus border default */
        }
        .table thead tr th {
            color: var(--color-text-dark);
            background-color: var(--color-table-accent);
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
        }
        /* Alternating row colors */
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #ffffff;
        }
        .table-striped > tbody > tr:nth-of-type(even) > * {
            background-color: #f8f9fa;
        }
        .table-striped > tbody > tr:hover > * {
            background-color: #e9ecef; /* Efek hover */
        }
        /* Footer Keterangan Role */
        .table tfoot tr td {
            color: var(--color-text-dark); 
            background-color: #fff !important;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 10px 10px;
        }
        
        /* Tombol Aksi */
        .btn-action {
            width: 32px; /* Lebih ringkas */
            height: 32px;
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

        /* Tombol Utama (Tambah Pengguna) */
        .btn-primary {
            background-color: var(--color-sidebar-primary);
            border-color: var(--color-sidebar-primary);
        }
        .btn-primary:hover {
            background-color: var(--color-sidebar-link-hover);
            border-color: var(--color-sidebar-link-hover);
        }

        /* === PROFILE === */
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

        /* === PAGINATION === */
        .pagination {
            justify-content: flex-end;
            margin: 15px 20px 20px 0;
        }
        .pagination .page-link {
            color: var(--color-sidebar-link);
            border: 1px solid var(--color-sidebar-link);
            background-color: #fff;
            transition: all 0.2s ease-in-out;
        }
        .pagination .page-link:hover {
            background-color: var(--color-sidebar-link-hover);
            color: #fff;
            border-color: var(--color-sidebar-link-hover);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--color-sidebar-link-hover);
            border-color: var(--color-sidebar-link-hover);
            color: #fff;
            box-shadow: 0 0 6px rgba(0,0,0,0.2);
        }

        /* === MEDIA QUERIES (RESPONSIVITAS) === */
        @media (max-width: 768px) {
            .app-layout {
                flex-direction: column; 
            }
            .sidebar {
                width: 100%;
                position: static;
                padding: 15px 10px;
            }
            .main-content-col {
                padding: 10px;
            }
            .sidebar-header {
                justify-content: center;
            }
            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-around;
                gap: 5px;
            }
            .sidebar-menu a {
                flex-basis: 30%; 
                justify-content: center;
                text-align: center;
                font-size: 0.9rem;
                padding: 8px 5px;
            }
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
                onerror="this.onerror=null; this.src='https://placehold.co/65x65/f7c948/0066cc?text=M';"
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
                    <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                    <i class="bi bi-chevron-down ms-2 fs-5 text-white"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-header">{{ Auth::user()?->name ?? 'Admin' }}</li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2 text-danger"></i>Logout
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                </ul>
            </div>
        </div>

        {{-- START CONTENT MANAJEMEN USER --}}
        <div class="container-fluid mt-4 p-0">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('admin.manajemenuser.create') ?? '#' }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna Baru
                    </a>
                </div>
            </div>

            <div class="table-container mt-4">
                <div class="table-header">DAFTAR PENGGUNA SISTEM</div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mt-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Dibuat Pada</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Menghilangkan style="color: black;" --}}
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->name ?? 'Nama Pengguna' }}</td>
                                    <td>{{ $user->email ?? 'email@example.com' }}</td>
                                    <td>
                                        {{-- LOGIKA PEMETAAN ROLE YANG BENAR --}}
                                        @if ($user->role_id == 1)
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif ($user->role_id == 2)
                                            <span class="badge bg-primary">Rektor</span>
                                        @elseif ($user->role_id == 3)
                                            <span class="badge bg-info">Dekan</span>
                                        @elseif ($user->role_id == 4)
                                            <span class="badge bg-success">Dosen</span>
                                        @elseif ($user->role_id == 5)
                                            <span class="badge bg-warning">Kaprodi</span>
                                        @elseif ($user->role_id == 6)
                                            <span class="badge bg-secondary">Tenaga Pendidik</span>
                                        @elseif ($user->role_id == 7)
                                            <span class="badge bg-dark">Dosen Khusus</span>
                                        @else
                                            <span class="badge bg-secondary">Undefined</span> 
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at ?? now())->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="action-buttons justify-content-center">
                                            <a href="{{ route('admin.manajemenuser.edit', $user->id ?? 0) ?? '#' }}" 
                                               class="btn btn-action btn-warning" title="Edit Pengguna">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-action btn-danger" title="Hapus Pengguna"
                                                onclick="confirmDelete('{{ $user->id ?? 0 }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $user->id ?? 0 }}" method="POST" 
                                              action="{{ route('admin.manajemenuser.destroy', $user->id ?? 0) }}" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                {{-- Menghilangkan style="color: black;" --}}
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pengguna ditemukan.</td> 
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            {{-- Menghilangkan style="color: black; background: #fff;" (background ditangani oleh CSS) --}}
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Keterangan ID Role di DB:</td>
                                <td colspan="3">
                                    <span class="badge bg-danger">1=Admin</span> 
                                    <span class="badge bg-primary">2=Rektor</span> 
                                    <span class="badge bg-info">3=Dekan</span> 
                                    <span class="badge bg-success">4=Dosen</span> 
                                    <span class="badge bg-warning">5=Kaprodi</span>
                                    <span class="badge bg-secondary">6=Tendik</span> 
                                    <span class="badge bg-dark">7=Dosen Khusus</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Pagination --}}
                    @if(is_object($users) && method_exists($users, 'links'))
                        <div class="d-flex justify-content-end p-3">
                            {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- END CONTENT --}}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function confirmDelete(userId) {
        if (confirm("Apakah Anda yakin ingin menghapus pengguna ini?")) {
            document.getElementById('delete-form-' + userId).submit();
        }
    }
</script>

</body>
</html>