<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
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
        .container-center {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100vw;
        }
        .login-title {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 32px;
            margin-top: 32px;
            letter-spacing: 2px;
        }
        .login-box {
            background: rgba(255,255,255,0.15);
            border-radius: 28px;
            padding: 50px 32px 32px 32px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            width: 100%;
            max-width: 420px;
            min-width: 280px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
        }
        .login-input {
            width: 100%;
            padding: 14px 1px 14px 1px;
            border-radius: 14px;
            border: none;
            margin-bottom: 20px;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        }
        .login-input::placeholder {
            color: #888;
            opacity: 1;
            font-size: 1.08rem;
        }
        .login-checkbox {
            margin-right: 8px;
            width: 22px;
            height: 22px;
        }
        .login-remember {
            display: flex;
            align-items: center;
            margin-bottom: 14px;
            width: 100%;
        }
        .login-btn {
            width: 100%;
            background: #fff;
            color: #222;
            border: none;
            border-radius: 24px;
            padding: 12px 0;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            transition: background 0.2s, color 0.2s;
        }
        .login-btn:hover {
            background: #1da1f2;
            color: #fff;
        }
        .login-footer {
            position: fixed;
            left: 0; right: 0; bottom: 0;
            background: #48a9e6;
            color: #fff;
            text-align: center;
            padding: 8px 0;
            font-size: 1rem;
            letter-spacing: 1px;
        }
        .container-center {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100vw;
            min-width: 0;
        }
    </style>
</head>
<body>
    <div class="container-center">
        <div class="login-title">MASUK</div>
        <div class="login-box">
        <form method="POST" action="/login">
            @csrf
            <input type="email" name="email" class="login-input" placeholder="Email" required autofocus>
            <input type="password" name="password" class="login-input" placeholder="**********" required>
            <div class="login-remember">
                <input type="checkbox" name="remember" id="remember" class="login-checkbox">
                <label for="remember" style="color:#fff;">Ingat Saya</label>
            </div>
            <button type="submit" class="login-btn">Masuk</button>
        </form>
        </div>
    </div>
    <div class="login-footer">LP3M UM PONTIANAK</div>
</body>
</html>
