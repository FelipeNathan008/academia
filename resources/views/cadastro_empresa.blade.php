<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Empresa</title>

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
            max-width: 750px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1 / span 2;
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

        input[type="file"] {
            background: none;
            color: white;
        }

        .btn {
            width: 100%;
            padding: 14px;
            margin-top: 25px;
            font-size: 16px;
            font-weight: bold;
            background: #8E251F;
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: 0.25s;
            letter-spacing: 1px;
        }

        .btn:hover {
            background: #732920;
            transform: scale(1.03);
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

        .erro {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 2px;
        }


        @media(max-width:650px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }
        }

        select {
            padding: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
            background: #f2f2f2;
            transition: 0.2s;
        }

        select:focus {
            background: white;
            box-shadow: 0 0 0 2px #8E251F;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="{{ route('apresentacao') }}" class="btn-login">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5A.5.5 0 0 1 15 8z" />
            </svg>
        </a>
        <h1>Cadastro de Empresa</h1>

        <form action="{{ route('empresa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Nome da Empresa *</label>
                    <input type="text" name="emp_nome" placeholder="Ex: Academia Gracie Barra" required>
                </div>

                <div class="form-group">
                    <label>Apelido *</label>
                    <input type="text" name="emp_apelido" placeholder="Ex: Gracie Centro" required>
                </div>

                <div class="form-group">
                    <label>Nome do Responsável *</label>
                    <input type="text" name="emp_nome_responsavel" placeholder="Ex: João Silva" required>
                </div>

                <div class="form-group">
                    <label>Email do Responsável *</label>
                    <input type="email" name="emp_email_responsavel" placeholder="Ex: joao@email.com" required>
                </div>

                <div class="form-group">
                    <label>Telefone *</label>
                    <input type="text" name="emp_telefone_responsavel" placeholder="Ex: (47) 99999-9999" required>
                </div>

                <div class="form-group">
                    <label>CPF *</label>
                    <input type="text" name="emp_cpf" placeholder="Ex: 000.000.000-00" required>
                </div>

                <div class="form-group full">
                    <label>Tipo de Empresa *</label>
                    <select name="emp_tipo" required>
                        <option value="">Selecione o tipo</option>
                        <option value="matriz">Matriz</option>
                        <option value="filial">Filial</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Logo da Empresa</label>
                    <input type="file" name="emp_foto" id="logoInput" accept="image/*">

                    <div style="margin-top:10px;">
                        <img id="previewLogo" style="display:none;max-height:120px;border-radius:8px;">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn">
                Cadastrar Empresa
            </button>

        </form>


    </div>
    <script>
        document.getElementById("logoInput").addEventListener("change", function(event) {

            const file = event.target.files[0];
            const preview = document.getElementById("previewLogo");

            if (file) {

                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                }

                reader.readAsDataURL(file);

            }

        });
    </script>
</body>

</html>