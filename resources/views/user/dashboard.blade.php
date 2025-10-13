<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* BASE STYLES */
        :root {
            --color-bg-body: #4db8ff;
            --color-sidebar-primary: #0066cc;
            --color-sidebar-link: #0080ff;
            --color-sidebar-link-hover: #0059b3;
            --color-card-green: #22c55e;
            --color-card-orange: #f59e42;
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

        /* DEFAULT MENU LINK STYLE */
        .sidebar-menu > a { /* Target HANYA link level atas */
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
        .sidebar-menu > a:hover {
            background: var(--color-sidebar-link-hover);
        }
        
        /* ACTIVE LINK STYLE (DASHBOARD) */
        .sidebar-menu a.active-link { 
            background: var(--color-text-white);
            color: var(--color-text-dark);
        }
        
        /* --- SIDEBAR DROPDOWN (COLLAPSE) STYLES --- */
        .sidebar-dropdown-item {
            margin: 8px 0;
        }
        .sidebar-dropdown-toggle {
            display: flex !important;
            align-items: center;
            justify-content: space-between; 
            background: var(--color-sidebar-link);
            color: var(--color-text-white);
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.2s;
            cursor: pointer;
            width: 100%;
            text-align: left;
            border: none;
            line-height: 1.2;
        }
        .sidebar-dropdown-toggle:hover {
            background: var(--color-sidebar-link-hover);
            color: var(--color-text-white);
        }
        /* Style untuk toggle saat terbuka */
        .sidebar-dropdown-toggle[aria-expanded="true"] {
            background: var(--color-sidebar-link-hover);
            border-radius: 5px 5px 0 0;
        }
        /* Rotasi ikon panah */
        .sidebar-dropdown-toggle .bi-chevron-down {
            transition: transform 0.3s;
        }
        .sidebar-dropdown-toggle[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(-180deg);
        }
        .sidebar-dropdown-menu {
            list-style: none; 
            padding-left: 0;
            margin-bottom: 0; 
            position: static; 
            background-color: var(--color-sidebar-link-hover);
            border: none;
            padding: 0 10px 5px 10px;
            border-radius: 0 0 5px 5px;
            box-shadow: none; 
            width: 100%;
            margin-top: 0; 
        }
        .sidebar-dropdown-menu li {
            margin: 0;
        }
        .sidebar-dropdown-menu li a {
            display: flex;
            align-items: center;
            background: transparent !important; 
            color: var(--color-text-white);
            font-weight: normal;
            padding: 8px 10px 8px 30px; 
            margin: 2px 0;
            border-radius: 3px;
            text-decoration: none;
        }
        .sidebar-dropdown-menu li a:hover {
            background: var(--color-sidebar-primary) !important;
            color: var(--color-text-white) !important;
        }
        /* --- END SIDEBAR DROPDOWN STYLES --- */

        .main-content-col {
            flex-grow: 1;
            padding: 20px;
        }
        .card-box {
            border-radius: 10px;
            padding: 20px;
            color: var(--color-text-white);
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 100px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-green {
            background: var(--color-card-green);
        }
        .card-orange {
            background: var(--color-card-orange); 
        }
        .card-box .number {
            font-size: 2.5rem;
            line-height: 1;
        }
        .card-box .icon {
            font-size: 2.5rem;
        }
        .table-container {
            background: var(--color-table-accent); 
            border-radius: 10px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-header {
            background: var(--color-table-accent);
            color: var(--color-text-white);
            padding: 15px 20px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .table th, .table td {
            color: var(--color-text-dark); 
            padding: 15px 10px;
            vertical-align: middle;
        }
        .table thead tr {
            color: var(--color-text-white);
            font-weight: bold;
            background-color: var(--color-table-accent);
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.7);
        }
        .table-striped > tbody > tr:nth-of-type(even) > * {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            padding: 0;
            margin: 2px 0; /* Add margin for small screens */
        }
        /* PROFILE STYLING */
        .user-info { 
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .user-name {
            font-size: 1.1rem;
            font-weight: bold;
            margin-right: 10px;
            color: var(--color-text-white);
            display: none; 
        }
        @media (min-width: 576px) {
            .user-name {
                display: block; 
            }
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
        .action-buttons {
            display: flex;
            flex-direction: column; /* Stack buttons vertically on small screens */
            gap: 5px;
            align-items: center;
        }
        @media (min-width: 992px) {
             .action-buttons {
                flex-direction: row; /* Horizontal layout on large screens */
            }
        }
        /* [UPDATED] LOGO STYLING */
        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px; 
        }
        .logo-img {
            width: 65px; /* Ukuran logo */
            height: 65px;
            border-radius: 50%;
            object-fit: cover; /* Penting untuk gambar */
            background-color: #b748f7ff; 
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
            {{-- MENU LEVEL ATAS --}}
            <a href="#"><i class="bi bi-list-task me-2"></i>MENU</a>
            <a href="{{ route('user.dashboard') ?? '#' }}" class="active-link"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            
            {{-- DROPDOWN DAFTAR SURAT (Menggunakan Bootstrap Collapse untuk tampilan sidebar yang stabil) --}}
            <div class="sidebar-dropdown-item">
                {{-- Toggle Link --}}
                <a class="sidebar-dropdown-toggle collapsed" id="daftarSuratDropdown" 
                    data-bs-toggle="collapse" href="#submenuDaftarSurat" role="button" aria-expanded="false" 
                    aria-controls="submenuDaftarSurat">
                    <i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT
                    <i class="bi bi-chevron-down" style="font-size: 1em;"></i>
                </a>

                {{-- Submenu yang akan di-collapse --}}
                <div class="collapse" id="submenuDaftarSurat">
                    <ul class="sidebar-dropdown-menu">
                        <li>
                            {{-- MENGGUNAKAN RUTE SPESIFIK --}}
                            <a href="{{ route('user.daftar_surat.masuk') ?? '#' }}">
                                <i class="bi bi-envelope me-2"></i>Surat Masuk
                            </a>
                        </li>
                        <li>
                            {{-- MENGGUNAKAN RUTE SPESIFIK --}}
                            <a href="{{ route('user.daftar_surat.keluar') ?? '#' }}">
                                <i class="bi bi-envelope-open me-2"></i>Surat Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Tautan KIRIM SURAT --}}
            <a href="{{ route('user.kirim_surat.index') ?? '#' }}"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">DASHBOARD USER</h2>
            
            <div class="dropdown">
                <div class="user-info dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        <span class="user-name d-none d-sm-block">{{ Auth::user()->name }}</span>
                        
                        <div class="profile-icon">
                            {{-- Menampilkan URL foto profil dari database atau placeholder ikon --}}
                            @if (Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="profile-img">
                            @else
                                <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                            @endif
                        </div>
                    @else
                        <span class="user-name d-none d-sm-block">Guest User</span>
                        <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                    @endauth
                    <i class="bi bi-chevron-down ms-2 fs-5 text-white"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-header">
                        @auth {{ Auth::user()->name }} @else Guest @endauth
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        {{-- FIX: Tautan ke halaman Edit Profil --}}
                        <a class="dropdown-item" href="{{ route('user.profile.edit') ?? '#' }}"><i class="bi bi-person-circle me-2"></i>User Profile</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li>
                        {{-- FIX: Tautan Surat dialihkan ke Daftar Surat Masuk (sebagai default) --}}
                        <a class="dropdown-item" href="{{ route('user.daftar_surat.masuk') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>Surat</a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        {{-- START: NOTIFIKASI SUKSES (Jika di-redirect dari halaman lain) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show w-100 mb-4" role="alert" style="color: var(--color-text-dark);">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- END: NOTIFIKASI SUKSES --}}

        {{-- CARD COUNT: Menampilkan data dari Controller --}}
        <div class="row g-4 mt-2">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card-box card-green">
                    <div>
                        <div class="text-uppercase" style="font-size: 0.9rem;">SURAT MASUK</div>
                        {{-- Data jumlah surat masuk dari Controller --}}
                        <div class="number">{{ $suratMasukCount ?? 0 }}</div>
                    </div>
                    <i class="bi bi-envelope-fill icon"></i>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card-box card-orange">
                    <div>
                        <div class="text-uppercase" style="font-size: 0.9rem;">SURAT KELUAR</div>
                        {{-- Data jumlah surat keluar dari Controller --}}
                        <div class="number">{{ $suratKeluarCount ?? 0 }}</div>
                    </div>
                    <i class="bi bi-envelope-open-fill icon"></i>
                </div>
            </div>
        </div>

        {{-- TABLE: SURAT MASUK (Ringkasan 10 Data Terbaru) --}}
        <div class="table-container mt-5">
            <div class="table-header">SURAT MASUK</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Kode Surat</th>
                            <th style="width: 30%;">Title</th>
                            <th style="width: 30%;">Isi</th>
                            <th style="width: 15%;">Lampiran</th> 
                            <th style="width: 5%;">Aksi</th> 
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOPING DATA SURAT MASUK dari Controller. --}}
                        @forelse ($suratMasuk ?? [] as $index => $surat)
                            <tr style="color: black;">
                                <td>{{ $index + 1 }}</td>
                                {{-- Menggunakan kode_surat dari Model/DB --}}
                                <td>{{ $surat->kode_surat ?? 'N/A' }}</td>
                                {{-- Menggunakan title dari Model/DB --}}
                                <td>{{ $surat->title ?? 'Judul Tidak Ada' }}</td>
                                {{-- Memotong isi surat agar tabel rapi --}}
                                <td>{{ Illuminate\Support\Str::limit($surat->isi ?? '', 50) }}</td>
                                
                                {{-- Kolom Lampiran (Menggunakan file_path) --}}
                                <td>
                                    @if (!empty($surat->file_path))
                                        <div class="action-buttons">
                                            
                                            {{-- 1. Tombol Lihat/View File --}}
                                            <a href="{{ route('surat.view_file', $surat->id) ?? '#' }}" class="btn btn-action btn-info" title="Lihat Lampiran" target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            {{-- 2. Tombol Download --}}
                                            <a href="{{ route('surat.download', $surat->id) ?? '#' }}" class="btn btn-action btn-success" title="Download Lampiran">
                                                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                            </a>

                                            {{-- 3. Tombol Print --}}
                                            <a href="{{ route('surat.view_file', $surat->id) ?? '#' }}" class="btn btn-action btn-warning" title="Cetak Lampiran" target="_blank" onclick="setTimeout(() => { window.open(this.href, '_blank', 'noopener,noreferrer').print(); }, 100); return false;">
                                                <i class="bi bi-printer-fill"></i>
                                            </a>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column align-items-center">
                                        {{-- Tombol Lihat Detail Surat --}}
                                        <button class="btn btn-action btn-primary" title="Lihat Detail Surat"
                                            onclick="window.location.href='{{ route('surat.view', $surat->id) }}'">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        {{-- Tombol Hapus --}}
                                        <button class="btn btn-action btn-danger mt-1" title="Hapus"
                                            onclick="confirmDelete('{{ $surat->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Form Hapus tersembunyi untuk method DELETE --}}
                                    <form id="delete-form-{{ $surat->id }}" method="POST" action="{{ route('surat.delete', $surat->id) }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr style="color: black;">
                                <td colspan="6" class="text-center">Tidak ada surat masuk yang ditemukan.</td> 
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /**
     * Mengkonfirmasi penghapusan dan submit form DELETE yang sesuai.
     */
    function confirmDelete(suratId) {
        // PERHATIAN: DIsarankan menggunakan modal kustom daripada confirm() di aplikasi nyata
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            document.getElementById('delete-form-' + suratId).submit();
        }
    }
</script>
</body>
</html>