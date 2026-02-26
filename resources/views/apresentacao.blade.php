<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Academia de Jiu-Jitsu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        .container h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .container p {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
            color: #dddddd;
        }

        .btn-login {
            display: inline-block;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #b22222;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #b22222;
            transform: scale(1.05);
        }

        footer {
            margin-top: 25px;
            font-size: 12px;
            color: #aaaaaa;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Sistema Academia de Jiu-Jitsu</h1>
        <p>
            Bem-vindo ao sistema de gerenciamento da nossa academia de Jiu-Jitsu.
            Aqui você poderá acompanhar alunos, turmas, graduações e controlar
            informações importantes de forma prática e organizada.
        </p>
        
        <a href="{{ route('login') }}" class="btn-login"> Login</a>

        <footer>
            © 2026 Academia Jiu-Jitsu - Todos os direitos reservados
        </footer>
    </div>

</body>
</html>
