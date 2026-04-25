@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

<h2 class="text-2xl font-bold mb-6">Visão Geral</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <x-card label="Total de Alunos" value="250" footer="Ativos e inativos" />
    <x-card label="Matrículas Ativas" value="180" footer="Atualmente inscritos" />
    <x-card label="Receita Mensal" value="R$ 15.200" footer="Mês atual" />
</div>

<h2 class="text-xl font-bold mb-4">Ações Rápidas</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-semibold mb-2">Cadastrar Novo Aluno</h3>
        <p class="text-sm text-gray-500 mb-4">Adicione um novo membro</p>
        <button class="px-4 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
            Ir para Cadastro
        </button>
    </div>
</div>

@endsection
