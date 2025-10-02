<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di E-Arsip</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #1da1f2 0%, #0077b6 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .logo {
            width: 120px;
            margin-bottom: 24px;
        }
        .title {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 2px;
            margin-bottom: 32px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .btn-masuk {
            background: #fff;
            color: #222;
            border: none;
            border-radius: 24px;
            padding: 12px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            transition: background 0.2s, color 0.2s;
        }
        .btn-masuk:hover {
            background: #1da1f2;
            color: #fff;
        }
    </style>
</head>
<body>
    <img src="/images/unmuh.png" alt="Logo UNMUH" class="logo">
    <div class="title">
        SELAMAT DATANG DI E-ARSIP<br>
        UNIVERSITAS MUHAMMADIYAH<br>
        PONTIANAK
    </div>
    <a href="/login">
        <button class="btn-masuk">Masuk</button>
    </a>
</body>
</html>
