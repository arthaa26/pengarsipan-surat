<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ARSIP - Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #4db8ff;
            font-family: 'Arial', sans-serif;
            color: #fff;
        }
        /* Menggunakan flexbox untuk layout penuh layar */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            background: #0066cc;
            min-height: 100vh;
            padding: 20px 10px;
            width: 250px; 
            flex-shrink: 0;
        }
        .sidebar-menu a {
            display: flex; 
            align-items: center;
            background: #0080ff;
            color: white;
            text-decoration: none;
            margin: 8px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .sidebar-menu a:hover {
            background: #0059b3;
        }
        .main-content-col {
            flex-grow: 1;
            padding: 20px;
        }
        .card-box {
            border-radius: 10px;
            padding: 20px;
            color: white;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 100px;
        }
        .card-green {
            background: #22c55e;
        }
        .card-orange {
            background: #f59e42; 
        }
        .card-box .number {
            font-size: 2.5rem;
            line-height: 1;
        }
        .card-box .icon {
            font-size: 2.5rem;
        }
        .table-container {
            background: #f7c948; 
            border-radius: 10px;
            padding: 0;
            overflow: hidden;
        }
        .table-header {
            background: #f7c948;
            color: #fff;
            padding: 15px 20px;
            font-size: 1.1rem;
            font-weight: bold;
        }
        /* Warna teks di dalam tabel ye */
        .table th, .table td {
            color: #000000ff; 
            border: none;
            padding: 15px 10px;
        }
        .table thead tr {
            color: #fff;
            font-weight: bold;
            background-color: #f7c948;
        }
        .table-responsive > .table {
            background-color: #f7c948;
        }
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        
        /* Dropdown custom styling */
        .profile-icon {
            cursor: pointer;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="app-layout">
    <div class="sidebar">
        <h4 class="text-white fw-bold mb-4">E-ARSIP</h4>
        <div class="sidebar-menu">
            <a href="#"><i class="bi bi-speedometer2 me-2"></i>DASHBOARD</a>
            <a href="#"><i class="bi bi-list-task me-2"></i>MENU</a>
            <a href="#"><i class="bi bi-folder-fill me-2"></i>DAFTAR SURAT</a>
            <a href="#"><i class="bi bi-send-fill me-2"></i>KIRIM SURAT</a>
        </div>
    </div>

    <div class="main-content-col">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <h2 class="fw-bold text-white">DASHBOARD USER</h2>
            
            <div class="dropdown">
                <div class="profile-icon" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-white" style="width:40px;height:40px;"></div>
                    <i class="bi bi-chevron-down ms-2 fs-3 text-white"></i>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person-circle me-2"></i>User Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-folder-fill me-2"></i>Surat</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
            </div>

        <div class="row g-4 mt-2">
            <div class="col-md-4 col-lg-3">
                <div class="card-box card-green">
                    <div>
                        <div class="text-uppercase" style="font-size: 0.9rem;">SURAT MASUK</div>
                        <div class="number">0</div>
                    </div>
                    <i class="bi bi-envelope-fill icon"></i>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card-box card-orange">
                    <div>
                        <div class="text-uppercase" style="font-size: 0.9rem;">SURAT KELUAR</div>
                        <div class="number">0</div>
                    </div>
                    <i class="bi bi-envelope-open-fill icon"></i>
                </div>
            </div>
        </div>

        <div class="table-container mt-5">
            <div class="table-header">HISTORY SURAT</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">id_Surat</th>
                            <th style="width: 15%;">Kode Surat</th>
                            <th style="width: 25%;">Title</th>
                            <th style="width: 30%;">Isi</th>
                            <th style="width: 10%;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="color: black;">
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <button class="btn btn-action btn-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-action btn-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>