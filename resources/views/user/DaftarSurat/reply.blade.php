<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Balas Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* BASE STYLES - DARI KIRIM SURAT */
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
            color: var(--color-text-dark); 
        }

        /* --- LAYOUT & SIDEBAR STYLES --- */
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
        .sidebar-menu a {
            display: flex; align-items: center; background: var(--color-sidebar-link);
            color: var(--color-text-white); text-decoration: none; margin: 8px 0;
            padding: 10px; border-radius: 5px; font-weight: bold; transition: background 0.2s;
        }
        .sidebar-menu a:hover { 
            background: var(--color-sidebar-link-hover); 
        }
        /* Style link aktif untuk Surat Masuk (jika diperlukan) */
        .sidebar-menu .active-link { 
            background: var(--color-text-white); 
            color: var(--color-text-dark); 
        }
        /* --- END SIDEBAR STYLES --- */
        
        .main-content-col { 
            flex-grow: 1; 
            padding: 20px; 
        }
        
        .kirim-surat-panel { 
            background-color: var(--color-kirim-surat-bg); 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
            max-width: 800px; 
            margin: 0 auto; /* Tengah di kolom utama */
        } 
        .kirim-surat-panel h4 { 
            color: var(--color-text-dark); 
            font-weight: bold; 
            margin-bottom: 25px; 
            border-bottom: 3px solid var(--color-text-dark); 
            padding-bottom: 10px; 
            display: inline-flex; /* Mengubah display agar ikon panah dan teks bisa sejajar */
            align-items: center;
        }
        .form-control-custom { 
            height: 50px; 
            border-radius: 10px; 
            border: 2px solid var(--color-text-dark); 
            font-weight: bold; 
            font-size: 1rem; 
            box-shadow: none !important; 
            color: var(--color-text-dark); 
            background-color: #ffffff; 
            padding: 12px 15px; 
        }
        textarea.form-control-custom { height: 120px !important; }

        .form-label-custom { color: var(--color-text-dark); font-weight: bold; font-size: 1.1rem; margin-bottom: 5px; }

        .original-content-panel { 
            background-color: #ffffff; 
            padding: 15px; 
            border-radius: 8px; 
            margin-top: 20px; 
            border-left: 5px solid var(--color-sidebar-primary); 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        .original-content-panel strong { color: var(--color-sidebar-primary); }
        .original-content-panel p { color: #555; }

        .btn-kirim-override {
            background-color: var(--color-button-kirim); color: var(--color-text-white);
            font-weight: bold; padding: 10px 30px; border-radius: 8px;
            border: none; transition: background-color 0.3s;
        }
        .btn-kirim-override:hover {
            background-color: var(--color-button-kirim-hover); color: var(--color-text-white);
        }
    </style>
</head>
<body>

{{-- START: WRAPPER UTAMA --}}
<div class="app-layout">
    
    {{-- START: SIDEBAR --}}
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
            {{-- Sesuaikan tautan aktif --}}
            <a href="#"><i class="bi bi-list-task me-2"></i>MENU</a>
            <a href="{{ route('user.dashboard') ?? '#' }}"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            <a href="{{ route('user.daftar_surat.masuk') ?? '#' }}" class="active-link"><i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT</a>
            <a href="{{ route('user.kirim_surat.index') ?? '#' }}"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>
    {{-- END: SIDEBAR --}}

    {{-- START: CONTENT UTAMA --}}
    <div class="main-content-col">
        {{-- Header Bar (Opsional, jika ada) --}}
        <div class="mb-4">
            <h2 class="fw-bold text-white">FORMULIR BALAS SURAT</h2>
        </div>

        <div class="kirim-surat-panel">
            @php
                // 🛑 PERBAIKAN: Gunakan $surat secara langsung dan definisikan variabel ID dan Sender di sini.
                // Model Binding memastikan $surat (KirimSurat) ada di sini.
                $suratId = $surat->id;
                $senderId = $surat->user_id_1;
                
                // Safety check dan display variables (menggunakan nullsafe operator untuk relasi)
                $recipientName = $surat->user1?->name ?? 'Pengirim Surat Asli Tidak Dikenal';
                $originalTitle = $surat->title ?? 'Tanpa Judul';
                $replyTitleDefault = 'Re: ' . \Illuminate\Support\Str::limit($originalTitle, 40);

                // $nextKode dilewatkan dari Controller
                $nextKode = $nextKode ?? 'KODE-BALASAN-001'; 
            @endphp

            {{-- JUDUL FORMULIR YANG DIIMPROVISASI MENJADI TOMBOL KEMBALI --}}
            <h4 class="text-uppercase">
                <a href="{{ route('user.dashboard') ?? '#' }}" 
                   style="color: var(--color-text-dark); text-decoration: none; margin-right: 10px; font-size: 1.5rem;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                FORMULIR BALAS SURAT
            </h4>
            
            {{-- Pesan Error Global --}}
            @if ($errors->any() || session('error'))
                <div class="alert alert-danger" style="color: var(--color-text-dark); background-color: #f8d7da; border-color: #f5c6cb;">
                    <strong>Gagal!</strong> Periksa kembali input Anda.
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        @if(session('error'))
                            <li>{{ session('error') }}</li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- Form action diarahkan ke route sendReply dengan parameter ID surat asli --}}
            <form method="POST" action="{{ route('DaftarSurat.reply.send', $suratId) }}" enctype="multipart/form-data">
                @csrf
                
                {{-- ID Penerima (Hidden Field: Pengirim asli user_id_1 menjadi target_user_id) --}}
                <input type="hidden" name="target_user_id" value="{{ $senderId }}">
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="kode_surat" class="form-label-custom">KODE SURAT BALASAN</label>
                        <input type="text" class="form-control form-control-custom" id="kode_surat" 
                                value="{{ $nextKode }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">TUJUAN BALASAN (TERKUNCI)</label>
                        <input type="text" class="form-control form-control-custom" value="{{ $recipientName }}" disabled>
                    </div>
                </div>

                {{-- TITLE (Subjek) --}}
                <div class="mb-4">
                    <label for="title" class="form-label-custom">SUBJEK BALASAN</label>
                    <input type="text" name="title" id="title" 
                            class="form-control form-control-custom @error('title') is-invalid @enderror" 
                            value="{{ old('title', $replyTitleDefault) }}" required>
                    @error('title')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- ISI BALASAN --}}
                <div class="mb-4">
                    <label for="isi" class="form-label-custom">ISI BALASAN ANDA</label>
                    <textarea name="isi" id="isi" rows="6" 
                                  class="form-control form-control-custom @error('isi') is-invalid @enderror" 
                                  style="height: 120px !important;" required>{{ old('isi', 'Menanggapi surat Anda dengan kode: ' . ($surat->kode_surat ?? 'N/A') . ', kami sampaikan...') }}</textarea>
                    @error('isi')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
                
                {{-- UPLOAD FILE --}}
                <div class="mb-4">
                    <label for="upload_file" class="form-label-custom">FILE LAMPIRAN BALASAN (Opsional)</label>
                    <div class="input-group">
                        {{-- Hapus atribut 'required' --}}
                        <input type="file" class="form-control d-none @error('file_surat') is-invalid @enderror" id="upload_file" name="file_surat"> 
                        <input type="text" class="form-control form-control-custom" id="file_display" placeholder="Pilih file lampiran..." readonly style="cursor: pointer;" onclick="document.getElementById('upload_file').click();">
                        <span class="input-group-text" onclick="document.getElementById('upload_file').click();"><i class="bi bi-upload"></i></span>
                    </div>
                    @error('file_surat')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- KUTIPAN SURAT ASLI --}}
                <div class="original-content-panel mb-4">
                    <strong>Kutipan Surat Asli (Subjek: {{ $originalTitle }}):</strong>
                    <hr class="my-2">
                    <p class="small m-0">{{ $surat->isi ?? 'Konten asli tidak tersedia.' }}</p>
                </div>

                {{-- Submit and Cancel Buttons --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('user.dashboard') ?? '#' }}" class="btn btn-secondary me-3"><i class="bi bi-x-circle me-1"></i> Batal</a>
                    <button type="submit" class="btn btn-kirim-override"><i class="bi bi-send-fill me-1"></i> Kirim Balasan</button>
                </div>
            </form>
        </div>
    </div>
    {{-- END: CONTENT UTAMA --}}
</div>
{{-- END: WRAPPER UTAMA --}}

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // File input display logic
    $('#upload_file').on('change', function() {
        const fileName = this.files.length > 0 ? this.files[0].name : 'Pilih file lampiran...';
        $('#file_display').val(fileName);
    });
});
</script>
</body>
</html>