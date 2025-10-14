<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Manajemen Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
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

        body {
            background-color: var(--color-bg-body); 
            font-family: 'Arial', sans-serif;
            color: var(--color-text-white);
            margin: 0; 
            padding: 0; 
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
            width: 100%; 
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
            background: var(--color-text-white);
            color: var(--color-text-dark);
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
        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo-img {
            width: 65px;
            height: 65px;
            object-fit: cover;
            margin-right: 10px;
            display: block;
            border-radius: 50%;
        }
        .logo-text {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--color-text-white);
            margin: 0;
        }

        .table-container {
            background: var(--color-text-white); 
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
            color: var(--color-text-dark); 
            padding: 12px 10px; 
            vertical-align: middle;
            border: none; 
        }
        .table thead tr th {
            color: var(--color-text-dark);
            background-color: var(--color-table-accent);
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #ffffff;
        }
        .table-striped > tbody > tr:nth-of-type(even) > * {
            background-color: #f8f9fa;
        }
        .table-striped > tbody > tr:hover > * {
            background-color: #e9ecef;
        }
        .table tfoot tr td {
            color: var(--color-text-dark); 
            background-color: #fff !important;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 20px 20px;
        }
        .btn-action {
            width: 32px; 
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
        .btn-primary {
            background-color: var(--color-sidebar-primary);
            border-color: var(--color-sidebar-primary);
        }
        .btn-primary:hover {
            background-color: var(--color-sidebar-link-hover);
            border-color: var(--color-sidebar-link-hover);
        }
        .user-info { 
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .user-identity {
            display: flex; 
            flex-direction: column; 
            line-height: 1.2;
            margin-right: 10px; 
            margin-left: 0;
            text-align: right; 
        }
        
        .user-name { 
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--color-text-white);
            display: block; 
        }
        .user-role-display { 
            font-size: 0.85rem; 
            font-weight: normal; 
            color: rgba(255, 255, 255, 0.8);
            display: block; 
        }

        .profile-img {
            direction: ltr;
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
            color: var(--color-sidebar-primary);
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
                    @auth
                        @php
                            // Asumsi Auth::user()->load(['role', 'faculty']) sudah dipanggil di Controller.
                            // Catatan: Variabel $role seharusnya diambil dari relasi Auth::user()->role->name, tetapi menggunakan $role untuk keamanan
                            $userName = Auth::user()->name ?? 'Admin';
                            $userRole = Auth::user()->role->display_name ?? 'Administrator';
                            $displayTitle = "({$userRole})";
                        @endphp
                        
                        {{-- Nama dan Role di Kiri --}}
                        <div class="user-identity">
                            <span class="user-name">{{ $userName }}</span>
                            <span class="user-role-display">{{ $displayTitle }}</span>
                        </div>
                        {{-- Ikon Profil di Kanan --}}
                        <div class="profile-icon">
                            @if (Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ $userName }}" class="profile-img">
                            @else
                                <div class="profile-img"><i class="bi bi-person-circle"></i></div>
                            @endif
                        </div>
                    @else
                        <div class="user-identity">
                            <span class="user-name">Admin User</span>
                            <span class="user-role-display">(Administrator)</span>
                        </div>
                        <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                    @endauth
                    <i class="bi bi-chevron-down ms-2 fs-5 text-white"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-header">
                        @auth {{ $userName }} <br><small class="text-muted">{{ $displayTitle }}</small> @else Admin @endauth
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.daftarsurat.index') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>Daftar Surat</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.manajemenuser.index') ?? '#' }}"><i class="bi bi-person-fill-gear me-2"></i>Manajemen User</a></li>
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
            
            {{-- FITUR PENCARIAN & TAMBAH PENGGUNA --}}
            <div class="row mb-3 align-items-center">
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <a href="{{ route('admin.manajemenuser.create') ?? '#' }}" class="btn btn-primary w-100">
                        <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna Baru
                    </a>
                </div>
                <div class="col-12 col-md-8">
                    {{-- Form Pencarian (Menggunakan GET Request ke route index saat ini) --}}
                    <form method="GET" action="{{ route('admin.manajemenuser.index') ?? '#' }}" class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Cari Nama, Email, atau Fakultas..." aria-label="Search" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-light text-dark bg-white" type="submit" title="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.manajemenuser.index') ?? '#' }}" class="btn btn-outline-danger ms-2" title="Reset Pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
            
            <div class="table-container mt-4">
                <div class="table-header">
                    DAFTAR PENGGUNA SISTEM 
                    @if (request('search'))
                        <span class="badge bg-danger ms-2" style="font-size: 0.7em;">
                            Hasil Pencarian: "{{ request('search') }}"
                        </span>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mt-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Fakultas</th> {{-- KOLOM FAKULTAS --}}
                                <th>Role</th>
                                <th>Dibuat Pada</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- PENTING: Asumsi variabel $users dikirim dari Controller dengan relasi 'faculty' sudah di-load --}}
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->name ?? 'Nama Pengguna' }}</td>
                                    <td>{{ $user->email ?? 'email@example.com' }}</td>
                                    
                                    {{-- DATA FAKULTAS --}}
                                    <td>
                                        {{-- Menampilkan nama fakultas melalui relasi --}}
                                        {{ $user->faculty->name ?? '-' }}
                                    </td>
                                    
                                    <td>
                                        {{-- LOGIKA PEMETAAN ROLE DARI DB --}}
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
                                <tr>
                                    <td colspan="7" class="text-center">
                                        @if (request('search'))
                                            Tidak ada pengguna ditemukan untuk pencarian: "<b>{{ request('search') }}</b>"
                                        @else
                                            Tidak ada data pengguna ditemukan.
                                        @endif
                                    </td> 
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Keterangan ID Role di DB:</td>
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

                    {{-- Pagination (mempertahankan query search) --}}
                    @if(is_object($users) && method_exists($users, 'links'))
                        <div class="d-flex justify-content-end p-3">
                            {{ $users->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- END CONTENT --}}
        {{-- START: FOOTER HAK CIPTA --}}
        @include('partials.footer')
        {{-- END: FOOTER HAK CIPTA --}}
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
