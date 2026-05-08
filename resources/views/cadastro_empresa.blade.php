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

        input,
        select {
            padding: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
            background: #f2f2f2;
            transition: 0.2s;
        }

        input:focus,
        select:focus {
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

        @media(max-width:650px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

        }
    </style>
</head>

<body>

    <div class="container">

        <a href="{{ route('apresentacao') }}" class="btn-login">
            Voltar
        </a>

        <h1>Cadastro de Empresa</h1>

        <form action="{{ route('cadastro_empresa.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Nome da Empresa *</label>

                    <input type="text"
                        id="emp_nome"
                        name="emp_nome"
                        maxlength="255"
                        placeholder="Ex: Academia Gracie Barra"
                        required>
                </div>

                <div class="form-group">
                    <label>Apelido *</label>

                    <input type="text"
                        id="emp_apelido"
                        name="emp_apelido"
                        maxlength="255"
                        placeholder="Ex: Gracie Centro"
                        required>
                </div>

                <div class="form-group">
                    <label>Nome do Responsável *</label>

                    <input type="text"
                        id="emp_nome_responsavel"
                        name="emp_nome_responsavel"
                        maxlength="255"
                        placeholder="Ex: João Silva"
                        required>
                </div>

                <div class="form-group">
                    <label>Email do Responsável *</label>

                    <input type="email"
                        id="emp_email_responsavel"
                        name="emp_email_responsavel"
                        maxlength="255"
                        placeholder="Ex: joao@email.com"
                        required>
                </div>

                <div class="form-group">
                    <label>Telefone *</label>

                    <input type="text"
                        id="emp_telefone_responsavel"
                        name="emp_telefone_responsavel"
                        maxlength="15"
                        placeholder="(47) 99999-9999"
                        required>
                </div>

                <div class="form-group">
                    <label>CPF *</label>

                    <input type="text"
                        id="emp_cpf"
                        name="emp_cpf"
                        maxlength="14"
                        placeholder="000.000.000-00"
                        required>
                </div>

                <input type="hidden" name="emp_tipo" value="matriz">

                <div class="form-group full">
                    <label>Logo da Empresa</label>

                    <input type="file"
                        name="emp_foto"
                        id="logoInput"
                        accept="image/*">

                    <div style="margin-top:10px;">
                        <img id="previewLogo"
                            style="display:none;max-height:120px;border-radius:8px;">
                    </div>
                </div>

            </div>

            <button type="submit" class="btn">
                Cadastrar Empresa
            </button>

        </form>

    </div>

    <script>
        // PREVIEW DA IMAGEM
        const logoInput = document.getElementById("logoInput");

        logoInput.addEventListener("change", function(event) {

            const file = event.target.files[0];
            const preview = document.getElementById("previewLogo");

            if (file) {

                const reader = new FileReader();

                reader.onload = function(e) {

                    preview.src = e.target.result;
                    preview.style.display = "block";

                };

                reader.readAsDataURL(file);

            }

        });

        // SOMENTE LETRAS
        function apenasLetras(input) {

            input.addEventListener("input", function() {

                this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '');

            });

        }

        apenasLetras(document.getElementById("emp_nome"));
        apenasLetras(document.getElementById("emp_apelido"));
        apenasLetras(document.getElementById("emp_nome_responsavel"));

        // CPF
        const cpfInput = document.getElementById("emp_cpf");

        cpfInput.addEventListener("input", function() {

            let value = this.value.replace(/\D/g, '');

            value = value.substring(0, 11);

            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            this.value = value;

        });

        // TELEFONE
        const telefoneInput = document.getElementById("emp_telefone_responsavel");

        telefoneInput.addEventListener("input", function() {

            let value = this.value.replace(/\D/g, '');

            value = value.substring(0, 11);

            if (value.length > 10) {

                value = value.replace(
                    /^(\d{2})(\d{5})(\d{4}).*/,
                    '($1) $2-$3'
                );

            } else {

                value = value.replace(
                    /^(\d{2})(\d{4})(\d{4}).*/,
                    '($1) $2-$3'
                );

            }

            this.value = value;

        });

        // VALIDAÇÃO FINAL
        document.querySelector("form").addEventListener("submit", function(event) {

            const cpf = cpfInput.value;
            const telefone = telefoneInput.value;

            if (cpf.length < 14) {

                alert("CPF inválido!");
                event.preventDefault();
                return;

            }

            if (telefone.length < 14) {

                alert("Telefone inválido!");
                event.preventDefault();
                return;

            }

        });
    </script>

</body>

</html>