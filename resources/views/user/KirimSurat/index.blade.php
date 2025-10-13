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
        .sidebar-menu > a { 
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

        /* ACTIVE LINK STYLE (KIRIM SURAT) */
        .sidebar-menu a.active-link { 
            background: var(--color-text-white);
            color: var(--color-text-dark);
        }

        /* --- SIDEBAR DROPDOWN (COLLAPSE) STYLES --- */
        .sidebar-dropdown-item { margin: 8px 0; }
        
        .sidebar-dropdown-toggle {
            display: flex !important; align-items: center; justify-content: space-between; 
            background: var(--color-sidebar-link); color: var(--color-text-white);
            text-decoration: none; padding: 10px; border-radius: 5px; font-weight: bold;
            transition: background 0.2s; cursor: pointer; width: 100%; text-align: left;
            border: none; line-height: 1.2;
        }
        .sidebar-dropdown-toggle:hover { background: var(--color-sidebar-link-hover); color: var(--color-text-white); }
        .sidebar-dropdown-toggle[aria-expanded="true"] { background: var(--color-sidebar-link-hover); border-radius: 5px 5px 0 0; }
        .sidebar-dropdown-toggle .bi-chevron-down { transition: transform 0.3s; }
        .sidebar-dropdown-toggle[aria-expanded="true"] .bi-chevron-down { transform: rotate(-180deg); }

        .sidebar-dropdown-menu {
            list-style: none; padding-left: 0; margin-bottom: 0; position: static; 
            background-color: var(--color-sidebar-link-hover); border: none;
            padding: 0 10px 5px 10px; border-radius: 0 0 5px 5px; box-shadow: none; 
            width: 100%; margin-top: 0;
        }
        .sidebar-dropdown-menu li { margin: 0; }
        .sidebar-dropdown-menu li a {
            display: flex; align-items: center; background: transparent !important; 
            color: var(--color-text-white); font-weight: normal; 
            padding: 8px 10px 8px 30px; margin: 2px 0; border-radius: 3px; text-decoration: none;
        }
        .sidebar-dropdown-menu li a:hover { background: var(--color-sidebar-primary) !important; color: var(--color-text-white) !important; }
        /* --- END SIDEBAR DROPDOWN STYLES --- */


        /* PROFILE STYLING & LOGO (KEEP EXISTING) */
        .user-info { display: flex; align-items: center; cursor: pointer; }
        .user-name { font-size: 1.1rem; font-weight: bold; margin-right: 10px; color: var(--color-text-white); display: none; }
        @media (min-width: 576px) { .user-name { display: block; } }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background-color: var(--color-text-white); border: 2px solid var(--color-text-white); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .sidebar-header { display: flex; align-items: center; margin-bottom: 20px; }
        .logo-img { width: 85px; height: 85px; object-fit: cover; margin-right: 10px; display: block; }
        .logo-text { font-size: 1.4rem; font-weight: bold; color: var(--color-text-white); margin: 0; }

        /* CUSTOM FORM STYLING for Kirim Surat */
        .main-content-col { flex-grow: 1; padding: 20px; }
        .kirim-surat-panel { background-color: var(--color-kirim-surat-bg); padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin-top: 20px; }
        .kirim-surat-panel h4 { color: var(--color-text-dark); font-weight: bold; margin-bottom: 25px; border-bottom: 3px solid var(--color-text-dark); padding-bottom: 10px; display: inline-block; }
        .form-control-custom { height: 50px; border-radius: 10px; border: 2px solid var(--color-text-dark); font-weight: bold; font-size: 1rem; box-shadow: none !important; }
        .form-control-custom:disabled, .form-control-custom[readonly] { background-color: #fff; color: var(--color-sidebar-primary); opacity: 1; }
        .form-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-bottom: 5px; }
        .radio-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-right: 20px; }
        .form-check-input:checked { background-color: var(--color-sidebar-primary); border-color: var(--color-sidebar-primary); }
        .form-check-input { width: 1.3em; height: 1.3em; }
        .input-group-upload .form-control-custom { border-right: none; flex-grow: 1; }
        .input-group-upload .input-group-text { background-color: var(--color-text-white); color: var(--color-text-dark); border: 2px solid var(--color-text-dark); border-left: none; height: 50px; border-radius: 0 10px 10px 0; font-size: 1.5rem; padding: 0 15px; }
        .btn-submit-custom { background-color: var(--color-sidebar-primary); color: var(--color-text-white); font-weight: bold; padding: 10px 30px; border-radius: 8px; border: none; margin-top: 20px; font-size: 1.2rem; transition: background-color 0.2s; }
        .btn-submit-custom:hover { background-color: var(--color-sidebar-link-hover); }
        .radio-group-container { margin-top: 15px; margin-bottom: 25px; }
        /* Style untuk notifikasi pop-up */
        .alert-fixed-top {
            z-index: 1050; 
            max-width: 400px; 
            color: var(--color-text-dark);
            position: fixed;
            top: 20px; 
            right: 20px;
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
            <a href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            
            {{-- DROPDOWN DAFTAR SURAT (Menggunakan Bootstrap Collapse) --}}
            <div class="sidebar-dropdown-item">
                <a class="sidebar-dropdown-toggle collapsed" id="daftarSuratDropdown" 
                    data-bs-toggle="collapse" href="#submenuDaftarSurat" role="button" aria-expanded="false" 
                    aria-controls="submenuDaftarSurat">
                    <i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT
                    <i class="bi bi-chevron-down" style="font-size: 1em;"></i>
                </a>

                <div id="submenuDaftarSurat" class="collapse">
                    <ul class="sidebar-dropdown-menu">
                        <li>
                            {{-- FIX: Menggunakan rute spesifik --}}
                            <a href="{{ route('user.daftar_surat.masuk') ?? '#' }}">
                                <i class="bi bi-envelope me-2"></i>Surat Masuk
                            </a>
                        </li>
                        <li>
                            {{-- FIX: Menggunakan rute spesifik --}}
                            <a href="{{ route('user.daftar_surat.keluar') ?? '#' }}">
                                <i class="bi bi-envelope-open me-2"></i>Surat Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Tautan KIRIM SURAT (Link aktif untuk halaman ini) --}}
            <a href="{{ route('user.kirim_surat.index') ?? '#' }}" class="active-link"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">KIRIM SURAT</h2>
            
            {{-- START: NOTIFIKASI SUKSES (Pop-up) --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show alert-fixed-top" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Sukses!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- END: NOTIFIKASI SUKSES --}}
            
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
                    
                    {{-- FIX: Tautan ke halaman EDIT PROFIL --}}
                    <li><a class="dropdown-item" href="{{ route('user.profile.edit') ?? '#' }}"><i class="bi bi-person-circle me-2"></i>User Profile</a></li>
                    
                    <li><a class="dropdown-item" href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    
                    {{-- FIX: Tautan Surat dialihkan ke Daftar Surat Masuk (sebagai default) --}}
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

{{-- START: KIRIM SURAT FORM CONTENT --}}
        <div class="kirim-surat-panel">
            <h4 class="text-uppercase">Kirim Surat</h4>
            
            {{-- Tambahkan penanganan error validasi di sini jika Anda menggunakan validasi Laravel --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="color: var(--color-text-dark);">
                    <strong>Oops!</strong> Ada masalah dengan input Anda.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
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
                    <input type="text" class="form-control form-control-custom @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Masukkan Judul Surat" required>
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ISI (Content) Field --}}
                <div class="mb-4">
                    <label for="isi" class="form-label-custom">ISI</label>
                    <textarea class="form-control form-control-custom @error('isi') is-invalid @enderror" style="height: 120px;" id="isi" name="isi" placeholder="Masukkan Isi Surat" required>{{ old('isi') }}</textarea>
                    @error('isi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- UPLOAD FILE Field --}}
                <div class="mb-4">
                    <label for="upload_file" class="form-label-custom">UPLOAD FILE</label>
                    <div class="input-group input-group-upload">
                        {{-- Actual file input (hidden) --}}
                        <input type="file" class="form-control d-none @error('file_surat') is-invalid @enderror" id="upload_file" name="file_surat" required>
                        {{-- Mock input for display --}}
                        <input type="text" class="form-control form-control-custom" id="file_display" placeholder="Pilih file..." readonly onclick="document.getElementById('upload_file').click();">
                        <span class="input-group-text" onclick="document.getElementById('upload_file').click();"><i class="bi bi-upload"></i></span>
                    </div>
                    @error('file_surat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
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
                    <div class="d-flex flex-wrap gap-3">
                        @php $oldTujuan = old('tujuan'); @endphp

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_rektor" value="rektor" required {{ $oldTujuan == 'rektor' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_rektor">REKTOR</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dekan" value="dekan" {{ $oldTujuan == 'dekan' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dekan">DEKAN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen" value="dosen" {{ $oldTujuan == 'dosen' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dosen">DOSEN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_tenaga_pendidik" value="tenaga_pendidik" {{ $oldTujuan == 'tenaga_pendidik' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_tenaga_pendidik">TENAGA PENDIDIK</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen_tugas_khusus" value="dosen_tugas_khusus" {{ $oldTujuan == 'dosen_tugas_khusus' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dosen_tugas_khusus">DOSEN TUGAS KHUSUS</label>
                        </div>
                    </div>
                    @error('tujuan')
                        <div class="text-danger mt-2">Pilih salah satu tujuan surat.</div>
                    @enderror
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
    // Rotasi ikon panah saat dropdown dibuka/ditutup (dari kode dashboard)
    document.addEventListener('DOMContentLoaded', function () {
        const collapseElement = document.getElementById('submenuDaftarSurat');
        const toggleButton = document.getElementById('daftarSuratDropdown');

        if (collapseElement && toggleButton) {
            collapseElement.addEventListener('show.bs.collapse', function () {
                toggleButton.setAttribute('aria-expanded', 'true');
            });
            collapseElement.addEventListener('hide.bs.collapse', function () {
                toggleButton.setAttribute('aria-expanded', 'false');
            });
        }
    });

    // Script to update the display input with the selected file name (Diulang di sini untuk memastikan scope)
    document.getElementById('upload_file').addEventListener('change', function() {
        const fileName = this.files.length > 0 ? this.files[0].name : '';
        document.getElementById('file_display').value = fileName;
    });

    function confirmDelete(suratId) {
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            document.getElementById('delete-form-' + suratId).submit();
        }
    }
</script>
</body>
</html>