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
                sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, .75), rgba(0, 0, 0, .75)),
            url("{{ asset('images/tela_inicial.jpg') }}");
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px;
            border-radius: 6px;
            border: none;
            outline: none;
            background: white;
            color: #222;
        }

        .form-group input:focus,
        .form-group select:focus {
            box-shadow: 0 0 8px #b22222;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);

            background: none;
            border: none;
            color: #777;

            cursor: pointer;
            padding: 4px;

            display: flex;
            align-items: center;
            justify-content: center;

            transition: 0.2s ease;
        }

        .toggle-password:hover {
            color: #222;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;

            border: none;
            border-radius: 8px;

            background-color: #8E251F;
            color: white;

            font-weight: bold;
            cursor: pointer;

            transition: 0.3s ease;

            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #732920;
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

        .remember input {
            width: auto;
        }

        .btn-voltar {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            color: #ccc;
            text-decoration: none;

            margin-bottom: 20px;

            transition: 0.2s ease;
        }

        .btn-voltar:hover {
            color: white;
        }

        .erro {
            display: block;
            color: #ff6b6b;
            margin-top: 5px;
            font-size: 13px;
        }

        .info-perfil {
            font-size: 12px;
            color: #aaa;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <a href="{{ route('apresentacao') }}"
            class="btn-voltar"
            aria-label="Voltar">

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

        <h2>Acesso ao Sistema</h2>

        <form method="POST" action="{{ route('login') }}">

            @csrf

            {{-- EMAIL --}}
            <div class="form-group">

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="academia@email.com"
                    value="{{ old('email') }}"
                    required
                    autofocus>

                @error('email')
                <small class="erro">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- PERFIL --}}
            <div class="form-group">

                <label for="role">
                    Perfil de acesso
                </label>

                <select name="role" id="role" required>
                    <option value="">
                        Selecione seu perfil
                    </option>

                    <option value="admin">
                        Administrador
                    </option>

                    <option value="professor">
                        Professor
                    </option>

                    <option value="aluno">
                        Aluno
                    </option>

                    <option value="superadmin">
                        Super Administrador
                    </option>
                </select>

                <div class="info-perfil">
                    Escolha o perfil que deseja acessar.
                </div>

                @error('role')
                <small class="erro">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- SENHA --}}
            <div class="form-group">

                <label for="password">
                    Senha
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="**********"
                        required>

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        onclick="toggleSenha()"
                        aria-label="Mostrar senha">

                        <svg
                            id="icone-olho"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>

                            <circle cx="12" cy="12" r="3"></circle>

                        </svg>

                    </button>

                </div>

                @error('password')
                <small class="erro">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- LEMBRAR --}}
            <div class="remember">

                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    {{ old('remember') ? 'checked' : '' }}>

                <label for="remember">
                    Lembrar de mim
                </label>

            </div>


            {{-- BOTÃO --}}
            <button
                type="submit"
                class="btn-login">

                Entrar

            </button>


            {{-- ESQUECI SENHA --}}
            <div class="extra-links">

                @if (Route::has('password.request'))

                <a href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>

                @endif

            </div>

        </form>

    </div>


    <script>
        function toggleSenha() {

            const input = document.getElementById('password');
            const icone = document.getElementById('icone-olho');
            const botao = document.getElementById('togglePassword');

            if (input.type === 'password') {

                input.type = 'text';

                icone.innerHTML = `
                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>

                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c5 0 8.73 4.17 9.5 7a1 1 0 0 1 0 .7 10.75 10.75 0 0 1-4.1 5.18"></path>

                    <path d="M6.61 6.61A10.75 10.75 0 0 0 2.5 12.3a1 1 0 0 0 0 .7C3.27 15.83 7 20 12 20a10.43 10.43 0 0 0 2.73-.36"></path>

                    <line x1="2" y1="2" x2="22" y2="22"></line>
                `;

                botao.setAttribute(
                    'aria-label',
                    'Ocultar senha'
                );

            } else {

                input.type = 'password';

                icone.innerHTML = `
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>

                    <circle cx="12" cy="12" r="3"></circle>
                `;

                botao.setAttribute(
                    'aria-label',
                    'Mostrar senha'
                );
            }
        }
    </script>

</body>

</html>