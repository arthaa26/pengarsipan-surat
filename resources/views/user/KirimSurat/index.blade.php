<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Kirim Surat</title>
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
            /* Custom Color for the Kirim Surat form background */
            --color-kirim-surat-bg: #f7c948; /* Matches the yellow/orange */
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
        .sidebar-menu a:hover {
            background: var(--color-sidebar-link-hover);
        }
        /* Highlight the active link for 'KIRIM SURAT' as per the image */
        .sidebar-menu a[href*="kirim_surat"] {
            background: var(--color-text-white); /* White background for active link */
            color: var(--color-text-dark); /* Dark text for active link */
        }

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
            background-color: #b748f7ff; /* Warna latar belakang logo jika gambar gagal dimuat */
            margin-right: 10px;
            display: block; /* Agar img bisa diatur dimensinya */
            border: 2px solid var(--color-text-white);
        }
        .logo-text {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--color-text-white);
            margin: 0;
        }

        /* CUSTOM FORM STYLING for Kirim Surat */
        .kirim-surat-panel {
            background-color: var(--color-kirim-surat-bg); /* Yellow background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }
        .kirim-surat-panel h4 {
            color: var(--color-text-dark);
            font-weight: bold;
            margin-bottom: 25px;
            border-bottom: 3px solid var(--color-text-dark);
            padding-bottom: 10px;
            display: inline-block;
        }
        .form-control-custom {
            height: 50px; /* Adjust height to match the image */
            border-radius: 10px;
            border: 2px solid var(--color-text-dark);
            font-weight: bold;
            font-size: 1rem;
            box-shadow: none !important; /* Remove default bootstrap shadow on focus */
        }
        /* Style for disabled/readonly fields */
        .form-control-custom:disabled, .form-control-custom[readonly] {
            background-color: #fff; /* White background for disabled field */
            color: var(--color-sidebar-primary); /* Use a distinct color for the auto-generated code */
            opacity: 1; /* Ensure text is fully visible */
        }
        .form-label-custom {
            color: var(--color-text-dark);
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        .radio-label-custom {
            color: var(--color-text-dark);
            font-weight: bold;
            font-size: 1.1rem;
            margin-right: 20px;
        }
        .form-check-input:checked {
            background-color: var(--color-sidebar-primary); 
            border-color: var(--color-sidebar-primary);
        }
        .form-check-input {
            width: 1.3em;
            height: 1.3em;
        }
        /* Style for the Upload File input to look like the image */
        .input-group-upload .form-control-custom {
            border-right: none;
            flex-grow: 1;
        }
        .input-group-upload .input-group-text {
            background-color: var(--color-text-white);
            color: var(--color-text-dark);
            border: 2px solid var(--color-text-dark);
            border-left: none;
            height: 50px;
            border-radius: 0 10px 10px 0;
            font-size: 1.5rem;
            padding: 0 15px;
        }
        .btn-submit-custom {
            background-color: var(--color-sidebar-primary);
            color: var(--color-text-white);
            font-weight: bold;
            padding: 10px 30px;
            border-radius: 8px;
            border: none;
            margin-top: 20px;
            font-size: 1.2rem;
            transition: background-color 0.2s;
        }
        .btn-submit-custom:hover {
            background-color: var(--color-sidebar-link-hover);
        }
        .radio-group-container {
            margin-top: 15px;
            margin-bottom: 25px;
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
            {{-- Menggunakan rute yang didefinisikan --}}
            <a href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            <a href="#"><i class="bi bi-list-task me-2"></i>MENU</a>
            <a href="{{ route('user.daftar_surat.index') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT</a>
            <a href="{{ route('user.kirim_surat.index') ?? '#' }}"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">KIRIM SURAT</h2>
            
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
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person-circle me-2"></i>User Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.daftar_surat.index') ?? '#' }}"><i class="bi bi-folder-fill me-2"></i>Surat</a></li>
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

{{-- START: KIRIM SURAT FORM CONTENT --}}
        <div class="kirim-surat-panel">
            <h4 class="text-uppercase">Kirim Surat</h4>
            
            {{-- The form itself --}}
            <form action="{{ route('user.kirim_surat.store') ?? '#' }}" method="POST" enctype="multipart/form-data">
                @csrf 
                
                {{-- KODE SURAT Field (AUTOMATICALLY GENERATED) --}}
                <div class="mb-4">
                    <label for="kode_surat" class="form-label-custom">KODE SURAT (Otomatis)</label>
                    <input 
                        type="text" 
                        class="form-control form-control-custom" 
                        id="kode_surat" 
                        value="{{ $nextKode ?? 'Kode Akan Dibuat Otomatis' }}"
                        placeholder="Kode akan di-generate oleh sistem" 
                        readonly 
                        disabled 
                    >
                </div>
                
                {{-- TITLE Field --}}
                <div class="mb-4">
                    <label for="title" class="form-label-custom">TITLE</label>
                    <input type="text" class="form-control form-control-custom" id="title" name="title" placeholder="Masukkan Judul Surat" required>
                </div>

                {{-- ISI (Content) Field --}}
                <div class="mb-4">
                    <label for="isi" class="form-label-custom">ISI</label>
                    <textarea class="form-control form-control-custom" style="height: 120px;" id="isi" name="isi" placeholder="Masukkan Isi Surat" required></textarea>
                </div>

                {{-- UPLOAD FILE Field --}}
                <div class="mb-4">
                    <label for="upload_file" class="form-label-custom">UPLOAD FILE</label>
                    <div class="input-group input-group-upload">
                        {{-- Actual file input (hidden) --}}
                        <input type="file" class="form-control d-none" id="upload_file" name="file_surat" required>
                        {{-- Mock input for display --}}
                        <input type="text" class="form-control form-control-custom" id="file_display" placeholder="Pilih file..." readonly onclick="document.getElementById('upload_file').click();">
                        <span class="input-group-text" onclick="document.getElementById('upload_file').click();"><i class="bi bi-upload"></i></span>
                    </div>
                    <script>
                        // Script to update the display input with the selected file name
                        document.getElementById('upload_file').addEventListener('change', function() {
                            const fileName = this.files.length > 0 ? this.files[0].name : '';
                            document.getElementById('file_display').value = fileName;
                        });
                    </script>
                </div>

                {{-- TUJUAN (Destination) Radio Buttons --}}
                <div class="radio-group-container">
                    <p class="form-label-custom">TUJUAN</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tujuan" id="tujuan_rektor" value="rektor" required>
                        <label class="form-check-label radio-label-custom" for="tujuan_rektor">REKTOR</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dekan" value="dekan">
                        <label class="form-check-label radio-label-custom" for="tujuan_dekan">DEKAN</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen" value="dosen">
                        <label class="form-check-label radio-label-custom" for="tujuan_dosen">DOSEN</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tujuan" id="tujuan_tenaga_pendidik" value="tenaga_pendidik">
                        <label class="form-check-label radio-label-custom" for="tujuan_tenaga_pendidik">TENAGA PENDIDIK</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen_tugas_khusus" value="dosen_tugas_khusus">
                        <label class="form-check-label radio-label-custom" for="tujuan_dosen_tugas_khusus">DOSEN TUGAS KHUSUS</label>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-submit-custom btn-kirim-override">KIRIM</button>
                </div>

            </form>
        </div>
        {{-- END: KIRIM SURAT FORM CONTENT --}}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /**
     * Mengkonfirmasi penghapusan dan submit form DELETE yang sesuai.
     */
    function confirmDelete(suratId) {
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            // This function is included in the template but not directly used 
            // in the 'Kirim Surat' view. It's safe to keep.
            document.getElementById('delete-form-' + suratId).submit();
        }
    }
</script>
</body>
</html>