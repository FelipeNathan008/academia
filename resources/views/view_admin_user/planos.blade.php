@extends('layouts.dashboard')

@section('title', 'Planos')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Planos Disponíveis
        </h2>

        <p class="text-gray-500 mt-2">
            O plano ideal para a quantidade de matrículas da sua academia.
        </p>
    </div>

    <a href="{{ route('admin.principal') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition self-start md:self-auto">
        ← Voltar
    </a>
</div>

{{-- Componente de check reutilizável --}}
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
    <div class="relative flex flex-col bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl overflow-hidden transition-all duration-300">

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

            <button type="button"
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                Escolher plano
            </button>

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

@endsection