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

        .app-layout { display: flex; min-height: 100vh; }
        .sidebar { background: var(--color-sidebar-primary); padding: 20px 10px; width: 250px; flex-shrink: 0; }

        .sidebar-menu > a {
            display: flex; align-items: center; background: var(--color-sidebar-link);
            color: var(--color-text-white); text-decoration: none; margin: 8px 0;
            padding: 10px; border-radius: 5px; font-weight: bold; transition: background 0.2s;
        }
        .sidebar-menu > a:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-menu a.active-link { background: var(--color-text-white); color: var(--color-text-dark); }

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

        .sidebar-dropdown-menu li a.active-sublink-masuk {
            background: var(--color-sidebar-primary) !important;
            font-weight: bold;
        }
        .main-content-col { flex-grow: 1; padding: 20px; }
        .table-container { 
            background: var(--color-table-accent); border-radius: 10px; padding: 0; 
            overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            overflow-x: auto; 
        }
        .table-header { 
            background: var(--color-table-accent); color: var(--color-text-dark); /* Mengubah header text agar kontras */
            padding: 15px 20px; font-size: 1.2rem; font-weight: bold; 
        }
        .table th, .table td { color: var(--color-text-dark); padding: 12px 10px; vertical-align: middle; border: none; }
        .table thead tr th { color: var(--color-text-dark); font-weight: bold; background-color: var(--color-table-accent); border-bottom: 2px solid rgba(0, 0, 0, 0.1); }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: rgba(255, 255, 255, 0.9); }
        .table-striped > tbody > tr:nth-of-type(even) > * { background-color: #f8f9fa; }
        .table-striped > tbody > tr:hover > * { background-color: #e9ecef; }
        
        .btn-action {
            width: 30px; height: 30px; display: inline-flex; align-items: center;
            justify-content: center; border-radius: 5px; padding: 0; margin: 2px 0; 
        }
        .action-buttons { 
            display: flex; 
            flex-direction: row !important; 
            gap: 5px; 
            align-items: center;
            justify-content: center;
        }
        
        /* START: PERBAIKAN USER DROPDOWN */
        .user-info { 
            display: flex; 
            align-items: center; 
            cursor: pointer; 
        }

        .user-identity {
            display: flex; 
            flex-direction: column; 
            line-height: 1.2; 
            margin-left: 0;
            margin-right: 10px; 
            text-align: right; 
            order: -1; 
        }
        .profile-icon {
            order: 0;
        }
        .profile-img { 
            width: 40px; height: 40px; border-radius: 50%; object-fit: cover; 
            background-color: var(--color-text-white); border: 2px solid var(--color-text-white); 
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem; 
            color: var(--color-sidebar-primary);
        }
        .user-info .dropdown-toggle::after {
            /* Hapus segitiga default Bootstrap */
            display: none; 
        }
        /* Tambahkan Ikon Chevron untuk Dropdown */
        .user-info .bi-chevron-down-profile {
            order: 1; /* Urutan terakhir */
            margin-left: 5px;
            font-size: 0.8rem;
        }


        .user-name { font-size: 1.1rem; font-weight: bold; color: var(--color-text-white); display: none; }
        .user-role-display { 
            font-size: 0.9rem; font-weight: normal; color: rgba(255, 255, 255, 0.8); display: none; 
        }
        @media (min-width: 576px) { 
            .user-name, .user-role-display { display: block; } 
        }
        /* END: PERBAIKAN USER DROPDOWN */
        
        .sidebar-header { 
            display: flex; 
            align-items: center; 
            margin-bottom: 20px; 
        }
        .logo-img { width: 85px; height: 85px; border-radius: 50%; object-fit: cover; margin-right: 10px; display: block; }
        .logo-text { font-size: 1.4rem; font-weight: bold; color: var(--color-text-white); margin: 0; }

        @media (max-width: 768px) {
            .app-layout { flex-direction: column; }
            .sidebar { width: 100%; position: static; padding: 15px 10px; }
            .sidebar-header { justify-content: center; }
            .sidebar-menu { display: flex; flex-wrap: wrap; justify-content: space-around; gap: 5px; }
            .sidebar-menu > a, .sidebar-dropdown-item { flex-basis: 48%; }
        }

        /* Gaya Khusus untuk Aksi */
        .action-column {
            display: flex;
            flex-direction: column; 
            gap: 5px;
            align-items: center;
            justify-content: center;
        }
        /* Mengatur agar konten di kolom title dan isi tidak terpotong (word-wrap) */
        .table td { 
            white-space: normal; 
            max-width: 250px; /* Batasi lebar agar tidak terlalu panjang */
            word-wrap: break-word;
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
                onerror="this.onerror=null; this.src='https://placehold.co/85x85/f7c948/0066cc?text=M';"
            >
            <p class="logo-text">E-ARSIP</p>
        </div>

        <div class="sidebar-menu">
            <a href="#"><i class="bi bi-list-task me-2"></i>MENU</a>
            <a href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>

            <div class="sidebar-dropdown-item">
                <a class="sidebar-dropdown-toggle" id="daftarSuratDropdown"
                    data-bs-toggle="collapse" href="#submenuDaftarSurat" role="button" aria-expanded="true"
                    aria-controls="submenuDaftarSurat">
                    <i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT
                    <i class="bi bi-chevron-down" style="font-size: 1em;"></i>
                </a>

                <div class="collapse show" id="submenuDaftarSurat">
                    <ul class="sidebar-dropdown-menu">
                        <li>
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
                            $roleName = Auth::user()->role->name ?? 'N/A';
                            $facultyCode = Auth::user()->faculty->code ?? '';
                            $displayRole = ucwords(str_replace('_', ' ', $roleName));
                            $fullTitle = trim($facultyCode) ? "({$displayRole} {$facultyCode})" : "({$displayRole})";
                        @endphp
                        
                        <div class="user-identity">
                            <span class="user-name d-none d-sm-block">{{ Auth::user()->name }}</span>
                            <span class="user-role-display d-none d-sm-block">{{ $fullTitle }}</span> 
                        </div>

                        <div class="profile-icon">
                            @if (Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="profile-img">
                            @else
                                <div class="profile-img"><i class="bi bi-person-circle"></i></div>
                            @endif
                        </div>
                        <i class="bi bi-chevron-down-profile"></i>
                    @else
                        <span class="user-name d-none d-sm-block">Guest User</span>
                        <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                        <i class="bi bi-chevron-down-profile"></i>
                    @endauth
                </div>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-header">
                        @auth 
                            {{ Auth::user()->name }} <br>
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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show w-100 mb-4" role="alert" style="color: var(--color-text-dark);">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table-container mt-5">
            <div class="table-header">SURAT MASUK (Total: {{ $suratList->total() ?? 0 }})</div>
<div class="table-responsive">
    <table class="table table-striped table-hover mt-0">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 8%;">Tgl. Masuk</th> 
                <th style="width: 12%;">Pengirim</th>   
                <th style="width: 8%;">Fakultas</th>   
                <th style="width: 7%;">Kode Surat</th>
                <th style="width: 15%;">Title</th> 
                <th style="width: 25%;">Isi Surat (Ringkasan)</th> 
                <th style="width: 10%;">Lampiran</th>   
                <th style="width: 10%;">Aksi</th> 
            </tr>
        </thead>
        <tbody>
            @forelse ($suratList ?? [] as $index => $surat)
                <tr style="color: black;">
                <td>{{ ($suratList->firstItem() ?? 0) + $index }}</td>
                <td>{{ \Carbon\Carbon::parse($surat->created_at)->format('d/m/y H:i') }}</td>
                
                <td>{{ $surat->user1->name ?? 'N/A' }}</td>
                
                <td>
                        {{ $surat->user1->faculty->name ?? '-' }}
                </td>
                
                <td>{{ $surat->kode_surat ?? 'N/A' }}</td>
                <td>{{ Illuminate\Support\Str::limit($surat->title ?? 'Judul Tidak Ada', 20) }}</td>

                <td>{{ Illuminate\Support\Str::limit($surat->isi ?? 'Tidak ada ringkasan isi.', 35) }}</td>
                
                {{-- START: PERUBAHAN DI SINI UNTUK KOLOM LAMPIRAN --}}
                <td>
                        <div class="action-buttons justify-content-center">
                        {{-- TOMBOL LIHAT DETAIL (selalu ada) --}}
                        <a href="{{ route('surat.show', $surat->id) ?? '#' }}" class="btn btn-action btn-info" title="Lihat Detail Surat">
                                <i class="bi bi-eye"></i>
                        </a>

                        @if (!empty($surat->file_path))
                                {{-- TOMBOL DOWNLOAD (hanya jika ada file) --}}
                                <a href="{{ route('surat.download', $surat->id) ?? '#' }}" class="btn btn-action btn-success" title="Download Lampiran">
                                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                </a>
                                
                                {{-- TOMBOL CETAK/VIEW FILE (hanya jika ada file) --}}
                                <a href="{{ route('surat.view_file', $surat->id) ?? '#' }}" class="btn btn-action btn-warning" title="Cetak Lampiran" target="_blank" onclick="setTimeout(() => { window.open(this.href, '_blank', 'noopener,noreferrer').print(); }, 100); return false;">
                                <i class="bi bi-printer-fill"></i>
                                </a>
                        @else
                                {{-- Teks atau ikon pengganti jika tidak ada lampiran selain tombol Lihat Detail --}}
                                <span class="text-muted" title="Tidak ada lampiran">-</span>
                        @endif
                        </div>
                </td>
                {{-- END: PERUBAHAN DI SINI --}}

                <td>
                        <div class="action-column">
                        {{-- TOMBOL BALAS --}}
                        <a href="{{ route('DaftarSurat.reply', $surat->id) ?? '#' }}" class="btn btn-action btn-primary" title="Balas Surat">
                                <i class="bi bi-reply-fill"></i>
                        </a>
                        
                        {{-- TOMBOL HAPUS --}}
                        <button class="btn btn-action btn-danger" title="Hapus"
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
                    <td colspan="10" class="text-center">Tidak ada surat masuk yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
            
            <div class="d-flex justify-content-center p-3">
                @if(is_object($suratList) && method_exists($suratList, 'links'))
                    {{ $suratList->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
        @include('partials.footer')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /**
     * Konfir hapus & submit form DELETE yang sesuai.
     */
    function confirmDelete(suratId) {
        // PERBAIKAN: Gunakan custom modal daripada alert/confirm
        // Catatan: Karena custom modal tidak disertakan, saya pertahankan confirm() 
        // yang disarankan Laravel untuk environment non-iframe.
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            document.getElementById('delete-form-' + suratId).submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const collapseElement = document.getElementById('submenuDaftarSurat');
        const toggleButton = document.getElementById('daftarSuratDropdown');
        const chevronIcon = toggleButton ? toggleButton.querySelector('.bi-chevron-down') : null;


        if (collapseElement && toggleButton && chevronIcon) {
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