@extends('layouts.dashboard')

@section('title', 'Administração')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li class="font-semibold text-gray-700">
            Administração
        </li>
    </ol>
</nav>

<div class="bg-white shadow-lg rounded-2xl p-6">

    <h3 class="text-2xl font-extrabold text-gray-800 mb-6">
        Administração do Sistema (Preparação do ambiente)
        <br>
        <span class="text-sm text-gray-500">Siga a ordem abaixo</span>
    </h3>


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <a href="{{ route('turmas') }}"
            style="background-color: #0f766e; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            1 - Turmas
        </a>
        <a href="{{ route('modalidades') }}"
            style="background-color: #7c3aed; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            2 - Modalidades
        </a>
        <a href="{{ route('preco-aula') }}"
            style="background-color: #dc2626; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            3 - Preço das Aulas
        </a>

        <a href="{{ route('graduacoes') }}"
            style="background-color: #15803d; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            4 - Graduações
        </a>

        <a href="{{ route('professores.alunos') }}"
            style="background-color: #ca8a04; color: white;"
            class="px-4 py-3 rounded-xl shadow hover:opacity-90 transition text-center font-semibold">
            5 - Professores / Alunos
        </a>

        <a href="{{ route('horario_treino') }}"
            style="background-color: #5004ca; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            6 - Horários de Treino
        </a>

        <a href="{{ route('grade_horarios') }}"
            style="background-color: #1d4ed8; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            7 - Grade de Horários
        </a>





    </div>

</div>

@endsection