<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
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
        
        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;    
        }
        .logo-img {
            width: 85px;
            height: 85px;
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
        /* CARD STYLES */
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
        .card-box .number {
            font-size: 2.5rem;
            line-height: 1;
        }
        .card-box .icon {
            font-size: 2.5rem;
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
        .table th, .table td {
            color: var(--color-text-dark); 
            padding: 15px 10px;
            vertical-align: middle;
            border: none;
        }
        .table thead tr th {
            color: var(--color-text-dark);
            font-weight: bold;
            background-color: var(--color-table-accent);
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.7);
        }
        .table-striped > tbody > tr:nth-of-type(even) > * {
            background-color: rgba(255, 255, 255, 0.9);
        }
        /* ACTION BUTTONS */
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            padding: 0;
            margin: 2px 0;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
            justify-content: center; 
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
            text-align: right; 
        }
        .user-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--color-text-white);
            display: none; 
        }
        .user-role-display {
            font-size: 0.85rem; 
            font-weight: normal; 
            color: rgba(255, 255, 255, 0.8);
            display: none;
        }
        @media (min-width: 576px) {
            .user-name, .user-role-display {
                display: block; 
            }
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
    </style>

</head>
<body>

<div class="app-layout">
    <div class="sidebar">
        {{-- LOGO DI KIRI ATAS --}}
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
            {{-- MENU ADMIN TINGKAT ATAS (DASHBOARD ACTIVE) --}}
            <a href="{{ route('admin.dashboard') ?? '#' }}" class="active-menu"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            
            {{-- DAFTAR SURAT --}}
            <a href="{{ route('admin.daftarsurat.index') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT</a>
            
            {{-- MANAJEMEN USER --}}
            <a href="{{ route('admin.manajemenuser.index') ?? '#' }}"><i class="bi bi-person-fill-gear me-2"></i>MANAJEMEN USER</a>
            </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">DASHBOARD ADMIN</h2> 
            
            <div class="dropdown">
                <div class="user-info dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        @php
                            // LOGIKA ROLE & FAKULTAS
                            $roleName = Auth::user()->role->name ?? 'Admin';
                            $facultyCode = Auth::user()->faculty->code ?? 'Pusat';
                            $displayRole = ucwords(str_replace('_', ' ', $roleName));
                            $fullTitle = trim($facultyCode) ? "({$displayRole} {$facultyCode})" : "({$displayRole})";
                        @endphp
                        
                        {{-- FIX: Nama dan Role di Kiri --}}
                        <div class="user-identity">
                            <span class="user-name d-none d-sm-block">{{ Auth::user()->name }}</span>
                            <span class="user-role-display d-none d-sm-block">{{ $fullTitle }}</span> 
                        </div>
                        
                        {{-- IKON PROFIL di Kanan --}}
                        <div class="profile-icon">
                            @if (Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="profile-img">
                            @else
                                <div class="profile-img"><i class="bi bi-person-circle"></i></div>
                            @endif
                        </div>
                    @else
                        <div class="user-identity">
                            <span class="user-name d-none d-sm-block">Guest Admin</span>
                        </div>
                        <div class="profile-icon">
                            <div class="profile-img"><i class="bi bi-person-circle"></i></div>
                        </div>
                    @endauth
                    <i class="bi bi-chevron-down ms-2 fs-5 text-white"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-header">
                        @auth 
                            {{ Auth::user()->name }} <br>
                            <small class="text-muted">{{ $fullTitle }}</small>
                        @else 
                            Admin 
                        @endauth
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    {{-- Tambahkan tautan navigasi penting --}}
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.daftarsurat.index') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>Daftar Surat</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.manajemenuser.index') ?? '#' }}"><i class="bi bi-person-fill-gear me-2"></i>Manajemen User</a></li>
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

        {{-- CARD COUNT: Menampilkan total semua surat --}}
        <div class="row g-4 mt-2 justify-content-center">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card-box card-green justify-content-center text-center py-4 px-5">
                    <div>
                        <div class="text-uppercase" style="font-size: 1.2rem;">JUMLAH SURAT</div>
                        {{-- Menggunakan $totalSuratCount dari Controller --}}
                        <div class="number my-1">{{ $totalSuratCount ?? 0 }}</div>
                    </div>
                    <i class="bi bi-envelope-fill icon ms-3"></i>
                </div>
            </div>
        </div>

        {{-- TABLE: HISTORY SURAT (Menampilkan 10 surat terbaru) --}}
        <div class="table-container mt-5">
            <div class="table-header">10 SURAT TERAKHIR</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            {{-- KOLOM BARU --}}
                            <th style="width: 15%;">Tgl. Kirim</th> 
                            <th style="width: 20%;">Pengirim</th>
                            <th style="width: 20%;">Fakultas</th>
                            <th style="width: 25%;">Title</th>
                            <th style="width: 15%; text-align: center;">Aksi</th> 
                            {{-- KOLOM LAMA DIHAPUS: Id_Surat, Kode Surat, Isi --}}
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Variabel diubah ke $recentSurat untuk konteks Dashboard (Asumsi: Controller menyediakan $recentSurat) --}}
                        @forelse ($suratMasuk ?? [] as $index => $surat)
                            <tr style="color: black;">
                                <td>{{ $index + 1 }}</td>
                                {{-- Tgl Kirim --}}
                                <td>{{ \Carbon\Carbon::parse($surat->created_at)->format('d/m/y H:i') }}</td>
                                
                                {{-- DATA PENGIRIM (user1) --}}
                                <td>{{ $surat->user1->name ?? 'N/A' }}</td>
                                
                                {{-- FAKULTAS PENGIRIM (user1->faculty) --}}
                                <td>{{ $surat->user1->faculty->name ?? '-' }}</td>
                                
                                <td>{{ Illuminate\Support\Str::limit($surat->title ?? 'Judul Tidak Ada', 25) }}</td>
                                
                                {{-- KOLOM TINDAKAN (MATA dan SAMPAH) --}}
                                <td class="text-center">
                                    <div class="action-buttons justify-content-center">
                                        {{-- Tombol Lihat Detail Surat (Menggunakan Rute Admin yang benar) --}}
                                        <button class="btn btn-action btn-primary" title="Lihat Detail"
                                            onclick="window.location.href='{{ route('admin.surat.view', $surat->id) ?? '#' }}'">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        {{-- Tombol Hapus (Menggunakan Rute Admin yang benar) --}}
                                        <button class="btn btn-action btn-danger" title="Hapus"
                                            onclick="confirmDelete('{{ $surat->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Form Hapus tersembunyi (Rute Admin) --}}
                                    <form id="delete-form-{{ $surat->id }}" method="POST" action="{{ route('admin.surat.delete', $surat->id) ?? '#' }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr style="color: black;">
                                <td colspan="6" class="text-center">Tidak ada surat terbaru yang ditemukan.</td> 
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- START: FOOTER HAK CIPTA --}}
        @include('partials.footer')
        {{-- END: FOOTER HAK CIPTA --}}
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/**
* Mengkonfirmasi penghapusan dan submit form DELETE 
*/
function confirmDelete(suratId) {
    if (confirm("Apakah Anda yakin ingin menghapus surat ini secara permanen?")) { 
        document.getElementById('delete-form-' + suratId).submit();
    }
}
</script>

</body>
</html>
