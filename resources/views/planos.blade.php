<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Planos | Sistema Academia</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {

            background: linear-gradient(rgba(0, 0, 0, .82), rgba(0, 0, 0, .82)),
            url("{{ asset('images/tela_inicial.jpg') }}");

            background-size: cover;
            background-position: center;
            background-attachment: fixed;

            font-family: Arial, Helvetica, sans-serif;

        }
    </style>

</head>

<body>

    <div class="max-w-7xl mx-auto px-6 py-14">

        <div class="text-center text-white mb-12">

            <h1 class="text-5xl font-bold mb-4">
                Sistema Academia de Jiu-Jitsu
            </h1>

            <p class="text-xl text-gray-300">
                O plano ideal para sua academia.
            </p>

        </div>

        {{-- Componente de check reutilizável (mesmo do admin) --}}
        @php
        $check = '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>';

        $planos = [
        [
        'nome' => 'Faixa Branca',
        'matriculas' => '50',
        'preco' => '59',
        'gradiente' => 'from-slate-100 to-slate-200',
        'texto_header' => 'text-gray-800',
        'cor_preco' => 'text-[#8E251F]',
        'cor_check' => 'text-[#8E251F]',
        'recursos' => ['Cadastro de alunos', 'Matrículas', 'Graduações', 'Frequência'],
        ],
        [
        'nome' => 'Faixa Azul',
        'matriculas' => '70',
        'preco' => '79',
        'gradiente' => 'from-blue-100 to-blue-200',
        'texto_header' => 'text-blue-900',
        'cor_preco' => 'text-blue-700',
        'cor_check' => 'text-blue-600',
        'recursos' => ['Cadastro de alunos', 'Matrículas', 'Graduações', 'Frequência'],
        ],
        [
        'nome' => 'Faixa Roxa',
        'matriculas' => '90',
        'preco' => '99',
        'gradiente' => 'from-purple-100 to-purple-200',
        'texto_header' => 'text-purple-900',
        'cor_preco' => 'text-purple-700',
        'cor_check' => 'text-purple-600',
        'recursos' => ['Cadastro de alunos', 'Matrículas', 'Graduações', 'Frequência'],
        ],
        [
        'nome' => 'Faixa Marrom',
        'matriculas' => '110',
        'preco' => '129',
        'gradiente' => 'from-amber-100 to-amber-200',
        'texto_header' => 'text-amber-900',
        'cor_preco' => 'text-amber-700',
        'cor_check' => 'text-amber-600',
        'recursos' => ['Cadastro de alunos', 'Matrículas', 'Graduações', 'Frequência'],
        ],
        [
        'nome' => 'Faixa Preta',
        'matriculas' => '150',
        'preco' => '169',
        'gradiente' => 'from-gray-800 to-black',
        'texto_header' => 'text-white',
        'cor_preco' => 'text-gray-900',
        'cor_check' => 'text-gray-900',
        'recursos' => ['Cadastro de alunos', 'Matrículas', 'Graduações', 'Frequência'],
        ],
        [
        'nome' => 'Faixa Coral',
        'matriculas' => '500',
        'preco' => '299',
        'gradiente' => 'from-orange-100 to-orange-200',
        'texto_header' => 'text-orange-900',
        'cor_preco' => 'text-orange-600',
        'cor_check' => 'text-orange-600',
        'recursos' => ['Cadastro de alunos', 'Matrículas', 'Graduações', 'Frequência', 'Fluxo de Caixa'],
        ],
        [
        'nome' => 'Faixa Vermelha',
        'matriculas' => 'Ilimitadas',
        'preco' => '499',
        'gradiente' => 'from-red-500 to-red-700',
        'texto_header' => 'text-white',
        'cor_preco' => 'text-red-600',
        'cor_check' => 'text-red-600',
        'recursos' => ['Cadastro de alunos', 'Matrículas', 'Graduações', 'Frequência', 'Fluxo de Caixa'],
        ],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach ($planos as $plano)
            <div class="relative flex flex-col bg-white rounded-2xl border border-gray-200 shadow-2xl hover:-translate-y-1 hover:shadow-green-500/20 overflow-hidden transition-all duration-300">

                {{-- Cabeçalho --}}
                <div class="bg-gradient-to-br {{ $plano['gradiente'] }} p-5">
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold {{ $plano['texto_header'] }}">
                            {{ $plano['nome'] }}
                        </h3>
                    </div>
                </div>

                {{-- Corpo --}}
                <div class="flex flex-col flex-1 p-6">

                    <div class="mb-5">
                        <p class="text-4xl font-extrabold {{ $plano['cor_preco'] }} leading-none">
                            R$ {{ $plano['preco'] }}
                        </p>
                        <p class="text-gray-400 text-sm mt-1">por mês</p>
                    </div>

                    <div class="mb-5 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-medium">
                        Até <strong>{{ $plano['matriculas'] }}</strong> matrículas
                    </div>

                    <hr class="mb-5">

                    <ul class="space-y-3 text-sm text-gray-600 flex-1">
                        @foreach ($plano['recursos'] as $recurso)
                        <li class="flex items-start gap-2">
                            <span class="{{ $plano['cor_check'] }} mt-0.5">{!! $check !!}</span>
                            <span>{{ $recurso }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('cadastro_empresa') }}"
                        class="block mt-6 w-full text-center py-2.5 rounded-lg font-semibold text-sm bg-[#8E251F] hover:bg-[#732920] text-white transition">
                        Começar Agora
                    </a>

                </div>
            </div>
            @endforeach
        </div>

        {{-- PACOTE FINANCEIRO --}}
        <div class="mt-12">
            <div class="bg-gradient-to-r from-green-50 to-white rounded-2xl shadow-lg border-l-8 border-green-600 p-8">

                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-2xl font-bold text-gray-800">
                        Pacote Financeiro
                    </h3>
                </div>

                <p class="text-gray-600 mb-6 ml-[52px]">
                    Pode ser adicionado a qualquer plano para liberar os recursos financeiros completos.
                </p>

                <div class="grid md:grid-cols-2 gap-8 items-center">

                    <div>
                        <p class="text-5xl font-extrabold text-green-600 leading-none">
                            + R$ 49
                        </p>
                        <p class="text-gray-500 mt-1">adicional por mês</p>
                    </div>

                    <div>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-2 text-gray-700">
                                <span class="text-green-600">{!! $check !!}</span>
                                Fluxo de Caixa
                            </li>
                            <li class="flex items-center gap-2 text-gray-700">
                                <span class="text-green-600">{!! $check !!}</span>
                                Contas a Receber
                            </li>
                            <li class="flex items-center gap-2 text-gray-700">
                                <span class="text-green-600">{!! $check !!}</span>
                                Contas a Pagar
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>

        <div class="text-center mt-12">

            <a href="{{ route('cadastro_empresa') }}"
                class="inline-block bg-green-600 hover:bg-green-700 text-white px-10 py-4 rounded-xl text-xl font-bold mr-4">
                Criar Conta
            </a>

            <a href="{{ route('login') }}"
                class="inline-block border border-white text-white px-10 py-4 rounded-xl hover:bg-white hover:text-black transition">
                Já tenho uma conta
            </a>

        </div>

    </div>

</body>

</html>