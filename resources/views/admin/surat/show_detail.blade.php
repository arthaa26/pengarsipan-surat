@php
// Asumsi: $surat sudah tersedia dari controller
$pengirim = $surat->user1->name ?? 'N/A';
$fakultasPengirim = $surat->user1->faculty->name ?? '-';

// Logika Tujuan
$tujuanDisplay = '';
if ($surat->user2 ?? false) {
$tujuanDisplay = $surat->user2->name . ' (' . ($surat->user2->faculty->code ?? 'Internal') . ')';
} elseif ($surat->tujuanFaculty ?? false) {
$tujuanDisplay = $surat->tujuanFaculty->name . ' (' . ucwords($surat->tujuan) . ')';
} else {
$tujuanDisplay = ucwords(str_replace('_', ' ', $surat->tujuan));
}

$tanggalKirim = \Carbon\Carbon::parse($surat->created_at)->format('d F Y, H:i');
@endphp

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Surat - {{ $surat->kode_surat ?? 'N/A' }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root {
--color-bg-body: #4db8ff;
--color-sidebar-primary: #0066cc;
--color-text-dark: #000000;
}
body { background: var(--color-bg-body); font-family: 'Arial', sans-serif; }
.detail-container {
background: #ffffff;
border-radius: 12px;
padding: 30px;
box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
max-width: 900px;
margin: 40px auto;
color: var(--color-text-dark);
}
.header-detail {
border-bottom: 2px solid #eee;
padding-bottom: 15px;
margin-bottom: 20px;
}
.info-label {
font-weight: bold;
color: var(--color-sidebar-primary);
}
.action-link {
transition: all 0.2s;
cursor: pointer;
}
.action-link:hover {
opacity: 0.8;
}
.badge-status {
font-size: 0.9em;
font-weight: 600;
padding: 5px 10px;
border-radius: 5px;
}
</style>
</head>
<body>
<div class="container">
<div class="detail-container">
<div class="d-flex justify-content-between align-items-center header-detail">
<h3 class="mb-0 text-dark">Detail Surat</h3>
<a href="{{ route('admin.dashboard') ?? '#' }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
</div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <p class="info-label">Kode Surat:</p>
            <p class="text-dark">{{ $surat->kode_surat ?? 'N/A' }}</p>
        </div>
        <div class="col-md-6 mb-3">
            <p class="info-label">Judul Surat:</p>
            <p class="text-dark">{{ $surat->title ?? 'N/A' }}</p>
        </div>
        <div class="col-md-6 mb-3">
            <p class="info-label">Pengirim:</p>
            <p class="text-dark">{{ $pengirim }}</p>
        </div>
        <div class="col-md-6 mb-3">
            <p class="info-label">Fakultas Pengirim:</p>
            <p class="text-dark">{{ $fakultasPengirim }}</p>
        </div>
        <div class="col-md-6 mb-3">
            <p class="info-label">Tujuan:</p>
            <p class="text-dark">{{ $tujuanDisplay }}</p>
        </div>
        <div class="col-md-6 mb-3">
            <p class="info-label">Tanggal Kirim:</p>
            <p class="text-dark">{{ $tanggalKirim }}</p>
        </div>

        <div class="col-12 mb-4">
            <p class="info-label">Isi Ringkas/Keterangan:</p>
            <div class="p-3 bg-light rounded text-dark border">
                {!! nl2br(e($surat->isi ?? 'Tidak ada keterangan isi surat.')) !!}
            </div>
        </div>

        <div class="col-12 mt-3 pt-3 border-top">
            <p class="info-label mb-2">File Lampiran:</p>
            @if ($surat->file_path)
                <div class="d-flex gap-3 align-items-center">
                    <a href="{{ route('admin.surat.view_file', $surat->id) ?? '#' }}" 
                       class="action-link text-info d-flex align-items-center" 
                       target="_blank">
                        <i class="bi bi-eye-fill me-1"></i> Lihat File
                    </a>
                    |
                    <a href="{{ route('admin.surat.download', $surat->id) ?? '#' }}" 
                       class="action-link text-success d-flex align-items-center">
                        <i class="bi bi-download me-1"></i> Download File
                    </a>
                    |
                    <span class="badge-status bg-secondary text-white">{{ basename($surat->file_path) }}</span>
                </div>
            @else
                <p class="text-muted">Tidak ada file lampiran.</p>
            @endif
        </div>
    </div>
        {{-- START: FOOTER HAK CIPTA --}}
        @include('partials.footer')
        {{-- END: FOOTER HAK CIPTA --}}
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 