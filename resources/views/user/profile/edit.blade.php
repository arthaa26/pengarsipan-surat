<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Update Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --color-bg-body: #4db8ff; --color-sidebar-primary: #0066cc;
            --color-sidebar-link: #0080ff; --color-sidebar-link-hover: #0059b3;
            --color-text-white: #fff; --color-text-dark: #000000;
        }

        body { background: var(--color-bg-body); font-family: 'Arial', sans-serif; color: var(--color-text-white); }
        .app-layout { display: flex; min-height: 100vh; }
        .sidebar { background: var(--color-sidebar-primary); padding: 20px 10px; width: 250px; flex-shrink: 0; }
        .sidebar-menu > a { display: flex; align-items: center; background: var(--color-sidebar-link); color: var(--color-text-white); text-decoration: none; margin: 8px 0; padding: 10px; border-radius: 5px; font-weight: bold; transition: background 0.2s; }
        .sidebar-menu > a:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-menu a.active-link { background: var(--color-text-white); color: var(--color-text-dark); }
        
        /* DROPDOWN & SIDEBAR STYLES */
        .sidebar-dropdown-item { margin: 8px 0; }
        .sidebar-dropdown-toggle { display: flex !important; align-items: center; justify-content: space-between; background: var(--color-sidebar-link); color: var(--color-text-white); padding: 10px; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%; text-align: left; border: none; line-height: 1.2; transition: background 0.2s; }
        .sidebar-dropdown-toggle:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-dropdown-toggle[aria-expanded="true"] { background: var(--color-sidebar-link-hover); border-radius: 5px 5px 0 0; }
        .sidebar-dropdown-menu { list-style: none; padding-left: 0; margin-bottom: 0; position: static; background-color: var(--color-sidebar-link-hover); border: none; padding: 0 10px 5px 10px; border-radius: 0 0 5px 5px; box-shadow: none; width: 100%; margin-top: 0; }
        .sidebar-dropdown-menu li a { display: flex; align-items: center; background: transparent !important; color: var(--color-text-white); font-weight: normal; padding: 8px 10px 8px 30px; margin: 2px 0; border-radius: 3px; text-decoration: none; }
        .sidebar-dropdown-menu li a:hover { background: var(--color-sidebar-primary) !important; color: var(--color-text-white) !important; }

        /* MAIN CONTENT & FORM */
        .main-content-col { flex-grow: 1; padding: 20px; }
        .card-box-profile { border-radius: 10px; padding: 30px; background: var(--color-text-white); color: var(--color-text-dark); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .form-label { font-weight: bold; }
        
        /* Profile & Logo Styles */
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
            color: var(--color-sidebar-primary); /* Warna ikon default */
        }
        .sidebar-header { display: flex; align-items: center; margin-bottom: 20px; }
        
        /* Logo size/style from previous request */
        .logo-img { width: 85px; height: 85px; border-radius: 50%; object-fit: cover; margin-right: 10px; display: block; border: none; }
        .logo-text { font-size: 1.4rem; font-weight: bold; color: var(--color-text-white); margin: 0; }

        /* Profile Image Area Styles */
        .profile-image-area {
            text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #ddd;
        }
        .profile-image-preview {
            width: 120px; height: 120px; object-fit: cover; border-radius: 50%;
            border: 4px solid var(--color-sidebar-primary); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        .btn-upload-trigger { margin-top: 10px; }
    </style>
</head>
<body>

<div class="app-layout">
    {{-- SIDEBAR --}}
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
                <a class="sidebar-dropdown-toggle collapsed" id="daftarSuratDropdown" 
                    data-bs-toggle="collapse" href="#submenuDaftarSurat" role="button" aria-expanded="false" 
                    aria-controls="submenuDaftarSurat">
                    <i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT
                    <i class="bi bi-chevron-down" style="font-size: 1em;"></i>
                </a>

                <div class="collapse" id="submenuDaftarSurat">
                    <ul class="sidebar-dropdown-menu">
                        <li><a href="{{ route('user.daftar_surat.masuk') ?? '#' }}"><i class="bi bi-envelope me-2"></i>Surat Masuk</a></li>
                        <li><a href="{{ route('user.daftar_surat.keluar') ?? '#' }}"><i class="bi bi-envelope-open me-2"></i>Surat Keluar</a></li>
                    </ul>
                </div>
            </div>
            
            <a href="{{ route('user.kirim_surat.index') ?? '#' }}"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">UPDATE PROFIL</h2>
            
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
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
        
        {{-- NOTIFIKASI DAN ERROR --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show w-100 mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger w-100 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UPDATE PROFIL --}}
        <div class="card-box-profile">
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- AREA FOTO PROFIL BARU --}}
                <div class="profile-image-area">
                    <img 
                        src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/120x120/0066cc/ffffff?text=User' }}" 
                        alt="Foto Profil" 
                        id="profile_preview"
                        class="profile-image-preview" 
                        onclick="document.getElementById('profile_photo').click();"
                    >
                    
                    {{-- Input file tersembunyi --}}
                    <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">
                    
                    <button type="button" class="btn btn-sm btn-upload-trigger btn-primary" onclick="document.getElementById('profile_photo').click();">
                        <i class="bi bi-camera me-1"></i> Ganti Foto
                    </button>
                    @error('profile_photo')
                        <div class="text-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                {{-- END AREA FOTO PROFIL --}}

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? '') }}">
                </div>

                {{-- [BARU] Tampilkan Role dan Fakultas (Read-only) --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role Pengguna</label>
                        <input type="text" class="form-control" value="{{ $displayRole }}" disabled readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fakultas</label>
                        <input type="text" class="form-control" value="{{ $facultyCode ?: 'Universitas/Pusat' }}" disabled readonly>
                    </div>
                </div>
                
                <hr>
                
                <p class="fw-bold">Ubah Password (Kosongkan jika tidak ingin mengubah)</p>

                <div class="mb-3">
                    <label for="password_new" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password_new" name="password_new">
                </div>

                <div class="mb-4">
                    <label for="password_new_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_new_confirmation" name="password_new_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Preview gambar baru sebelum diupload
    document.getElementById('profile_photo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile_preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Menangani rotasi ikon panah saat dropdown dibuka/ditutup (dari kode sebelumnya)
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('daftarSuratDropdown');
        const chevronIcon = toggleButton ? toggleButton.querySelector('.bi-chevron-down') : null;

        if (chevronIcon) {
            const collapseElement = document.getElementById('submenuDaftarSurat');

            // listener untuk rotasi ikon
            if (collapseElement) {
                collapseElement.addEventListener('show.bs.collapse', () => {
                    chevronIcon.style.transform = 'rotate(-180deg)';
                });
                collapseElement.addEventListener('hide.bs.collapse', () => {
                    chevronIcon.style.transform = 'rotate(0deg)';
                });
            }
        }
    });
</script>
</body>
</html>