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
            --color-button-kirim: #009933; 
            --color-button-kirim-hover: #007722;
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
            color: var(--color-text-white); text-decoration: none; margin: 8px 0; padding: 10px; 
            border-radius: 5px; font-weight: bold; transition: background 0.2s; 
        }
        .sidebar-menu > a:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-menu a.active-link { background: var(--color-text-white); color: var(--color-text-dark); }

        .sidebar-dropdown-item { margin: 8px 0; }
        .sidebar-dropdown-toggle { 
            display: flex !important; align-items: center; justify-content: space-between; 
            background: var(--color-sidebar-link); color: var(--color-text-white);
            text-decoration: none; padding: 10px; border-radius: 5px; font-weight: bold; 
        }
        .sidebar-dropdown-menu { list-style: none; padding-left: 0; margin-bottom: 0; position: static; background-color: var(--color-sidebar-link-hover); border: none; padding: 0 10px 5px 10px; border-radius: 0 0 5px 5px; box-shadow: none; width: 100%; margin-top: 0; }
        .sidebar-dropdown-menu li a { display: flex; align-items: center; background: transparent !important; color: var(--color-text-white); font-weight: normal; padding: 8px 10px 8px 30px; margin: 2px 0; border-radius: 3px; text-decoration: none; }
        .sidebar-dropdown-menu li a:hover { background: var(--color-sidebar-primary) !important; color: var(--color-text-white) !important; }
        .user-info { display: flex; align-items: center; cursor: pointer; }
        .user-identity {
            display: flex; flex-direction: column; line-height: 1.2;
            margin-right: 10px; text-align: right; 
        }
        .user-name { font-size: 1.1rem; font-weight: bold; color: var(--color-text-white); display: none; }
        .user-role-display { font-size: 0.9rem; font-weight: normal; color: rgba(255, 255, 255, 0.8); }

        @media (min-width: 576px) { .user-name, .user-role-display { display: block; } }
        
        .profile-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background-color: var(--color-text-white); border: 2px solid var(--color-text-white); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--color-sidebar-primary); }
        .sidebar-header { display: flex; align-items: center; margin-bottom: 20px; }
        .logo-img { width: 85px; height: 85px; object-fit: cover; margin-right: 10px; display: block; }
        .logo-text { font-size: 1.4rem; font-weight: bold; color: var(--color-text-white); margin: 0; }

        .main-content-col { flex-grow: 1; padding: 20px; }
        .kirim-surat-panel { background-color: var(--color-kirim-surat-bg); padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin-top: 20px; }
        .kirim-surat-panel h4 { color: var(--color-text-dark); font-weight: bold; margin-bottom: 25px; border-bottom: 3px solid var(--color-text-dark); padding-bottom: 10px; display: inline-block; }
        .form-control-custom { height: 50px; border-radius: 10px; border: 2px solid var(--color-text-dark); font-weight: bold; font-size: 1rem; box-shadow: none !important; }
        .form-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-bottom: 5px; }
        .radio-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-right: 20px; }
        .alert-fixed-top { z-index: 1050; max-width: 400px; color: var(--color-text-dark); position: fixed; top: 20px; right: 20px; }
        
        .btn-kirim-override {
            background-color: var(--color-button-kirim);
            color: var(--color-text-white);
            font-weight: bold;
            padding: 10px 30px;
            border-radius: 8px;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-kirim-override:hover {
            background-color: var(--color-button-kirim-hover);
            color: var(--color-text-white);
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
                
                {{-- Input Hidden untuk Faculty ID tujuan (Digunakan jika tujuan adalah level Fakultas) --}}
                {{-- Dibiarkan tanpa atribut disabled di HTML agar JS yang mengontrol --}}
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
                <div class="radio-group-container mb-4">
                    <p class="form-label-custom">TUJUAN KE LEVEL</p>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        {{-- OPSI 1: TINGKAT UNIVERSITAS (SEMUA FAKULTAS) --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="target_type" id="target_universitas" value="universitas" checked>
                            <label class="form-check-label radio-label-custom" for="target_universitas">UNIVERSITAS (SEMUA FAKULTAS)</label>
                        </div>
                        {{-- OPSI 2: TINGKAT FAKULTAS SPESIFIK --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="target_type" id="target_spesifik" value="spesifik">
                            <label class="form-check-label radio-label-custom" for="target_spesifik">FAKULTAS SPESIFIK</label>
                        </div>
                    </div>
                </div>

                {{-- DROPDOWN PILIH FAKULTAS (Tersembunyi secara default) --}}
                <div class="mb-4" id="faculty_dropdown_container" style="display: none;">
                    <label for="target_faculty_id" class="form-label-custom">PILIH FAKULTAS TUJUAN</label>
                    <select class="form-select form-control-custom @error('target_faculty_id') is-invalid @enderror" id="target_faculty_id" name="target_faculty_id" disabled>
                        <option value="">-- Pilih Fakultas Tujuan --</option>
                        
                        {{-- Loop data Fakultas (Asumsi $allFaculties dikirim dari Controller) --}}
                        @if (isset($allFaculties) && is_iterable($allFaculties))
                            @foreach ($allFaculties as $faculty)
                                <option value="{{ $faculty->id ?? '' }}" 
                                    {{ old('target_faculty_id') == ($faculty->id ?? '') ? 'selected' : '' }}>
                                    {{ $faculty->name ?? 'N/A' }} ({{ $faculty->code ?? 'N/A' }})
                                </option>
                            @endforeach
                        @else
                             <option value="" disabled>-- Data Fakultas belum dimuat --</option>
                        @endif
                    </select>
                    @error('target_faculty_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- TUJUAN ROLE (PILIHAN ROLE/JABATAN) - HANYA BERLAKU UNTUK LEVEL YANG DIPILIH --}}
                <div class="radio-group-container">
                    <p class="form-label-custom">TUJUAN JABATAN (Di level yang dipilih di atas)</p>
                    <div class="d-flex flex-wrap gap-3">
                        @php 
                            // Menggunakan Laravel old() helper dengan fallback 'rektor' jika tidak ada input 'tujuan'
                            $currentTujuan = old('tujuan', 'rektor'); 
                        @endphp

                        <div class="form-check form-check-inline">
                            {{-- FIX: Gunakan $currentTujuan untuk menentukan yang tercentang --}}
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_rektor" value="rektor" {{ $currentTujuan == 'rektor' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_rektor">REKTOR</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dekan" value="dekan" {{ $currentTujuan == 'dekan' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dekan">DEKAN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen" value="dosen" {{ $currentTujuan == 'dosen' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dosen">DOSEN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_tenaga_pendidik" value="tenaga_pendidik" {{ $currentTujuan == 'tenaga_pendidik' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_tenaga_pendidik">TENAGA PENDIDIK</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_dosen_tugas_khusus" value="dosen_tugas_khusus" {{ $currentTujuan == 'dosen_tugas_khusus' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_dosen_tugas_khusus">DOSEN TUGAS KHUSUS</label>
                        </div>
                        {{-- Tambahkan radio untuk Kaprodi jika ada di daftar role --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tujuan" id="tujuan_kaprodi" value="kaprodi" {{ $currentTujuan == 'kaprodi' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="tujuan_kaprodi">KAPRODI</label>
                        </div>
                    </div>
                    @error('tujuan')
                        <div class="text-danger mt-2">Pilih salah satu jabatan tujuan surat.</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-submit-custom btn-kirim-override">KIRIM</button>
                </div>

            </form>
        </div>
        {{-- END: KIRIM SURAT FORM CONTENT --}}
        {{-- START: FOOTER HAK CIPTA --}}
        @include('partials.footer')
        {{-- END: FOOTER HAK CIPTA --}}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hiddenFacultyInput = document.getElementById('tujuan_faculty_id_input');
        const targetTypeRadios = document.querySelectorAll('input[name="target_type"]');
        const facultyDropdownContainer = document.getElementById('faculty_dropdown_container');
        const targetFacultyIdSelect = document.getElementById('target_faculty_id'); // Select dropdown
        const collapseElement = document.getElementById('submenuDaftarSurat');
        const toggleButton = document.getElementById('daftarSuratDropdown');
        
        // Inisialisasi: Pastikan dropdown fakultas dinonaktifkan saat pertama kali dimuat
        targetFacultyIdSelect.setAttribute('disabled', 'disabled');

        function toggleFacultyDropdown() {
            const checkedTargetType = document.querySelector('input[name="target_type"]:checked');
            const selectedTargetType = checkedTargetType ? checkedTargetType.value : 'universitas'; 

            if (selectedTargetType === 'spesifik') {
                facultyDropdownContainer.style.display = 'block';
                
                // Aktifkan dropdown dan hidden input
                targetFacultyIdSelect.setAttribute('required', 'required'); 
                targetFacultyIdSelect.removeAttribute('disabled');
                hiddenFacultyInput.removeAttribute('disabled');
                
                // Sinkronisasi value: ambil value dari dropdown
                hiddenFacultyInput.value = targetFacultyIdSelect.value;

            } else { // 'universitas'
                facultyDropdownContainer.style.display = 'none';
                
                // Hapus requirement, NONAKTIFKAN dropdown
                targetFacultyIdSelect.removeAttribute('required');
                targetFacultyIdSelect.setAttribute('disabled', 'disabled');
                
                // KOSONGKAN dan NONAKTIFKAN hidden input agar TIDAK ikut terkirim
                hiddenFacultyInput.value = ''; 
                hiddenFacultyInput.setAttribute('disabled', 'disabled'); 
            }
        }
        
        // Listener untuk radio target_type
        targetTypeRadios.forEach(radio => {
            radio.addEventListener('change', toggleFacultyDropdown);
        });

        // Listener untuk dropdown fakultas spesifik (jika diubah)
        targetFacultyIdSelect.addEventListener('change', function() {
            // Memastikan hidden input terisi hanya jika tidak disabled
            if (!hiddenFacultyInput.disabled) {
                hiddenFacultyInput.value = this.value;
            }
        });

        // Inisialisasi awal
        toggleFacultyDropdown();
        
        document.getElementById('upload_file').addEventListener('change', function() {
            const fileName = this.files.length > 0 ? this.files[0].name : '';
            document.getElementById('file_display').value = fileName;
        });
        
        // Logic Sidebar Dropdown (tidak diubah)
        const chevronIcon = toggleButton ? toggleButton.querySelector('.bi-chevron-down') : null;
        if (collapseElement && toggleButton && chevronIcon) {
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

    function confirmDelete(suratId) {
        if (confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
            document.getElementById('delete-form-' + suratId).submit();
        }
    }
</script>
</body>
</html>
