@extends('layouts.dashboard')

@section('title', 'Principal')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li class="font-semibold text-gray-700">
            Principal
        </li>
    </ol>
</nav>

<div class="bg-white shadow-lg rounded-2xl p-6">

    <h3 class="text-2xl font-extrabold text-gray-800 mb-6">
        Navegação Principal
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <a href="{{ route('dashboard') }}"
            style="background-color: #0F4C81; color: white;"
            class="px-4 py-3 rounded-xl shadow hover:opacity-90 transition text-center font-semibold">
            Dashboard
        </a>

        <a href="{{ route('responsaveis') }}"
            style="background-color: #15803d; color: white;"
            class="px-4 py-3 rounded-xl shadow hover:opacity-90 transition text-center font-semibold">
            Matrícula
        </a>

        <a href="{{ route('matricula.index') }}"
            style="background-color: #7c3aed; color: white;"
            class="px-4 py-3 rounded-xl shadow hover:opacity-90 transition text-center font-semibold">
            Alunos
        </a>

        <a href="{{ route('professores.alunos') }}"
            style="background-color: #ca8a04; color: white;"
            class="px-4 py-3 rounded-xl shadow hover:opacity-90 transition text-center font-semibold">
            Professores
        </a>

        <a href="{{ route('frequencia.listagem') }}"
            style="background-color: #dc2626; color: white;"
            class="px-4 py-3 rounded-xl shadow hover:opacity-90 transition text-center font-semibold">
            Frequência dos Alunos
        </a>

        <a href="{{ route('grades.aulas') }}"
            style="background-color: #0d4270; color: white;"
            class="px-4 py-3 rounded-xl shadow hover:opacity-90 transition text-center font-semibold">
            Aula dos Alunos
        </a>
    </div>

</div>

@endsection