<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Kirim Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        /* BASE STYLES - DIKEMBALIKAN KE WARNA ASLI */
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
        .form-control-custom { height: 50px; border-radius: 10px; border: 2px solid var(--color-text-dark); font-weight: bold; font-size: 1rem; box-shadow: none !important; color: var(--color-text-dark); background-color: #ffffff; }
        .form-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-bottom: 5px; }
        .radio-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-right: 20px; }
        .alert-fixed-top { z-index: 1050; max-width: 400px; color: var(--color-text-dark); position: fixed; top: 20px; right: 20px; } 
        
        .btn-kirim-override {
            background-color: var(--color-button-kirim); color: var(--color-text-white);
            font-weight: bold; padding: 10px 30px; border-radius: 8px;
            border: none; transition: background-color 0.3s;
        }
        .btn-kirim-override:hover {
            background-color: var(--color-button-kirim-hover); color: var(--color-text-white);
        }
        .text-danger { color: red !important; }

        /* SELECT2 CUSTOM STYLES */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 50px !important; border-radius: 10px !important;
            border: 2px solid var(--color-text-dark) !important; font-weight: bold !important;
            font-size: 1rem !important; padding-top: 10px;
            background-color: #ffffff !important; color: var(--color-text-dark) !important;
        }
        .select2-container--bootstrap-5 .select2-selection__placeholder { color: #555 !important; }
        .select2-container--bootstrap-5 .select2-selection__arrow b { border-top-color: var(--color-text-dark) !important; }
        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid var(--color-text-dark) !important; border-radius: 0 0 10px 10px;
            background-color: #FFFFFF;
        }
        .select2-container--bootstrap-5 .select2-results__option { color: var(--color-text-dark); }
        .select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: var(--color-sidebar-link-hover) !important; color: var(--color-text-white) !important;
        }
        .select2-container--bootstrap-5 .select2-results__option--selected {
            background-color: var(--color-sidebar-link-hover) !important; color: var(--color-text-white) !important;
        }

        .form-check-input:checked {
            background-color: var(--color-sidebar-primary); border-color: var(--color-sidebar-primary);
        }
        
        .input-group-text {
            background-color: var(--color-sidebar-link); border-color: var(--color-text-dark); 
            color: var(--color-text-white); cursor: pointer;
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
            {{-- MENU LEVEL ATAS --}}
            <a href="#"><i class="bi bi-list-task me-2"></i>MENU</a>
            <a href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            
            {{-- DROPDOWN DAFTAR SURAT --}}
            <div class="sidebar-dropdown-item">
                <a class="sidebar-dropdown-toggle collapsed" id="daftarSuratDropdown" 
                    data-bs-toggle="collapse" href="#submenuDaftarSurat" role="button" aria-expanded="false" 
                    aria-controls="submenuDaftarSurat">
                    <i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT
                    <i class="bi bi-chevron-down" style="font-size: 1em; transition: transform 0.3s;"></i>
                </a>

                <div id="submenuDaftarSurat" class="collapse">
                    <ul class="sidebar-dropdown-menu">
                        <li><a href="{{ route('user.daftar_surat.masuk') ?? '#' }}"><i class="bi bi-envelope me-2"></i>Surat Masuk</a></li>
                        <li><a href="{{ route('user.daftar_surat.keluar') ?? '#' }}"><i class="bi bi-envelope-open me-2"></i>Surat Keluar</a></li>
                    </ul>
                </div>
            </div>
            
            {{-- Tautan KIRIM SURAT --}}
            <a href="{{ route('user.kirim_surat.index') ?? '#' }}" class="active-link"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold" style="color: var(--color-text-white);">KIRIM SURAT</h2>
            
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show alert-fixed-top" role="alert" style="background-color: var(--color-card-green); color: var(--color-text-white); border-color: var(--color-button-kirim-hover);">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Sukses!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
             {{-- Notifikasi Error (Tambahan, penting untuk debugging error sebelumnya) --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show alert-fixed-top" role="alert" style="background-color: #f8d7da; color: var(--color-text-dark); border-color: #f5c6cb;">
                    <i class="bi bi-x-octagon-fill me-2"></i>
                    <strong>Gagal!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            {{-- User Profile Dropdown (No Changes) --}}
            <div class="dropdown">
                <div class="user-info dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
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
                        <div class="profile-icon">
                            @if (Auth::user()->profile_photo_url ?? false)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="profile-img">
                            @else
                                <div class="profile-img" style="color: var(--color-sidebar-primary);"><i class="bi bi-person-circle"></i></div>
                            @endif
                        </div>
                    @else
                        <span class="user-name d-none d-sm-block">Guest User</span>
                        <div class="profile-img" style="color: var(--color-sidebar-primary);"><i class="bi bi-person-circle"></i></div>
                    @endauth
                    <i class="bi bi-chevron-down ms-2 fs-5 text-white"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="dropdown-header" style="color: var(--color-text-dark);">
                        @auth {{ Auth::user()->name }} <br><small class="text-muted" style="color: #6c757d !important;">{{ $fullTitle }}</small> @else Guest @endauth
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('user.profile.edit') ?? '#' }}" style="color: var(--color-text-dark);"><i class="bi bi-person-circle me-2"></i>User Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.dashboard') ?? '#' }}" style="color: var(--color-text-dark);"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.daftar_surat.masuk') ?? '#' }}" style="color: var(--color-text-dark);"><i class="bi bi-folder-fill me-2"></i>Surat</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" style="color: red !important;" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                <div class="alert alert-danger" style="color: var(--color-text-dark); background-color: #f8d7da; border-color: #f5c6cb;">
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
                
                {{-- Input Hidden untuk menyimpan target role (pengganti radio jabatan) --}}
                <input type="hidden" name="tujuan" id="tujuan_hidden_input" value="{{ old('tujuan', 'rektor') }}">

                {{-- KODE SURAT --}}
                <div class="mb-4">
                    <label for="kode_surat" class="form-label-custom">KODE SURAT (Otomatis)</label>
                    <input type="text" class="form-control form-control-custom" id="kode_surat" value="{{ $nextKode ?? 'Kode Akan Dibuat Otomatis' }}" placeholder="Kode akan di-generate oleh sistem" readonly>
                </div>
                
                {{-- TITLE --}}
                <div class="mb-4">
                    <label for="title" class="form-label-custom">TITLE</label>
                    <input type="text" class="form-control form-control-custom @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Masukkan Judul Surat" required>
                    @error('title')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ISI --}}
                <div class="mb-4">
                    <label for="isi" class="form-label-custom">ISI</label>
                    <textarea class="form-control form-control-custom @error('isi') is-invalid @enderror" style="height: 120px;" id="isi" name="isi" placeholder="Masukkan Isi Surat" required>{{ old('isi') }}</textarea>
                    @error('isi')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- UPLOAD FILE --}}
                <div class="mb-4">
                    <label for="upload_file" class="form-label-custom">UPLOAD FILE</label>
                    <div class="input-group">
                        <input type="file" class="form-control d-none @error('file_surat') is-invalid @enderror" id="upload_file" name="file_surat" required>
                        <input type="text" class="form-control form-control-custom" id="file_display" placeholder="Pilih file..." readonly style="cursor: pointer;" onclick="document.getElementById('upload_file').click();">
                        <span class="input-group-text" onclick="document.getElementById('upload_file').click();"><i class="bi bi-upload"></i></span>
                    </div>
                    @error('file_surat')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TUJUAN KE LEVEL (Radio Buttons) --}}
                <div class="radio-group-container mb-4">
                    <p class="form-label-custom">TUJUAN KEL</p>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="target_type" id="target_universitas" value="universitas" {{ old('target_type', 'universitas') == 'universitas' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="target_universitas">UNIVERSITAS</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="target_type" id="target_spesifik" value="spesifik" {{ old('target_type') == 'spesifik' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="target_spesifik">FAKULTAS SPESIFIK</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="target_type" id="target_user_spesifik" value="user_spesifik" {{ old('target_type') == 'user_spesifik' ? 'checked' : '' }}>
                            <label class="form-check-label radio-label-custom" for="target_user_spesifik">USER SPESIFIK</label>
                        </div>
                    </div>
                </div>

                {{-- DROPDOWN PILIH FAKULTAS (Conditional) --}}
                <div class="mb-4" id="faculty_dropdown_container" style="display: none;">
                    <label for="target_faculty_id" class="form-label-custom">PILIH FAKULTAS TUJUAN</label>
                    <select class="form-select form-control-custom @error('target_faculty_id') is-invalid @enderror" id="target_faculty_id" name="target_faculty_id" disabled>
                        <option value="">-- Pilih Fakultas Tujuan --</option>
                        @isset($allFaculties)
                            @foreach ($allFaculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ old('target_faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }} ({{ $faculty->code }})
                                </option>
                            @endforeach
                        @else
                            {{-- Fallback data for testing --}}
                            <option value="1">Fakultas Teknik (FT)</option>
                            <option value="2">Fakultas Ekonomi dan Bisnis (FEB)</option>
                        @endisset
                    </select>
                    @error('target_faculty_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DROPDOWN PILIH ROLE SPESIFIK DALAM FAKULTAS (NEW FIELD) --}}
                <div class="mb-4" id="role_dropdown_container" style="display: none;">
                    <label for="target_role_id" class="form-label-custom">PILIH ROLE TUJUAN DALAM FAKULTAS</label>
                    <select class="form-select form-control-custom @error('target_role_id') is-invalid @enderror" id="target_role_id" name="target_role_id" disabled>
                        <option value="">-- Pilih Role Tujuan --</option>
                        {{-- ID 1 (Admin) diabaikan, menggunakan ID 2-7 dari daftar role umum --}}
                        <option value="2" {{ old('target_role_id') == 2 ? 'selected' : '' }}>Rektor</option>
                        <option value="3" {{ old('target_role_id') == 3 ? 'selected' : '' }}>Dekan</option>
                        <option value="5" {{ old('target_role_id') == 5 ? 'selected' : '' }}>Kaprodi</option>
                        <option value="4" {{ old('target_role_id') == 4 ? 'selected' : '' }}>Dosen</option>
                        <option value="6" {{ old('target_role_id') == 6 ? 'selected' : '' }}>Tenaga Pendidik</option>
                        <option value="7" {{ old('target_role_id') == 7 ? 'selected' : '' }}>Dosen Tugas Khusus</option>
                    </select>
                    @error('target_role_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- DROPDOWN PILIH USER SPESIFIK (Conditional, with Select2) --}}
                <div class="mb-4" id="user_dropdown_container" style="display: none;">
                    <label for="target_user_id" class="form-label-custom">CARI & PILIH USER TUJUAN</label>
                    <select class="form-select form-control-custom @error('target_user_id') is-invalid @enderror" id="target_user_id" name="target_user_id" style="width: 100%;" disabled>
                        {{-- Data akan dimuat via AJAX --}}
                         {{-- Jika ada old user_id, tampilkan opsi default --}}
                        @if (old('target_user_id'))
                            <option value="{{ old('target_user_id') }}" selected>Memuat User...</option>
                        @endif
                    </select>
                    @error('target_user_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-submit-custom btn-kirim-override">KIRIM</button>
                </div>
            </form>
        </div>
        
        <footer class="mt-4 text-center text-white-50">
            <p>&copy; 2025 E-ARSIP. All rights reserved.</p>
        </footer>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // =========================================================================
    // INITIALIZATION
    // =========================================================================
    
    // Cache jQuery selectors for performance
    const hiddenTujuanInput = $('#tujuan_hidden_input');
    const targetTypeRadios = $('input[name="target_type"]');
    
    const facultyDropdownContainer = $('#faculty_dropdown_container');
    const facultySelect = $('#target_faculty_id');

    const roleDropdownContainer = $('#role_dropdown_container'); 
    const roleSelect = $('#target_role_id');                       
    
    const userDropdownContainer = $('#user_dropdown_container');
    const userSelect = $('#target_user_id');

    // Initialize Select2 for User Search
    userSelect.select2({
        theme: 'bootstrap-5',
        placeholder: '-- Ketik Nama atau Username User --',
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: '{{ route("api.search.users") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    page: params.page || 1,
                };
            },
            processResults: function (data) {
                // Ensure the data structure is what Select2 expects
                return {
                    results: data.results || [],
                    pagination: data.pagination || { more: false }
                };
            },
            cache: true
        }
    });

    // Perbaikan UX: Jika ada old user_id, kita perlu memuat data user tersebut ke Select2 saat load
    @if (old('target_user_id'))
        $.ajax({
            url: '{{ route("api.get.user", ["id" => old("target_user_id")]) }}', // Asumsi ada route untuk ambil user berdasarkan ID
            dataType: 'json',
            success: function (data) {
                if (data.id && data.text) {
                    const option = new Option(data.text, data.id, true, true);
                    userSelect.append(option).trigger('change');
                } else {
                    userSelect.val(null).trigger('change');
                }
            }
        });
    @endif
    
    // =========================================================================
    // CORE FUNCTION: Controls form display based on selected target level
    // =========================================================================
    function toggleTargetTypeDisplay() {
        // Ambil nilai dari radio yang ter-check, atau pakai old('target_type'), default 'universitas'
        const selectedTargetType = $('input[name="target_type"]:checked').val() || '{{ old('target_type', 'universitas') }}';

        // 1. Reset and hide all conditional sections first
        facultyDropdownContainer.hide();
        facultySelect.prop('disabled', true).prop('required', false);

        roleDropdownContainer.hide(); 
        roleSelect.prop('disabled', true).prop('required', false);

        userDropdownContainer.hide();
        userSelect.prop('disabled', true).prop('required', false);

        // 2. Set the target role and show the relevant section based on selection
        switch (selectedTargetType) {
            case 'universitas':
                hiddenTujuanInput.val('rektor');
                break;

            case 'spesifik':
                // Diperlukan Fakultas DAN Role
                hiddenTujuanInput.val('fakultas');
                
                facultyDropdownContainer.show();
                facultySelect.prop('disabled', false).prop('required', true);

                roleDropdownContainer.show();
                roleSelect.prop('disabled', false).prop('required', true);
                break;

            case 'user_spesifik':
                hiddenTujuanInput.val('personal');
                userDropdownContainer.show();
                userSelect.prop('disabled', false).prop('required', true);
                // Hanya auto-open jika tidak ada error dari server (agar tidak mengganggu saat load)
                if (!$('.is-invalid').length) {
                    setTimeout(() => { userSelect.select2('open'); }, 100);
                }
                break;
        }
    }
    targetTypeRadios.on('change', toggleTargetTypeDisplay);

    // File input display logic
    $('#upload_file').on('change', function() {
        const fileName = this.files.length > 0 ? this.files[0].name : 'Pilih file...';
        $('#file_display').val(fileName);
    });

    // Sidebar dropdown animation logic
    const collapseElement = document.getElementById('submenuDaftarSurat');
    const toggleButton = document.getElementById('daftarSuratDropdown');
    if (collapseElement && toggleButton) {
        const chevronIcon = toggleButton.querySelector('.bi-chevron-down');
        collapseElement.addEventListener('show.bs.collapse', function () {
            chevronIcon.style.transform = 'rotate(-180deg)';
        });
        collapseElement.addEventListener('hide.bs.collapse', function () {
            chevronIcon.style.transform = 'rotate(0deg)';
        });
    }

    // Jalankan fungsi saat dokumen siap
    toggleTargetTypeDisplay();
});
</script>
</body>
</html>
