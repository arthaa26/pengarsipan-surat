<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Daftar Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* BASE STYLES */
        :root {
            --color-bg-body: #4db8ff;
            --color-sidebar-primary: #0066cc;
            --color-sidebar-link: #0080ff;
            --color-sidebar-link-hover: #0059b3;
            --color-card-green: #71aeeeff;
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

        /* LAYOUT */
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
                onerror="this.onerror=null; this.src='https://placehold.co/40x40/f7c948/0066cc?text=M';"
            >
            <p class="logo-text">E-ARSIP</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            <a href="{{ route('admin.daftarsurat.index') ?? '#' }}" class="active-menu"><i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT</a>
            <a href="{{ route('admin.manajemenuser.index') ?? '#' }}"><i class="bi bi-person-fill-gear me-2"></i>MANAJEMEN USER</a>
        </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">HISTORY SURAT</h2> 
            
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

        {{-- TABLE: HISTORY SURAT --}}
        <div class="table-container mt-4">
            <div class="table-header">HISTORY SURAT</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">Id_Surat</th>
                            <th style="width: 20%;">Kode Surat</th>
                            <th style="width: 30%;">Title</th>
                            <th style="width: 25%;">Isi</th>
                            <th style="width: 10%; text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ⚠️ PENTING: Menggunakan data dummy sementara agar tombol muncul --}}
                        {{-- Ganti $suratHistory menjadi data dari Controller Anda yang sudah terkoneksi DB --}}
                        @php
                            $suratHistory = $suratHistory ?? collect([
                                (object)['id' => 1, 'kode_surat' => '01/SK-REKTOR/X/2025', 'title' => 'SURAT KEPUTUSAN REKTOR', 'isi' => 'Panitia Baitul Arqam'],
                                (object)['id' => 2, 'kode_surat' => '02/PROPOSAL/XI/2025', 'title' => 'Proposal Kegiatan', 'isi' => 'Permohonan dana untuk acara kampus'],
                            ]);
                        @endphp
                        
                        @forelse ($suratHistory as $index => $surat)
                            <tr style="color: black;">
                                <td>{{ $index + 1 }}</td> 
                                <td>{{ $surat->id ?? '...' }}</td>
                                <td>{{ $surat->kode_surat ?? '...' }}</td>
                                <td>{{ $surat->title ?? '...' }}</td>
                                <td>{{ Illuminate\Support\Str::limit($surat->isi, 40) ?? '...' }}</td>
                                
                                {{-- KOLOM TINDAKAN: LIHAT (Biru) dan HAPUS (Merah) --}}
                                <td class="text-center">
                                    <div class="action-buttons justify-content-center">
                                        
                                        {{-- 1. Tombol LIHAT DETAIL (Mata Biru) --}}
                                        <a href="{{ route('surat.show_detail', $surat->id) ?? '#' }}" 
                                           class="btn btn-action btn-primary" title="Lihat Detail Surat">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- 2. Tombol HAPUS (Sampah Merah) --}}
                                        <button class="btn btn-action btn-danger" title="Hapus Surat"
                                            onclick="confirmDelete('{{ $surat->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        
                                        {{-- Form Hapus tersembunyi untuk method DELETE --}}
                                        <form id="delete-form-{{ $surat->id }}" method="POST" action="{{ route('surat.delete', $surat->id) }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr style="color: black;">
                                <td colspan="6" class="text-center">Tidak ada history surat yang ditemukan.</td> 
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
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            document.getElementById('delete-form-' + suratId).submit();
        }
    }
</script>
</body>
</html>