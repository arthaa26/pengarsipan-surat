<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Daftar Surat Masuk</title>
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
        .app-layout { display: flex; min-height: 100vh; }
        .sidebar { background: var(--color-sidebar-primary); padding: 20px 10px; width: 250px; flex-shrink: 0; }

        /* DEFAULT MENU LINK STYLE */
        .sidebar-menu > a {
            display: flex; align-items: center; background: var(--color-sidebar-link);
            color: var(--color-text-white); text-decoration: none; margin: 8px 0;
            padding: 10px; border-radius: 5px; font-weight: bold; transition: background 0.2s;
        }
        .sidebar-menu > a:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-menu a.active-link { background: var(--color-text-white); color: var(--color-text-dark); }

        /* --- SIDEBAR DROPDOWN (COLLAPSE) STYLES --- */
        .sidebar-dropdown-item { margin: 8px 0; }
        .sidebar-dropdown-toggle {
            display: flex !important; align-items: center; justify-content: space-between;
            background: var(--color-sidebar-link); color: var(--color-text-white);
            text-decoration: none; padding: 10px; border-radius: 5px; font-weight: bold;
            transition: background 0.2s; cursor: pointer; width: 100%; text-align: left;
            border: none; line-height: 1.2;
        }
        .sidebar-dropdown-toggle:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-dropdown-toggle[aria-expanded="true"] { background: var(--color-sidebar-link-hover); border-radius: 5px 5px 0 0; }
        .sidebar-dropdown-toggle .bi-chevron-down { transition: transform 0.3s; }
        .sidebar-dropdown-toggle[aria-expanded="true"] .bi-chevron-down { transform: rotate(-180deg); }

        .sidebar-dropdown-menu {
            list-style: none; padding-left: 0; margin-bottom: 0; position: static;
            background-color: var(--color-sidebar-link-hover); border: none;
            padding: 0 10px 5px 10px; border-radius: 0 0 5px 5px; box-shadow: none; width: 100%; margin-top: 0;
        }
        .sidebar-dropdown-menu li a {
            display: flex; align-items: center; background: transparent !important;
            color: var(--color-text-white); font-weight: normal; padding: 8px 10px 8px 30px;
            margin: 2px 0; border-radius: 3px; text-decoration: none;
        }
        .sidebar-dropdown-menu li a:hover { background: var(--color-sidebar-primary) !important; color: var(--color-text-white) !important; }

        /* [BARU] ACTIVE SUBLINK STYLE untuk Surat Masuk */
        .sidebar-dropdown-menu li a.active-sublink-masuk {
            background: var(--color-sidebar-primary) !important;
            font-weight: bold;
        }
        /* --- END SIDEBAR DROPDOWN STYLES --- */

        .main-content-col { flex-grow: 1; padding: 20px; }
        .table-container { 
            background: var(--color-table-accent); border-radius: 10px; padding: 0; 
            overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }
        .table-header { 
            background: var(--color-table-accent); color: var(--color-text-white); 
            padding: 15px 20px; font-size: 1.2rem; font-weight: bold; 
        }
        .table th, .table td { color: var(--color-text-dark); padding: 15px 10px; vertical-align: middle; }
        .table thead tr { color: var(--color-text-white); font-weight: bold; background-color: var(--color-table-accent); }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: rgba(255, 255, 255, 0.7); }
        .table-striped > tbody > tr:nth-of-type(even) > * { background-color: rgba(255, 255, 255, 0.9); }
        .btn-action { 
            width: 35px; height: 35px; display: inline-flex; align-items: center; 
            justify-content: center; border-radius: 6px; padding: 0; margin: 2px 0; 
        }
        
        /* Profile Styles */
        .user-info { display: flex; align-items: center; cursor: pointer; }

        /* [BARU] Container untuk Nama dan Role/Fakultas */
        .user-identity {
            display: flex;
            flex-direction: column; 
            line-height: 1.2;
            margin-right: 10px;
            text-align: right; 
        }
        
        .user-name { font-size: 1.1rem; font-weight: bold; color: var(--color-text-white); display: none; }
        
        /* [BARU] Gaya untuk Role dan Fakultas */
        .user-role-display { 
            font-size: 0.9rem; 
            font-weight: normal; 
            color: rgba(255, 255, 255, 0.8); /* Agak redup */
            display: none; 
        }
        
        @media (min-width: 576px) { 
            .user-name, .user-role-display { display: block; } 
        }
        
        .profile-img { 
            width: 40px; height: 40px; border-radius: 50%; object-fit: cover; 
            background-color: var(--color-text-white); border: 2px solid var(--color-text-white); 
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem; 
            color: var(--color-sidebar-primary);
        }
        .action-buttons { display: flex; flex-direction: column; gap: 5px; align-items: center; }
        @media (min-width: 992px) { .action-buttons { flex-direction: row; } }
        /* Logo Styles */
        .sidebar-header { display: flex; align-items: center; margin-bottom: 20px; }
        .logo-img { width: 85px; height: 85px; border-radius: 50%; object-fit: cover; margin-right: 10px; display: block; }
        .logo-text { font-size: 1.4rem; font-weight: bold; color: var(--color-text-white); margin: 0; }
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
            <a href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>

            {{-- DROPDOWN DAFTAR SURAT --}}
            <div class="sidebar-dropdown-item">
                <a class="sidebar-dropdown-toggle" id="daftarSuratDropdown"
                   data-bs-toggle="collapse" href="#submenuDaftarSurat" role="button" aria-expanded="true"
                   aria-controls="submenuDaftarSurat">
                    <i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT
                    <i class="bi bi-chevron-down" style="font-size: 1em;"></i>
                </a>

                {{-- Submenu dibuka secara default untuk halaman ini --}}
                <div class="collapse show" id="submenuDaftarSurat">
                    <ul class="sidebar-dropdown-menu">
                        <li>
                            {{-- Tautan aktif untuk halaman ini --}}
                            <a href="{{ route('user.daftar_surat.masuk') ?? '#' }}" class="active-sublink-masuk">
                                <i class="bi bi-envelope me-2"></i>Surat Masuk
                            </a>
                        </li>
                        <li>
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
            <h2 class="fw-bold text-white">DAFTAR SURAT MASUK</h2>

            <div class="dropdown">
                <div class="user-info dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        @php
                            // LOGIKA PHP UNTUK MENAMPILKAN ROLE & FAKULTAS
                            // Catatan: Pastikan di Controller Anda memuat relasi 'role' dan 'faculty' (contoh: Auth::user()->load(['role', 'faculty']))
                            $roleName = Auth::user()->role->name ?? 'N/A';
                            // Mengakses code Fakultas (Jika relasi faculty ada)
                            $facultyCode = Auth::user()->faculty->code ?? '';
                            
                            $displayRole = ucwords(str_replace('_', ' ', $roleName));
                            // Format: (ROLE KODEFACULTY) atau (ROLE)
                            $fullTitle = trim($facultyCode) ? "({$displayRole} {$facultyCode})" : "({$displayRole})";
                        @endphp

                        {{-- CONTAINER NAMA & ROLE/FAKULTAS --}}
                        <div class="user-identity">
                            <span class="user-name d-none d-sm-block">{{ Auth::user()->name }}</span>
                            {{-- Tampilkan role dan fakultas --}}
                            <span class="user-role-display d-none d-sm-block">{{ $fullTitle }}</span> 
                        </div>

                        <div class="profile-icon">
                            @if (Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="profile-img">
                            @else
                                <div class="profile-img"><i class="bi bi-person-circle"></i></div>
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
                        @auth 
                            {{ Auth::user()->name }} <br>
                            {{-- Tampilkan role dan fakultas di header dropdown --}}
                            <small class="text-muted">{{ $fullTitle }}</small> 
                        @else 
                            Guest 
                        @endauth
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('user.profile.edit') ?? '#' }}"><i class="bi bi-person-circle me-2"></i>User Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.daftar_surat.masuk') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>Surat</a></li>
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

        {{-- START: NOTIFIKASI SUKSES --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show w-100 mb-4" role="alert" style="color: var(--color-text-dark);">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- END: NOTIFIKASI SUKSES --}}

        {{-- TABLE: SURAT MASUK --}}
        {{-- Mengambil data dari variabel $suratList yang dikirim oleh UsersController@daftarSuratMasuk --}}
        <div class="table-container mt-5">
            <div class="table-header">SURAT MASUK (Total: {{ $suratList->total() ?? 0 }})</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            {{-- [BARU] Tambah kolom Tanggal Masuk --}}
                            <th style="width: 15%;">Tgl. Masuk</th> 
                            <th style="width: 15%;">Kode Surat</th>
                            <th style="width: 25%;">Title</th>
                            <th style="width: 25%;">Isi</th>
                            <th style="width: 10%;">Lampiran</th> 
                            <th style="width: 5%;">Aksi</th> 
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOPING DATA SURAT MASUK dari Controller. --}}
                        @forelse ($suratList ?? [] as $index => $surat)
                            <tr style="color: black;">
                                {{-- Penomoran yang benar dengan pagination --}}
                                <td>{{ ($suratList->firstItem() ?? 0) + $index }}</td>
                                {{-- [BARU] Tampilkan Tanggal Masuk (asumsi kolom 'created_at' ada dan berupa instance Carbon) --}}
                                <td>{{ \Carbon\Carbon::parse($surat->created_at)->format('d M y H:i') }}</td>
                                <td>{{ $surat->kode_surat ?? 'N/A' }}</td>
                                <td>{{ $surat->title ?? 'Judul Tidak Ada' }}</td>
                                <td>{{ Illuminate\Support\Str::limit($surat->isi ?? '', 50) }}</td>

                                {{-- Kolom Lampiran (Menggunakan file_path) --}}
                                <td>
                                    @if (!empty($surat->file_path))
                                        <div class="action-buttons">
                                            <a href="{{ route('surat.view_file', $surat->id) ?? '#' }}" class="btn btn-action btn-info" title="Lihat Lampiran" target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('surat.download', $surat->id) ?? '#' }}" class="btn btn-action btn-success" title="Download Lampiran">
                                                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                            </a>
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
                                        <button class="btn btn-action btn-danger mt-1" title="Hapus"
                                            onclick="confirmDelete('{{ $surat->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $surat->id }}" method="POST" action="{{ route('surat.delete', $surat->id) }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr style="color: black;">
                                <td colspan="7" class="text-center">Tidak ada surat masuk yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Link Pagination --}}
            <div class="d-flex justify-content-center p-3">
                {{ $suratList->links() ?? '' }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /**
     * Konfir hapus & submit form DELETE yang sesuai.
     */
    function confirmDelete(suratId) {
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            document.getElementById('delete-form-' + suratId).submit();
        }
    }

    // Menangani rotasi ikon panah saat dropdown dibuka/ditutup
    document.addEventListener('DOMContentLoaded', function () {
        const collapseElement = document.getElementById('submenuDaftarSurat');
        const toggleButton = document.getElementById('daftarSuratDropdown');
        const chevronIcon = toggleButton ? toggleButton.querySelector('.bi-chevron-down') : null;


        if (collapseElement && toggleButton && chevronIcon) {
            // Inisialisasi: Pastikan panah menghadap ke atas karena submenu 'show'
            if (collapseElement.classList.contains('show')) {
                 chevronIcon.style.transform = 'rotate(-180deg)';
            }
            
            collapseElement.addEventListener('show.bs.collapse', function () {
                toggleButton.setAttribute('aria-expanded', 'true');
                chevronIcon.style.transform = 'rotate(-180deg)';
            });
            collapseElement.addEventListener('hide.bs.collapse', function () {
                toggleButton.setAttribute('aria-expanded', 'false');
                chevronIcon.style.transform = 'rotate(0deg)';
            });
        }
    });
</script>
</body>
</html>