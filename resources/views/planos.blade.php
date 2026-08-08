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
                Sistema Academia de Artes Marciais
            </h1>

            <p class="text-xl text-gray-300">
                O plano ideal para sua academia.
            </p>
            <div class="text-center mt-12">

                <a href="{{ route('apresentacao') }}"
                    class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-10 py-4 rounded-xl text-xl font-bold mr-4">
                    Voltar
                </a>
            </div>
        </div>
      
        @php
        $check = '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>';
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            @forelse ($planos as $plano)

            <div
                class="plano-card relative flex flex-col bg-white rounded-2xl border border-gray-200 shadow-2xl hover:-translate-y-1 hover:shadow-green-500/20 overflow-hidden transition-all duration-300"
                data-plano="{{ $plano->plano_nome }}"
                data-plano-tipo="{{ $plano->plano_tipo }}">

                <div class="plano-header p-5">
                    <h3 class="plano-titulo text-lg font-bold">
                        {{ $plano->plano_nome }}
                    </h3>
                </div>

                <div class="flex flex-col flex-1 p-6">

                    <div class="mb-5">

                        <p class="plano-preco text-4xl font-extrabold leading-none">

                            @if($plano->plano_tipo == 'financeiro')
                            + R$ {{ number_format($plano->plano_valor, 2, ',', '.') }}
                            @else
                            R$ {{ number_format($plano->plano_valor, 2, ',', '.') }}
                            @endif

                        </p>

                        <p class="text-gray-400 text-sm mt-1">

                            @if($plano->plano_tipo == 'financeiro')
                            adicional por mês
                            @else
                            por mês
                            @endif

                        </p>

                    </div>

                    @if($plano->plano_tipo == 'financeiro')

                    <div class="mb-5 bg-green-50 rounded-lg px-3 py-2 text-sm text-green-700 font-medium">
                        Complemento (Pacote adicional)
                    </div>

                    @else

                    <div class="mb-5 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-medium">
                        Até
                        <strong>{{ $plano->plano_quantidade ?? 'Ilimitadas' }}</strong>
                        matrículas
                    </div>

                    @endif

                    <hr class="mb-5">

                    <ul class="space-y-3 text-sm text-gray-600 flex-1">

                        @foreach ($plano->plano_desc as $recurso)

                        <li class="flex items-start gap-2">

                            <span class="plano-check mt-0.5">
                                {!! $check !!}
                            </span>

                            <span>{{ $recurso }}</span>

                        </li>

                        @endforeach

                    </ul>

                    <a href="{{ route('cadastro_empresa') }}"
                        class="block mt-6 w-full text-center py-2.5 rounded-lg font-semibold text-sm bg-[#8E251F] hover:bg-[#732920] text-white transition">

                        @if($plano->plano_tipo == 'financeiro')
                        Adicionar Pacote
                        @else
                        Começar Agora
                        @endif

                    </a>

                </div>

            </div>

            @empty

            <div class="col-span-full text-center text-white py-12">
                Nenhum plano disponível no momento.
            </div>

            @endforelse

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
    <script src="{{ asset('js/planos-cores.js') }}"></script>
</body>

</html>