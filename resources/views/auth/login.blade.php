<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Academia Jiu-Jitsu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans",
                sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
        }

        body {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)),
                url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.85);
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
            color: white;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 26px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            outline: none;
        }

        .form-group input:focus {
            box-shadow: 0 0 8px #b22222;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #b22222;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #ff0000;
            transform: scale(1.03);
        }

        .extra-links {
            margin-top: 15px;
            text-align: center;
            font-size: 13px;
        }

        .extra-links a {
            color: #dddddd;
            text-decoration: none;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Acesso ao Sistema</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required>
                @error('password')
                <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <div class="remember">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Lembrar de mim</label>
            </div>

            <button type="submit" class="btn-login">Entrar</button>

            <div class="extra-links">
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Esqueceu a senha?</a>
                @endif
            </div>
        </form>
    </div>

</body>

</html>