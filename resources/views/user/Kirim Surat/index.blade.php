<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Kirim Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* BASE STYLES - REPLICATED FROM PREVIOUS CODE */
        :root {
            --color-bg-body: #4db8ff;
            --color-sidebar-primary: #0066cc;
            --color-sidebar-link: #0080ff;
            --color-sidebar-link-hover: #0059b3;
            --color-card-green: #22c55e;
            --color-card-orange: #f59e42;
            --color-table-accent: #f7c948; /* Bright Yellow for Content Block */
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
        /* Highlight for current active page - KIRIM SURAT */
        .sidebar-menu .active {
            background: var(--color-text-white); /* White background for active link */
            color: var(--color-sidebar-primary); /* Blue text for active link */
        }

        .sidebar-menu a:hover:not(.active) {
            background: var(--color-sidebar-link-hover);
        }
        .main-content-col {
            flex-grow: 1;
            padding: 20px;
        }
        /* [NEW] LOGO STYLING */
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
            background-color: #b748f7ff;
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

        /* [NEW] FORM STYLING FOR KIRIM SURAT */
        .form-container {
            background: var(--color-table-accent); /* Yellow block */
            color: var(--color-text-dark); /* Black text on yellow */
            padding: 20px;
            border-radius: 0; /* Sharp corners like the image */
            box-shadow: none; /* Flat design as in the image */
            margin-top: 20px;
        }
        .form-header {
            color: var(--color-text-dark);
            font-weight: bold;
            border-bottom: 2px solid var(--color-text-white); /* White border divider */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .custom-input {
            background-color: var(--color-text-white); /* White input background */
            border: 2px solid var(--color-text-dark); /* Black border for contrast */
            border-radius: 10px;
            font-weight: bold;
            padding: 15px 20px;
            margin-bottom: 20px;
            color: var(--color-text-dark);
            font-size: 1.1rem;
            height: auto; /* Allow padding to define height */
        }
        /* Style for the simulated 'Upload' button on the file input */
        .file-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .file-input-wrapper .form-control {
            padding-right: 50px; /* Make space for the icon */
        }
        .file-upload-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--color-text-dark);
        }
        
        /* Custom Radio Button Styles */
        .custom-radio-group {
            margin-top: 15px;
        }
        .custom-radio-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: var(--color-text-dark);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        .custom-radio {
            /* Standard radio button sizing */
            width: 20px;
            height: 20px;
            appearance: none; /* Hide default radio */
            border-radius: 50%;
            border: 3px solid var(--color-text-dark); /* Black border */
            margin-right: 10px;
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
        }
        .custom-radio:checked::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        /* Specific colors for checked state */
        .radio-rektor:checked::before {
            background-color: #f00; /* Red dot for REKTOR */
        }
        .radio-dekan:checked::before {
            background-color: #0f0; /* Green dot for DEKAN */
        }
        
        /* Submit Button */
        .btn-submit {
            background-color: var(--color-sidebar-primary); /* Blue button */
            color: var(--color-text-white);
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 1.2rem;
            border: none;
            margin-top: 30px;
            width: 100%;
        }

    </style>
</head>
<body>

<div class="app-layout">
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="sidebar-header">
            {{-- Placeholder for Logo --}}
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
            <a href="#"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            <a href="#"><i class="bi bi-list-task me-2"></i>MENU</a>
            <a href="#"><i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT</a>
            {{-- ACTIVE link to match the image --}}
            <a href="#" class="active"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            {{-- Page Title (Large and Bold) --}}
            <h2 class="fw-bold text-white display-4">KIRIM SURAT</h2>
            
            {{-- User Profile Placeholder --}}
            <div class="dropdown">
                <div class="user-info dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-img"><i class="bi bi-person-circle text-primary"></i></div>
                    <i class="bi bi-chevron-down ms-2 fs-5 text-white"></i>
                </div>
                {{-- Dropdown Menu content would go here --}}
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div>
        </div>

        {{-- KIRIM SURAT FORM --}}
        <div class="form-container">
            <h3 class="form-header">KIRIM SURAT</h3>
            
            <form action="{{ route('surat.store') ?? '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- 1. Input: Kode Surat --}}
                <div class="mb-3">
                    <input type="text" class="form-control custom-input" id="kode_surat" name="kode_surat" value="01/SK-REKTOR/X/2025" placeholder="Kode Surat" required>
                </div>

                {{-- 2. Input: Title --}}
                <div class="mb-3">
                    <input type="text" class="form-control custom-input" id="title" name="title" value="SURAT KEPUTUSAN REKTOR" placeholder="Title" required>
                </div>

                {{-- 3. Input: Isi --}}
                <div class="mb-3">
                    <input type="text" class="form-control custom-input" id="isi" name="isi" value="PANITIA BAITUL ARQAM" placeholder="Isi" required>
                </div>

                {{-- 4. Input: File/Lampiran --}}
                <div class="mb-4">
                    <div class="file-input-wrapper">
                        <input type="text" class="form-control custom-input" id="file_display" value="baitularqam.pdf" readonly placeholder="Pilih File PDF">
                        <i class="bi bi-upload file-upload-icon" onclick="document.getElementById('file_upload').click()"></i>
                        <input type="file" class="d-none" id="file_upload" name="file" accept=".pdf, .docx" onchange="document.getElementById('file_display').value = this.files[0].name">
                    </div>
                </div>

                {{-- 5. Radio Buttons: Tujuan --}}
                <div class="custom-radio-group">
                    <p class="fw-bold text-dark fs-5">TUJUAN</p>
                    
                    {{-- Tujuan: REKTOR (White/Red circle) --}}
                    <label>
                        <input type="radio" class="custom-radio radio-rektor" name="tujuan" value="REKTOR">
                        REKTOR
                    </label>

                    {{-- Tujuan: DEKAN (Green circle) - Checked to match image --}}
                    <label>
                        <input type="radio" class="custom-radio radio-dekan" name="tujuan" value="DEKAN" checked>
                        DEKAN
                    </label>
                </div>

                {{-- Submit Button (Placeholder for an actual button) --}}
                <div class="d-grid">
                    {{-- The image doesn't show a submit button, but a form needs one. Adding one here. --}}
                    {{-- <button type="submit" class="btn btn-submit">Kirim Surat</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Script to ensure the file display input updates when a file is selected
    document.getElementById('file_upload').addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('file_display').value = this.files[0].name;
        } else {
            document.getElementById('file_display').value = 'Pilih File PDF';
        }
    });
</script>
</body>
</html>