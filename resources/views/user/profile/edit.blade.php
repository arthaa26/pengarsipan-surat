<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Update Profil</title>
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

        /* LAYOUT & SIDEBAR */
        .app-layout { display: flex; min-height: 100vh; }
        .sidebar { background: var(--color-sidebar-primary); padding: 20px 10px; width: 250px; flex-shrink: 0; }

        /* DEFAULT MENU LINK STYLE */
        .sidebar-menu > a {
            display: flex; align-items: center; background: var(--color-sidebar-link);
            color: var(--color-text-white); text-decoration: none; margin: 8px 0;
            padding: 10px; border-radius: 5px; font-weight: bold; transition: background 0.2s;
        }
        .sidebar-menu > a:hover { background: var(--color-sidebar-link-hover); }
        .sidebar-menu a.active-link { background: var(--color-text-white); color: var(--color-text-dark); }

        /* DROPDOWN STYLES */
        .sidebar-dropdown-item { margin: 8px 0; }
        .sidebar-dropdown-toggle {
            display: flex !important; align-items: center; justify-content: space-between;
            background: var(--color-sidebar-link); color: var(--color-text-white);
            padding: 10px; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%;
            text-align: left; border: none; line-height: 1.2; transition: background 0.2s;
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
        
        .main-content-col { flex-grow: 1; padding: 20px; }
        
        /* Card box custom untuk Form Profil */
        .card-box-profile {
            border-radius: 10px;
            padding: 30px; 
            background: var(--color-text-white); 
            color: var(--color-text-dark);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .form-label {
            font-weight: bold;
        }
        
        /* Profile & Logo Styles (disalin untuk konsistensi) */
        .user-info { display: flex; align-items: center; cursor: pointer; }
        .user-name { font-size: 1.1rem; font-weight: bold; margin-right: 10px; color: var(--color-text-white); display: none; }
        @media (min-width: 576px) { .user-name { display: block; } }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background-color: var(--color-text-white); border: 2px solid var(--color-text-white); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .sidebar-header { display: flex; align-items: center; margin-bottom: 20px; }
        .logo-img { width: 65px; height: 65px; border-radius: 50%; object-fit: cover; background-color: #b748f7ff; margin-right: 10px; display: block; border: 2px solid var(--color-text-white); }
        .logo-text { font-size: 1.4rem; font-weight: bold; color: var(--color-text-white); margin: 0; }

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
                onerror="this.onerror=null; this.src='https://placehold.co/35x35/f7c948/0066cc?text=M';"
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
                        <span class="user-name d-none d-sm-block">{{ Auth::user()->name }}</span>
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
                    <li class="dropdown-header">@auth {{ Auth::user()->name }} @else Guest @endauth</li>
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
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                
                {{-- START: FIELD BARU UNTUK NO. HP --}}
                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    {{-- Asumsi data 'no_hp' ada di objek $user --}}
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? '') }}">
                </div>
                {{-- END: FIELD BARU UNTUK NO. HP --}}

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
    // Tidak ada script khusus untuk halaman ini, tapi tetap menyertakan bootstrap bundle
</script>
</body>
</html>