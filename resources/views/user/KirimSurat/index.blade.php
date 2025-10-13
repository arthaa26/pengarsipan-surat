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
            --color-kirim-surat-bg: #f7c948;
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
            color: var(--color-text-white); text-decoration: none; margin: 8px 0; padding: 10px; 
            border-radius: 5px; font-weight: bold; transition: background 0.2s; 
        }
        .sidebar-menu > a:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-menu a.active-link { background: var(--color-text-white); color: var(--color-text-dark); }

        /* --- SIDEBAR DROPDOWN (COLLAPSE) STYLES --- */
        .sidebar-dropdown-item { margin: 8px 0; }
        .sidebar-dropdown-toggle { 
            display: flex !important; align-items: center; justify-content: space-between; 
            background: var(--color-sidebar-link); color: var(--color-text-white);
            text-decoration: none; padding: 10px; border-radius: 5px; font-weight: bold; 
        }
        .sidebar-dropdown-menu { list-style: none; padding-left: 0; margin-bottom: 0; position: static; background-color: var(--color-sidebar-link-hover); border: none; padding: 0 10px 5px 10px; border-radius: 0 0 5px 5px; box-shadow: none; width: 100%; margin-top: 0; }
        .sidebar-dropdown-menu li a { display: flex; align-items: center; background: transparent !important; color: var(--color-text-white); font-weight: normal; padding: 8px 10px 8px 30px; margin: 2px 0; border-radius: 3px; text-decoration: none; }
        .sidebar-dropdown-menu li a:hover { background: var(--color-sidebar-primary) !important; color: var(--color-text-white) !important; }
        /* --- END SIDEBAR DROPDOWN STYLES --- */


        /* PROFILE STYLING & LOGO (KEEP EXISTING) */
        .user-info { display: flex; align-items: center; cursor: pointer; }
        
        /* BARU: Container untuk Nama dan Role di Dropdown Profile */
        .user-identity {
            display: flex;
            flex-direction: column; 
            line-height: 1.2;
            margin-right: 10px; /* Spasi sebelum ikon profil */
        }
        .user-name { font-size: 1.1rem; font-weight: bold; color: var(--color-text-white); display: none; }
        .user-role-display { font-size: 0.9rem; font-weight: normal; color: rgba(255, 255, 255, 0.8); }

        @media (min-width: 576px) { .user-name, .user-role-display { display: block; } }
        
        .profile-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background-color: var(--color-text-white); border: 2px solid var(--color-text-white); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .sidebar-header { display: flex; align-items: center; margin-bottom: 20px; }
        .logo-img { width: 85px; height: 85px; object-fit: cover; margin-right: 10px; display: block; }
        .logo-text { font-size: 1.4rem; font-weight: bold; color: var(--color-text-white); margin: 0; }

        /* CUSTOM FORM STYLING for Kirim Surat */
        .main-content-col { flex-grow: 1; padding: 20px; }
        .kirim-surat-panel { background-color: var(--color-kirim-surat-bg); padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin-top: 20px; }
        .kirim-surat-panel h4 { color: var(--color-text-dark); font-weight: bold; margin-bottom: 25px; border-bottom: 3px solid var(--color-text-dark); padding-bottom: 10px; display: inline-block; }
        .form-control-custom { height: 50px; border-radius: 10px; border: 2px solid var(--color-text-dark); font-weight: bold; font-size: 1rem; box-shadow: none !important; }
        .form-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-bottom: 5px; }
        .radio-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-right: 20px; }
        .alert-fixed-top { z-index: 1050; max-width: 400px; color: var(--color-text-dark); position: fixed; top: 20px; right: 20px; }
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
                        <li><a href="{{ route('user.daftar_surat.masuk') ?? '#' }}"><i class="bi bi-envelope me-2"></i>Surat Masuk</a></li>
                        <li><a href="{{ route('user.daftar_surat.keluar') ?? '#' }}"><i class="bi bi-envelope-open me-2"></i>Surat Keluar</a></li>
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
                        {{-- START: TAMPILAN NAMA DAN JABATAN/FAKULTAS --}}
                        @php
                            $roleName = Auth::user()->role->name ?? 'N/A';
                            $facultyCode = Auth::user()->faculty->code ?? '';
                            $displayRole = ucwords(str_replace('_', ' ', $roleName));
                            $fullTitle = $facultyCode ? "({$displayRole} {$facultyCode})" : "({$displayRole})";
                        @endphp
                        <div class="user-identity">
                            <span class="user-name d-none d-sm-block">{{ Auth::user()->name }}</span>
                            <span class="user-role-display d-none d-sm-block">{{ $fullTitle }}</span>
                        </div>
                        {{-- END: TAMPILAN NAMA DAN JABATAN/FAKULTAS --}}

                        <div class="profile-icon">
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
                        @auth {{ Auth::user()->name }} <br><small class="text-muted">{{ $fullTitle }}</small> @else Guest @endauth
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

{{-- START: KIRIM SURAT FORM CONTENT --}}
        <div class="kirim-surat-panel">
            <h4 class="text-uppercase">Kirim Surat</h4>
            
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
                
                {{-- Input Hidden untuk Faculty ID Tujuan --}}
                <input type="hidden" name="tujuan_faculty_id" id="tujuan_faculty_id_input" value=""> 
                
                {{-- KODE SURAT Field --}}
                <div class="mb-4">
                    <label for="kode_surat" class="form-label-custom">KODE SURAT (Otomatis)</label>
                    <input type="text" class="form-control form-control-custom" id="kode_surat" value="{{ $nextKode ?? 'Kode Akan Dibuat Otomatis' }}" placeholder="Kode akan di-generate oleh sistem" readonly disabled>
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
                        <input type="file" class="form-control d-none @error('file_surat') is-invalid @enderror" id="upload_file" name="file_surat" required>
                        <input type="text" class="form-control form-control-custom" id="file_display" placeholder="Pilih file..." readonly onclick="document.getElementById('upload_file').click();">
                        <span class="input-group-text" onclick="document.getElementById('upload_file').click();"><i class="bi bi-upload"></i></span>
                    </div>
                    @error('file_surat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TUJUAN (Destination) Radio Buttons --}}
                <div class="radio-group-container">
                    <p class="form-label-custom">TUJUAN</p>
                    <div class="d-flex flex-wrap gap-3">
                        @php $oldTujuan = old('tujuan'); @endphp

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_rektor" value="rektor" data-is-faculty-level="false" {{ $oldTujuan == 'rektor' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_rektor">REKTOR</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dekan" value="dekan" data-is-faculty-level="true" {{ $oldTujuan == 'dekan' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dekan">DEKAN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen" value="dosen" data-is-faculty-level="true" {{ $oldTujuan == 'dosen' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dosen">DOSEN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_tenaga_pendidik" value="tenaga_pendidik" data-is-faculty-level="true" {{ $oldTujuan == 'tenaga_pendidik' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_tenaga_pendidik">TENAGA PENDIDIK</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen_tugas_khusus" value="dosen_tugas_khusus" data-is-faculty-level="false" {{ $oldTujuan == 'dosen_tugas_khusus' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dosen_tugas_khusus">DOSEN TUGAS KHUSUS</label>
                        </div>
                        {{-- Tambahkan radio untuk Kaprodi jika ada di daftar role --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_kaprodi" value="kaprodi" data-is-faculty-level="true" {{ $oldTujuan == 'kaprodi' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_kaprodi">KAPRODI</label>
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
        const currentUserFacultyId = '{{ Auth::user()->faculty_id ?? '' }}';
        const hiddenFacultyInput = document.getElementById('tujuan_faculty_id_input');
        const purposeRadios = document.querySelectorAll('input[name="tujuan"]');
        const collapseElement = document.getElementById('submenuDaftarSurat');
        const toggleButton = document.getElementById('daftarSuratDropdown');
        
        // Fungsi untuk memperbarui field tersembunyi berdasarkan pilihan radio
        function updateHiddenFacultyId() {
            const checkedRadio = document.querySelector('input[name="tujuan"]:checked');

            if (!checkedRadio) {
                hiddenFacultyInput.value = '';
                return;
            }
            
            const isFacultyLevel = checkedRadio.getAttribute('data-is-faculty-level') === 'true';

            if (isFacultyLevel) {
                // Jika tujuan adalah tingkat Fakultas (Dekan/Kaprodi/Dosen), 
                // kirim ID Fakultas pengirim sebagai ID Fakultas Tujuan.
                hiddenFacultyInput.value = currentUserFacultyId;
            } else {
                // Jika tujuan adalah tingkat Universitas (Rektor/Dosen Khusus), 
                // Faculty ID tujuan adalah NULL (string kosong).
                hiddenFacultyInput.value = ''; 
            }
        }

        // Event Listeners untuk radio buttons
        purposeRadios.forEach(radio => {
            radio.addEventListener('change', updateHiddenFacultyId);
        });

        // Panggil saat DOM dimuat untuk mengatur nilai awal
        updateHiddenFacultyId(); 

        // Listener untuk collapse
        if (collapseElement && toggleButton) {
            collapseElement.addEventListener('show.bs.collapse', function () {
                toggleButton.setAttribute('aria-expanded', 'true');
            });
            collapseElement.addEventListener('hide.bs.collapse', function () {
                toggleButton.setAttribute('aria-expanded', 'false');
            });
        }

        // Script to update the display input with the selected file name
        document.getElementById('upload_file').addEventListener('change', function() {
            const fileName = this.files.length > 0 ? this.files[0].name : '';
            document.getElementById('file_display').value = fileName;
        });
    });

    function confirmDelete(suratId) {
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            document.getElementById('delete-form-' + suratId).submit();
        }
    }
</script>
</body>
</html>