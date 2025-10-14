<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat - {{ $surat->kode_surat ?? 'N/A' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* BASE STYLES */
        :root {
            --color-bg-body: #4db8ff;
            --color-sidebar-primary: #0066cc;
            --color-sidebar-link: #0080ff;
            --color-sidebar-link-hover: #fff;
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
            padding: 20px 0;
            width: 250px; 
            flex-shrink: 0;
        }
        .sidebar-menu a {
            display: flex; 
            align-items: center;
            background: var(--color-sidebar-link);
            color: var(--color-text-white);
            text-decoration: none;
            margin: 8px 10px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.2s;
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
            padding: 0 10px;
        }
        .logo-img {
            width: 65px; 
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
        
            margin-right: 10px;
            display: block;
        }
        .logo-text {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--color-text-white);
            margin: 0;
        }

        /* Style Card Detail */
        .card-detail {
            background-color: var(--color-text-white);
            color: var(--color-text-dark);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        .card-header-custom {
            background-color: var(--color-sidebar-primary);
            color: var(--color-text-white);
            padding: 15px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .preview-box { 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            overflow: hidden; 
            margin-top: 15px;
        }
        /* Style untuk tombol */
        .btn-custom-back {
             background-color: var(--color-sidebar-link-hover);
             border: none;
             color: var(--color-text-dark);
        }
        .btn-custom-print {
            background-color: var(--color-sidebar-primary);
            border: none;
        }
    </style>
</head>
<body>

<div class="app-layout">
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <img 
                src="/images/unmuh.png" 
                alt="Logo Muhammadiyah" 
                class="logo-img" 
                title="Logo Muhammadiyah"
                onerror="this.onerror=null; this.src='https://placehold.co/65x65/0066cc/ffffff?text=E-ARSIP';"
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
        <h2 class="fw-bold text-white mb-4">DETAIL SURAT</h2> 
        
        <a href="{{ route('admin.daftarsurat.index') }}" class="btn btn-primary mb-3 btn-custom-back">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke History
        </a>

        <div class="card-detail">
            <div class="card-header-custom">
                Informasi Detail Surat: {{ $surat->kode_surat ?? 'N/A' }}
            </div>
            <div class="card-body p-4">
                
                {{-- Bagian Detail Data Surat --}}
                <div class="row detail-info mb-4">
                    <div class="col-md-6">
                        <p><strong>ID Surat:</strong> {{ $surat->id ?? '-' }}</p>
                        <p><strong>Kode Surat:</strong> {{ $surat->kode_surat ?? '-' }}</p>
                        <p><strong>Judul:</strong> {{ $surat->title ?? '-' }}</p>
                        <p><strong>Tujuan:</strong> {{ $surat->tujuan ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Dibuat:</strong> {{ $surat->created_at ? $surat->created_at->format('d M Y H:i') : '-' }}</p>
                        {{-- FIX: Menggunakan operator nullsafe (?->) untuk mencegah Fatal Error (layar biru) --}}
                        <p><strong>Pengirim (ID 1):</strong> {{ $surat->user1?->name ?? $surat->user_id_1 ?? '-' }}</p> 
                        <p><strong>Penerima (ID 2):</strong> {{ $surat->user2?->name ?? $surat->user_id_2 ?? '-' }}</p>
                    </div>
                    <div class="col-12 mt-3">
                        <p><strong>Isi Surat:</strong></p>
                        <div class="p-3 border rounded" style="background-color: #f7f7f7; color: var(--color-text-dark);">
                            {{ $surat->isi ?? 'Isi surat tidak tersedia.' }}
                        </div>
                    </div>
                </div>

                <hr class="mt-4 mb-4">

                {{-- Bagian Tindakan (Lihat, Cetak, Simpan) --}}
                <h5 class="mb-3" style="color: var(--color-sidebar-primary);">Tindakan File Lampiran</h5>
                
                <div class="d-flex justify-content-start mb-3 gap-2">
                    
                    @if ($surat->file_path)
                    
                    {{-- Tombol Lihat Surat (Membuka di tab baru) - RUTE SUDAH DIKOREKSI --}}
                    <a href="{{ route('admin.surat.preview', $surat->id) }}" target="_blank" class="btn btn-primary" style="background-color: var(--color-sidebar-link); border: none;">
                        <i class="bi bi-eye me-2"></i>Lihat Surat
                    </a>

                    {{-- Tombol Simpan/Download Surat --}}
                    <a href="{{ route('admin.surat.download', $surat->id) }}" class="btn btn-success">
                        <i class="bi bi-download me-2"></i>Simpan Surat
                    </a>

                    {{-- Tombol Cetak (Hanya untuk PDF yang di-preview di iframe ini) --}}
                    @if (strtolower(pathinfo($surat->file_path, PATHINFO_EXTENSION)) === 'pdf')
                    <button class="btn btn-custom-print text-white" onclick="printPreview()">
                        <i class="bi bi-printer me-2"></i>Cetak
                    </button>
                    @endif
                    
                    @else
                    <div class="alert alert-secondary mb-0" style="color: var(--color-text-dark);">
                        Tidak ada file lampiran yang tersedia.
                    </div>
                    @endif
                </div>

                <hr class="mt-4 mb-4">

                {{-- Preview File (Hanya untuk PDF, di embed langsung di halaman) --}}
                @if ($surat->file_path && strtolower(pathinfo($surat->file_path, PATHINFO_EXTENSION)) === 'pdf')
                    <h5 class="mb-3" style="color: var(--color-sidebar-primary);">Pratinjau Langsung</h5>
                    <div class="preview-box">
                        {{-- Kunci: Iframe memuat URL dari Controller::previewFile() --}}
                        <iframe 
                            id="filePreviewFrame"
                            src="{{ route('admin.surat.preview', $surat->id) }}" 
                            style="width: 100%; height: 700px; border: none; background-color: white;">
                        </iframe>
                    </div>
                @elseif ($surat->file_path)
                    <div class="alert alert-warning" style="color: var(--color-text-dark);">
                        Format file (*.{{ pathinfo($surat->file_path, PATHINFO_EXTENSION) }}) tidak didukung untuk tampilan pratinjau langsung. Silakan gunakan tombol **Lihat Surat** untuk membukanya.
                    </div>
                @endif

            </div>
        </div>
        {{-- START: FOOTER HAK CIPTA --}}
        @include('partials.footer')
        {{-- END: FOOTER HAK CIPTA --}}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function printPreview() {
        const iframe = document.getElementById('filePreviewFrame');
        if (iframe && iframe.contentWindow) {
            try {
                iframe.contentWindow.print();
            } catch