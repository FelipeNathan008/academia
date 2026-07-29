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

            max-width: 600px;

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



        .form-group {

            display: flex;

            flex-direction: column;

            margin-bottom: 20px;

        }



        label {

            color: white;

            margin-bottom: 8px;

            font-weight: bold;

            font-size: 14px;

        }



        input {

            padding: 12px 15px;

            border-radius: 8px;

            border: 1px solid #555;

            background: rgba(255, 255, 255, .08);

            color: white;

            font-size: 15px;

            transition: .3s;

        }



        input:focus {

            outline: none;

            border-color: #174ab9;

            box-shadow: 0 0 8px rgba(23, 74, 185, .4);

        }



        input::placeholder {

            color: #bdbdbd;

        }



        .btn {

            width: 100%;

            padding: 13px 35px;

            border: none;

            border-radius: 8px;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;

            transition: .3s;

            background: #174ab9;

            color: white;

        }



        .btn:hover {

            background: #123d9a;

            transform: translateY(-2px);

        }



        .link {

            margin-top: 20px;

            text-align: center;

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

            margin-top: 5px;

        }



        .alert {

            background: rgba(142, 37, 31, .18);

            border: 1px solid #8E251F;

            color: white;

            padding: 15px;

            border-radius: 8px;

            margin-bottom: 25px;

        }



        @media(max-width:768px) {

            .container {

                padding: 25px;

            }


            h1 {

                font-size: 26px;

            }

        }
    </style>

</head>

<body>

    <div class="container">

        <h1>Cadastro de Usuário</h1>

        <p class="subtitulo">
            Crie o usuário administrador da sua empresa para acessar o sistema.
        </p>
        <form id="cadastroForm" method="POST" action="{{ url('register/'.$empresa_id) }}">
            @csrf

            <div class="form-group">
                <label>Nome *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: João Silva" required>
                @error('name')
                <div class="erro">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Ex: joao@email.com" required>
                @error('email')
                <div class="erro">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Senha *</label>

                <div style="position: relative;">
                    <input
                        type="password"
                        id="senha"
                        name="password"
                        placeholder="Digite uma senha segura"
                        required
                        oninput="verificarSenha(this.value)"
                        style="padding-right:45px;">

                    <button
                        type="button"
                        onclick="toggleSenha()"
                        style="
                position:absolute;
                right:12px;
                top:50%;
                transform:translateY(-50%);
                background:none;
                border:none;
                color:#ccc;
                cursor:pointer;
                font-size:16px;">
                        <span id="icone-olho">👁</span>
                    </button>
                </div>

                @error('password')
                <div class="erro">{{ $message }}</div>
                @enderror

                <!-- Barra -->
                <div style="margin-top:10px;height:8px;background:#444;border-radius:999px;overflow:hidden;">
                    <div id="barra-forca"
                        style="height:100%;width:0%;transition:.3s;">
                    </div>
                </div>

                <p id="texto-forca"
                    style="font-size:12px;margin-top:6px;color:#ccc;font-weight:bold;">
                </p>

                <ul style="margin-top:10px;font-size:13px;color:#ccc;line-height:24px;">
                    <li id="check-min">✗ Mínimo 8 caracteres</li>
                    <li id="check-maiuscula">✗ Letra maiúscula</li>
                    <li id="check-numero">✗ Número</li>
                    <li id="check-especial">✗ Caractere especial (!@#$...)</li>
                </ul>
            </div>

            <div class="form-group">
                <label>Confirmar Senha *</label>
                <input type="password" name="password_confirmation" placeholder="Repita a senha" required>
            </div>

            <input type="hidden" name="role" value="admin">

            <button type="submit" id="btnSubmit" class="btn">
                Criar Conta
            </button>

            <div class="link">
                <a href="{{ route('login') }}">Já possui conta?</a>
            </div>

        </form>

    </div>

</body>
<script>
    document.getElementById('cadastroForm').addEventListener('submit', function(e) {

        const senha = document.getElementById('senha').value;

        const forte =
            senha.length >= 8 &&
            /[A-Z]/.test(senha) &&
            /[0-9]/.test(senha) &&
            /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(senha);

        if (!forte) {
            e.preventDefault();
            alert('A senha não atende aos requisitos mínimos de segurança.');
            return;
        }

        const btn = document.getElementById('btnSubmit');

        btn.disabled = true;
        btn.innerHTML = "Cadastrando...";
        btn.style.opacity = "0.7";
    });

    function verificarSenha(valor) {

        const checks = {
            min: valor.length >= 8,
            maiuscula: /[A-Z]/.test(valor),
            numero: /[0-9]/.test(valor),
            especial: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(valor),
        };
        atualizarCheck('check-min', checks.min, 'Mínimo 8 caracteres');
        atualizarCheck('check-maiuscula', checks.maiuscula, 'Letra maiúscula');
        atualizarCheck('check-numero', checks.numero, 'Número');
        atualizarCheck('check-especial', checks.especial, 'Caractere especial (!@#$...)');
        const pontos = Object.values(checks).filter(Boolean).length;
        const barra = document.getElementById('barra-forca');
        const texto = document.getElementById('texto-forca');
        const niveis = [{
                label: '',
                cor: '',
                largura: '0%'
            },
            {
                label: 'Muito fraca',
                cor: '#ef4444',
                largura: '20%'
            },
            {
                label: 'Fraca',
                cor: '#f97316',
                largura: '45%'
            },
            {
                label: 'Média',
                cor: '#eab308',
                largura: '65%'
            },
            {
                label: 'Forte',
                cor: '#22c55e',
                largura: '100%'
            }
        ];

        const nivel = niveis[pontos];

        barra.style.width = nivel.largura;
        barra.style.backgroundColor = nivel.cor;

        texto.innerHTML = nivel.label;
        texto.style.color = nivel.cor;
    }

    function atualizarCheck(id, passou, texto) {
        const el = document.getElementById(id);
        if (passou) {
            el.innerHTML = '✓ ' + texto;
            el.style.color = '#22c55e';
        } else {
            el.innerHTML = '✗ ' + texto;
            el.style.color = '#ccc';
        }
    }

    function toggleSenha() {
        const input = document.getElementById('senha');
        const icone = document.getElementById('icone-olho');
        if (input.type === 'password') {
            input.type = 'text';
            icone.innerHTML = '🙈';
        } else {
            input.type = 'password';
            icone.innerHTML = '👁';
        }
    }
</script>

</html>