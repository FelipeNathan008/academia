<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Esqueci minha senha - Academia Jiu-Jitsu</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)),
                url('images/tela_inicial.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(0, 0, 0, 0.85);

            padding: 40px;

            border-radius: 15px;

            width: 100%;
            max-width: 420px;

            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);

            color: white;
        }

        .voltar {
            display: inline-flex;
            align-items: center;

            color: #ccc;
            text-decoration: none;

            margin-bottom: 20px;

            transition: 0.2s ease;
        }

        .voltar:hover {
            color: white;
        }

        h2 {
            text-align: center;

            margin-bottom: 15px;

            font-size: 26px;
        }

        .descricao {
            text-align: center;

            color: #ccc;

            font-size: 14px;

            line-height: 1.5;

            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;

            margin-bottom: 6px;

            font-size: 14px;
        }

        input {
            width: 100%;

            padding: 11px;

            border-radius: 6px;

            border: none;

            outline: none;
        }

        input:focus {
            box-shadow: 0 0 8px #b22222;
        }

        .btn {
            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 8px;

            background-color: #8E251F;

            color: white;

            font-weight: bold;

            cursor: pointer;

            transition: 0.3s ease;
        }

        .btn:hover {
            background-color: #732920;

            transform: scale(1.02);
        }

        .mensagem {
            background: rgba(34, 197, 94, 0.15);

            border: 1px solid rgba(34, 197, 94, 0.4);

            color: #86efac;

            padding: 10px;

            border-radius: 6px;

            font-size: 13px;

            margin-bottom: 18px;
        }

        .erro {
            color: #ff6b6b;

            font-size: 13px;

            margin-top: 5px;

            display: block;
        }

        .login {
            text-align: center;

            margin-top: 20px;

            font-size: 13px;
        }

        .login a {
            color: #ddd;

            text-decoration: none;
        }

        .login a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">

        <a href="{{ route('login') }}" class="voltar" aria-label="Voltar">

            <svg xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">

                <path d="M19 12H5"></path>
                <path d="M12 19l-7-7 7-7"></path>

            </svg>

        </a>

        <h2>Esqueci minha senha</h2>

        <div class="descricao">
            Informe o e-mail cadastrado no sistema.
            Enviaremos um link para você criar uma nova senha.
        </div>

        @if (session('status'))
        <div class="mensagem">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">

            @csrf

            <div class="form-group">

                <label for="email">
                    E-mail
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="academia@email.com"
                    required
                    autofocus>

                @error('email')
                <span class="erro">
                    {{ $message }}
                </span>
                @enderror

            </div>

            <button type="submit" class="btn">
                Enviar link para redefinir senha
            </button>

        </form>

        <div class="login">

            <a href="{{ route('login') }}">
                Voltar para o login
            </a>

        </div>

    </div>

</body>

</html>