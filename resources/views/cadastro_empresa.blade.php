<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro da Empresa</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
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

        .container {
            width: 100%;
            max-width: 850px;
            background: rgba(0, 0, 0, .82);
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .45);
        }

        h1 {
            color: #FFF;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .subtitulo {
            color: #d8d8d8;
            text-align: center;
            margin-bottom: 35px;
            font-size: 15px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .campo {
            display: flex;
            flex-direction: column;
        }

        .campo-full {
            grid-column: span 2;
        }

        label {
            color: white;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #555;
            background: rgba(255, 255, 255, .08);
            color: white;
            font-size: 15px;
            transition: .3s;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #174ab9;
            box-shadow: 0 0 8px rgba(23, 74, 185, .4);
        }

        input::placeholder {
            color: #bdbdbd;
        }

        input[type=file] {
            padding: 10px;
        }

        select option {
            color: black;
        }

        .acoes {
            margin-top: 35px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 13px 35px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: .3s;
        }

        .btn-primary {
            background: #174ab9;
            color: white;
        }

        .btn-primary:hover {
            background: #123d9a;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #8E251F;
            color: white;
        }

        .btn-secondary:hover {
            background: #74201b;
            transform: translateY(-2px);
        }

        .alert {
            background: rgba(142, 37, 31, .18);
            border: 1px solid #8E251F;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .alert ul {
            margin-left: 20px;
        }

        @media(max-width:768px) {

            .container {
                padding: 25px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .campo-full {
                grid-column: span 1;
            }

            h1 {
                font-size: 26px;
            }

        }
    </style>
</head>

<body>

    <div class="container">

        <h1>Cadastro da Empresa</h1>
        <x-alert-error />

        <p class="subtitulo">
            Preencha os dados abaixo para cadastrar sua academia no sistema.
        </p>



        <form id="cadastroForm" action="{{ route('cadastro_empresa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid">

                <div class="campo">
                    <label>Nome da Empresa</label>

                    <input
                        type="text"
                        name="emp_nome"
                        value="{{ old('emp_nome') }}"
                        placeholder="Academia Exemplo"
                        required>
                </div>

                <div class="campo">
                    <label>Nome Fantasia / Apelido</label>

                    <input
                        type="text"
                        name="emp_apelido"
                        value="{{ old('emp_apelido') }}"
                        placeholder="Ex.: Team Silva"
                        required>
                </div>

                <div class="campo">
                    <label>Responsável</label>

                    <input
                        type="text"
                        name="emp_nome_responsavel"
                        value="{{ old('emp_nome_responsavel') }}"
                        placeholder="Nome do responsável"
                        required>
                </div>

                <div class="campo">
                    <label>E-mail</label>

                    <input
                        type="email"
                        name="emp_email_responsavel"
                        value="{{ old('emp_email_responsavel') }}"
                        placeholder="email@empresa.com"
                        required>
                </div>

                <div class="campo">
                    <label>Telefone</label>

                    <input
                        type="text"
                        id="emp_telefone"
                        name="emp_telefone_responsavel"
                        value="{{ old('emp_telefone_responsavel') }}"
                        placeholder="(00) 00000-0000"
                        maxlength="15"
                        required>
                </div>

                <div class="campo">
                    <label>CPF do Responsável</label>

                    <input
                        type="text"
                        id="emp_cpf"
                        name="emp_cpf"
                        value="{{ old('emp_cpf') }}"
                        placeholder="000.000.000-00"
                        maxlength="14"
                        required>
                </div>

                <div class="campo">
                    <label>Chave PIX</label>

                    <input
                        type="text"
                        name="emp_chave_pix"
                        value="{{ old('emp_chave_pix') }}"
                        placeholder="CPF, telefone, e-mail ou chave aleatória"
                        required>
                </div>

                <div class="campo">
                    <label>Nome do Recebedor (PIX) Limite de 25 caracteres</label>

                    <input
                        type="text"
                        name="emp_nome_recebedor"
                        value="{{ old('emp_nome_recebedor') }}"
                        placeholder="Ex.: ACADEMIA SILVA"
                        maxlength="25"
                        required>
                </div>

                <div class="campo">
                    <label>Cidade (PIX) Limite de 15 caracteres</label>

                    <input
                        type="text"
                        name="emp_cidade"
                        value="{{ old('emp_cidade') }}"
                        placeholder="Ex.: SAO PAULO"
                        maxlength="15"
                        required>
                </div>

                <input type="hidden" name="emp_tipo" value="Matriz">

                <div class="campo campo-full">

                    <label>Logo da Empresa </label>

                    <input
                        type="file" id="emp_foto" name="emp_foto" accept="image/*">

                    <div id="previewContainer" style="display:none;margin-top:15px;text-align:center;">

                        <img
                            id="preview" src="#" alt="Preview" style="width:150px;height:150px;object-fit:cover;border-radius:12px;
                                border:2px solid rgba(255,255,255,.15);box-shadow:0 5px 15px rgba(0,0,0,.4);">
                    </div>
                </div>

            </div>

            <div class="acoes">

                <a href="{{ route('apresentacao') }}" class="btn btn-secondary">
                    Voltar
                </a>

                <button type="submit" id="btnSubmit" class="btn btn-primary">
                    Continuar Cadastro
                </button>

            </div>

        </form>

    </div>

</body>
<script>
    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
        document.getElementById('formCadastro').reset();
    }

    // Bloquear múltiplos envios do formulário
    document.getElementById('cadastroForm').addEventListener('submit', function() {

        const btn = document.getElementById('btnSubmit');

        btn.disabled = true;

        btn.innerHTML = "Cadastrando...";

        btn.style.opacity = "0.7";

    });

    const inputFoto = document.getElementById('emp_foto');
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('previewContainer');

    inputFoto.addEventListener('change', function() {

        const arquivo = this.files[0];

        if (arquivo) {

            const reader = new FileReader();

            reader.onload = function(e) {

                preview.src = e.target.result;
                previewContainer.style.display = "block";

            }

            reader.readAsDataURL(arquivo);

        } else {

            previewContainer.style.display = "none";

        }

    });


    // CPF
    document.getElementById('emp_cpf').addEventListener('input', function(e) {

        let v = e.target.value.replace(/\D/g, '');

        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        e.target.value = v;

    });


    // Telefone
    document.getElementById('emp_telefone').addEventListener('input', function(e) {

        let v = e.target.value.replace(/\D/g, '');

        if (v.length > 11)
            v = v.substring(0, 11);

        if (v.length > 10) {

            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d{5})(\d)/, '$1-$2');

        } else {

            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d{4})(\d)/, '$1-$2');

        }

        e.target.value = v;

    });
</script>

</html>