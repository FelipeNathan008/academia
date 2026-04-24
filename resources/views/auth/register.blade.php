<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)),
                url('/images/tela_inicial.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .container {
            background: rgba(0, 0, 0, 0.85);
            padding: 40px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 4px;
            color: #ddd;
        }

        input {
            padding: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
            background: #f2f2f2;
            transition: 0.2s;
        }

        input:focus {
            background: white;
            box-shadow: 0 0 0 2px #8E251F;
        }

        .btn {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            background: #8E251F;
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: 0.25s;
        }

        .btn:hover {
            background: #732920;
            transform: scale(1.03);
        }

        .link {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .link a {
            color: #ccc;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .erro {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 2px;
        }

    </style>

</head>

<body>

    <div class="container">

        <h1>Criar Usuário Administrador</h1>

        <form method="POST" action="{{ url('register/'.$empresa_id) }}">
            @csrf

            <div class="form-group">
                <label>Nome *</label>
                <input type="text" name="name" placeholder="Ex: João Silva" required>
                @error('name')
                <div class="erro">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" placeholder="Ex: joao@email.com" required>
                @error('email')
                <div class="erro">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Senha *</label>
                <input type="password" name="password" placeholder="Digite uma senha segura" required>
                @error('password')
                <div class="erro">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirmar Senha *</label>
                <input type="password" name="password_confirmation" placeholder="Repita a senha" required>
            </div>

            <input type="hidden" name="role" value="admin">

            <button type="submit" class="btn">
                Criar Conta
            </button>

            <div class="link">
                <a href="{{ route('login') }}">Já possui conta?</a>
            </div>

        </form>

    </div>

</body>

</html>