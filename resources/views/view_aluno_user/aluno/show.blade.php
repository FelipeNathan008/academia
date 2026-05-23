@extends('layouts.dashboard')

@section('title', 'Ver Aluno')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">

        <li>
            <a href="{{ route('aluno.index') }}"
                class="hover:text-[#8E251F] transition">
                Meus Alunos
            </a>
        </li>

        <li>/</li>

        <li class="font-semibold text-gray-700">
            Ver Aluno
        </li>

    </ol>
</nav>

<div class="flex justify-between items-center mb-8">

    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            INFORMAÇÕES DO ALUNO
        </h2>
    </div>

    <a href="{{ route('aluno.index') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

</div>

<div class="bg-white rounded-2xl shadow-md p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Nome</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->aluno_nome }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Responsável</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->responsavel->resp_nome }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Nascimento</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
                </p>
            </div>

        </div>

        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Idade</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->age }} anos
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Bolsista</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ ucfirst($aluno->aluno_bolsista) }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Status</p>

                @if($aluno->matriculas->where('matri_status', 'Matriculado')->count() > 0)

                <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                    Matriculado
                </span>

                @else

                <span class="px-4 py-2 text-sm rounded-full bg-red-100 text-red-700 font-medium">
                    Não Matriculado
                </span>

                @endif

            </div>

        </div>

        <div>

            <p class="text-xs uppercase text-gray-400 mb-2">Foto</p>

            @if($aluno->aluno_foto)

            <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                class="w-36 h-36 object-cover rounded-xl shadow">

            @else

            <p class="text-gray-500">Sem foto</p>

            @endif

        </div>

    </div>

    @if($aluno->aluno_desc)

    <div class="mt-10 pt-6 border-t">

        <p class="text-xs uppercase text-gray-400 mb-2">
            Observações
        </p>

        <p class="text-gray-700 leading-relaxed">
            {{ $aluno->aluno_desc }}
        </p>

    </div>

    @endif

</div>

@endsection