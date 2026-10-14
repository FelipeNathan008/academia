@extends('layouts.dashboard')

@section('title', 'Responsáveis')

@section('content')

@php
$labelClass = 'block text-sm font-semibold text-gray-700';

$inputClass = 'mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition focus:border-[#8E251F] focus:outline-none focus:ring-2 focus:ring-[#8E251F]/15';

$btnPrimary = 'inline-flex items-center justify-center gap-2 rounded-lg bg-[#8E251F] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#732920] hover:shadow focus:outline-none focus:ring-2 focus:ring-[#8E251F]/30 disabled:cursor-not-allowed disabled:opacity-60';

$btnSecondary = 'inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200';

$btnKids = 'inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#174ab9] px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#123a94] focus:outline-none focus:ring-2 focus:ring-[#174ab9]/20';

$btnRowSecondary = 'inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-[#8E251F] hover:bg-[#8E251F]/5 hover:text-[#8E251F] focus:outline-none focus:ring-2 focus:ring-gray-200';

$btnRowDanger = 'inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-200';


@endphp

<style>
    .responsavel-sem-aluno td {
        background-color: #fff7f7;
    }

    .responsavel-sem-aluno:hover td {
        background-color: #fef2f2;
    }

    .table-row-hover:hover td {
        background-color: #fafafa;
    }

    .section-title {
        position: relative;
        padding-left: 12px;
    }

    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 3px;
        bottom: 3px;
        width: 3px;
        border-radius: 999px;
        background: #8E251F;
    }
</style>

<x-alert-error />

<div class="space-y-6">

   
    {{-- CABEÇALHO --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 bg-gradient-to-r from-[#8E251F] to-[#732920] px-6 py-5 text-white">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-center gap-4">

                    <a href="{{ route('admin.principal') }}"
                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-white/20 bg-white/10 text-white transition hover:bg-white/20"
                        title="Voltar">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>

                    </a>

                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-bold tracking-tight">
                                Responsáveis
                            </h2>

                            <span class="rounded-full border border-white/20 bg-white/10 px-2.5 py-1 text-[11px] font-semibold">
                                Gestão
                            </span>
                        </div>

                        <p class="mt-1 text-sm text-white/75">
                            Gerencie responsáveis vinculados aos alunos e matrículas.
                        </p>
                    </div>

                </div>

                <button type="button"
                    onclick="toggleCadastro()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-[#8E251F] shadow-sm transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white/40">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>

                    Novo responsável
                </button>

            </div>
        </div>

        <div class="grid grid-cols-1 divide-y divide-gray-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0">

            <div class="px-6 py-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Total cadastrado
                </p>

                <p class="mt-1 text-2xl font-bold text-gray-900">
                    {{ $totalResponsaveis }}
                </p>
            </div>

            <div class="px-6 py-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Resultado atual
                </p>

                <p class="mt-1 text-2xl font-bold text-[#8E251F]">
                    {{ $responsaveis->total() }}
                </p>
            </div>

        </div>
    </div>

    {{-- ESCOLHA DO TIPO --}}
    <div id="escolhaTipoResponsavel"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/60 p-4 backdrop-blur-sm">

        <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="tituloEscolhaTipo">

            <div class="border-b border-gray-200 bg-gray-50 px-6 py-5">
                <div class="flex items-center justify-between">

                    <div>
                        <h3 id="tituloEscolhaTipo" class="text-lg font-bold text-gray-900">
                            Novo responsável
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Selecione o tipo de cadastro que deseja realizar.
                        </p>
                    </div>

                    <button type="button"
                        onclick="fecharEscolhaTipo()"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-200 hover:text-gray-700">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>

                    </button>

                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2">

                <button type="button"
                    onclick="abrirCadastroKids()"
                    class="group rounded-xl border border-gray-200 bg-white p-5 text-left transition hover:border-[#174ab9] hover:bg-blue-50/50 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#174ab9]/20">

                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-[#174ab9]">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14a4 4 0 100-8 4 4 0 000 8zm-7 7a7 7 0 0114 0" />
                        </svg>

                    </div>

                    <span class="block text-sm font-bold text-gray-900">
                        Kids
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-gray-500">
                        Cadastro completo com CPF, contato e endereço.
                    </span>

                </button>

                <button type="button"
                    onclick="abrirCadastroAdulto()"
                    class="group rounded-xl border border-gray-200 bg-white p-5 text-left transition hover:border-[#8E251F] hover:bg-red-50/50 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#8E251F]/20">

                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-red-100 text-[#8E251F]">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm7-2a4 4 0 110-8 4 4 0 010 8z" />
                        </svg>

                    </div>

                    <span class="block text-sm font-bold text-gray-900">
                        Adulto
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-gray-500">
                        Cadastro simplificado para responsáveis adultos.
                    </span>

                </button>

            </div>

            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                <button type="button"
                    onclick="fecharEscolhaTipo()"
                    class="{{ $btnSecondary }} w-full">
                    Cancelar
                </button>
            </div>

        </div>
    </div>

    {{-- CADASTRO --}}
    <div id="cadastroForm" class="{{ $errors->any() ? '' : 'hidden' }}">

        <form action="{{ route('responsaveis.store') }}"
            method="POST"
            onsubmit="bloquearSubmit(event, this)">

            @csrf

            <input type="hidden" name="tipo_cadastro" id="tipo_cadastro" value="kids">

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                {{-- Cabeçalho do cadastro --}}
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-5">

                    <div class="flex items-start gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#8E251F]/10 text-[#8E251F]">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19a4 4 0 10-8 0m4-8a4 4 0 100-8 4 4 0 000 8zm7 8v-2a4 4 0 00-4-4" />
                            </svg>

                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                Cadastro de responsável
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Informe os dados necessários para cadastrar um novo responsável.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="p-6">

                    {{-- DADOS PESSOAIS --}}
                    <section class="border-b border-gray-200 pb-7">

                        <div class="mb-5 flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-sm font-bold text-gray-600">
                                01
                            </span>

                            <div>
                                <h4 class="section-title text-sm font-bold text-gray-900">
                                    Dados do responsável
                                </h4>

                                <p class="mt-1 text-xs text-gray-500">
                                    Informações principais do responsável.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <div class="md:col-span-2">
                                <label class="{{ $labelClass }}">
                                    Nome do responsável
                                </label>

                                <input type="text"
                                    name="resp_nome"
                                    required
                                    maxlength="120"
                                    value="{{ old('resp_nome') }}"
                                    placeholder="Ex: Maria Lúcia"
                                    oninput="validarNome(this)"
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="campo-kids">
                                <label class="{{ $labelClass }}">
                                    Tipo
                                </label>

                                <select name="resp_parentesco" class="{{ $inputClass }}">
                                    <option value="">Selecione o tipo</option>

                                    <option value="Responsável" {{ old('resp_parentesco') == 'Responsável' ? 'selected' : '' }}>
                                        Responsável
                                    </option>

                                    <option value="Outro" {{ old('resp_parentesco') == 'Outro' ? 'selected' : '' }}>
                                        Outro
                                    </option>
                                </select>
                            </div>

                            <div class="campo-kids">
                                <label class="{{ $labelClass }}">
                                    CPF
                                </label>

                                <input type="text"
                                    name="resp_cpf"
                                    maxlength="14"
                                    value="{{ old('resp_cpf') }}"
                                    placeholder="000.000.000-00"
                                    oninput="mascaraCPF(this)"
                                    class="{{ $inputClass }} @error('resp_cpf') !border-red-500 !ring-red-100 @enderror">

                                @error('resp_cpf')
                                <p class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                        </div>

                    </section>

                    {{-- CONTATO --}}
                    <section class="border-b border-gray-200 py-7">

                        <div class="mb-5 flex items-center gap-3">

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-sm font-bold text-gray-600">
                                02
                            </span>

                            <div>
                                <h4 class="section-title text-sm font-bold text-gray-900">
                                    Informações de contato
                                </h4>

                                <p class="mt-1 text-xs text-gray-500">
                                    E-mail e telefone para comunicação.
                                </p>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <div>
                                <label class="{{ $labelClass }}">
                                    E-mail
                                </label>

                                <input type="email"
                                    name="resp_email"
                                    maxlength="150"
                                    value="{{ old('resp_email') }}"
                                    placeholder="Ex: marialucia@email.com"
                                    class="{{ $inputClass }}">
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">
                                    Telefone
                                </label>

                                <input type="text"
                                    id="resp_telefone"
                                    name="resp_telefone"
                                    value="{{ old('resp_telefone') }}"
                                    placeholder="Ex: (99) 99999-9999"
                                    class="{{ $inputClass }}">
                            </div>

                        </div>

                    </section>

                    {{-- ENDEREÇO --}}
                    <section class="campo-kids pt-7">

                        <div class="mb-5 flex items-center gap-3">

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-sm font-bold text-gray-600">
                                03
                            </span>

                            <div>
                                <h4 class="section-title text-sm font-bold text-gray-900">
                                    Endereço
                                </h4>

                                <p class="mt-1 text-xs text-gray-500">
                                    Endereço residencial do responsável.
                                </p>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-6">

                            <div class="md:col-span-2">
                                <label class="{{ $labelClass }}">CEP</label>

                                <input type="text"
                                    name="resp_cep"
                                    id="resp_cep"
                                    maxlength="9"
                                    value="{{ old('resp_cep') }}"
                                    placeholder="00000-000"
                                    oninput="mascaraCEP(this)"
                                    onblur="buscarCEP()"
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $labelClass }}">Cidade</label>

                                <input type="text"
                                    name="resp_cidade"
                                    value="{{ old('resp_cidade') }}"
                                    placeholder="Ex: São Paulo"
                                    oninput="validarTexto(this)"
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $labelClass }}">Bairro</label>

                                <input type="text"
                                    name="resp_bairro"
                                    value="{{ old('resp_bairro') }}"
                                    placeholder="Ex: Centro"
                                    oninput="validarTexto(this)"
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="md:col-span-4">
                                <label class="{{ $labelClass }}">Logradouro</label>

                                <input type="text"
                                    name="resp_logradouro"
                                    value="{{ old('resp_logradouro') }}"
                                    placeholder="Rua, Avenida, etc."
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $labelClass }}">Número</label>

                                <input type="text"
                                    name="resp_numero"
                                    value="{{ old('resp_numero') }}"
                                    placeholder="Ex: 63"
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="md:col-span-6">
                                <label class="{{ $labelClass }}">
                                    Complemento
                                    <span class="font-normal text-gray-400">(opcional)</span>
                                </label>

                                <input type="text"
                                    name="resp_complemento"
                                    value="{{ old('resp_complemento') }}"
                                    placeholder="Ex: Bloco B, Apto 302"
                                    class="{{ $inputClass }}">
                            </div>

                        </div>

                    </section>

                </div>

                {{-- AÇÕES --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">

                    <button type="button"
                        onclick="fecharCadastro()"
                        class="{{ $btnSecondary }}">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="{{ $btnPrimary }}">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 13l4 4L19 7" />
                        </svg>

                        Salvar responsável
                    </button>

                </div>

            </div>

        </form>

    </div>

    {{-- FILTROS --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-200 text-gray-600">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />

                    </svg>

                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        Filtros
                    </h3>

                    <p class="text-xs text-gray-500">
                        Localize rapidamente um responsável.
                    </p>
                </div>

            </div>

        </div>

        <div class="p-6">

            <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">

                <div class="flex-1">

                    <label for="busca_nome" class="{{ $labelClass }}">
                        Buscar por nome
                    </label>

                    <div class="relative">

                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />

                        </svg>

                        <input type="text"
                            id="busca_nome"
                            name="nome"
                            value="{{ request('nome') }}"
                            placeholder="Digite o nome do responsável..."
                            class="{{ $inputClass }} !pl-10">

                    </div>

                </div>

                <div class="flex gap-2">

                    <button type="submit"
                        class="{{ $btnPrimary }} flex-1 sm:flex-none">
                        Buscar
                    </button>

                    <a href="{{ route('responsaveis') }}"
                        class="{{ $btnSecondary }} flex-1 sm:flex-none">
                        Limpar
                    </a>

                </div>

            </form>

            <div class="mt-6 border-t border-gray-100 pt-5">

                <div class="mb-3 flex items-center justify-between">

                    <span class="text-sm font-semibold text-gray-700">
                        Primeira letra
                    </span>

                    @if(request('letra'))
                    <span class="rounded-full bg-[#8E251F]/10 px-2.5 py-1 text-xs font-semibold text-[#8E251F]">
                        Letra: {{ strtoupper(request('letra')) }}
                    </span>
                    @endif

                </div>

                <div class="flex flex-wrap gap-1.5">

                    @foreach (range('A', 'Z') as $letra)

                    <a href="{{ route('responsaveis', ['letra' => $letra]) }}"
                        class="flex h-8 w-8 items-center justify-center rounded-lg border text-xs font-bold transition
                        {{ strtoupper(request('letra')) === $letra
                            ? 'border-[#8E251F] bg-[#8E251F] text-white shadow-sm'
                            : 'border-gray-200 bg-white text-gray-600 hover:border-[#8E251F]/30 hover:bg-[#8E251F]/5 hover:text-[#8E251F]' }}">

                        {{ $letra }}

                    </a>

                    @endforeach

                </div>

            </div>

            @if (request('nome') || request('letra'))

            <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-4">

                <span class="text-xs font-medium text-gray-500">
                    Filtros ativos:
                </span>

                @if (request('nome'))
                <span class="rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-700">
                    Nome: {{ request('nome') }}
                </span>
                @endif

                @if (request('letra'))
                <span class="rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-700">
                    Letra: {{ strtoupper(request('letra')) }}
                </span>
                @endif

            </div>

            @endif

        </div>

    </div>

    {{-- LISTAGEM --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 bg-white px-6 py-5">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h3 class="text-base font-bold text-gray-900">
                        Responsáveis cadastrados
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Consulte e gerencie os responsáveis da unidade.
                    </p>

                </div>

                <div class="flex flex-wrap gap-2">

                    <span class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-semibold text-gray-600">

                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                        Total: {{ $totalResponsaveis }}

                    </span>

                    <span class="inline-flex items-center gap-1.5 rounded-full border border-[#8E251F]/20 bg-[#8E251F]/5 px-3 py-1.5 text-xs font-semibold text-[#8E251F]">

                        <span class="h-1.5 w-1.5 rounded-full bg-[#8E251F]"></span>

                        Filtrados: {{ $responsaveis->total() }}

                    </span>

                </div>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[760px] text-sm">

                <thead>

                    <tr class="border-b border-[#732920] bg-[#8E251F] text-left text-xs font-bold uppercase tracking-wider text-white">

                        <th class="px-6 py-4">
                            Responsável
                        </th>

                        <th class="px-6 py-4">
                            Telefone
                        </th>

                        <th class="px-6 py-4">
                            E-mail
                        </th>

                        <th class="px-6 py-4 text-right">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($responsaveis as $resp)

                    <tr class="linha-responsavel table-row-hover transition
                        {{ $resp->alunos_count == 0
                            ? 'responsavel-sem-aluno'
                            : '' }}">

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#8E251F]/10 text-xs font-bold text-[#8E251F]">

                                    {{ strtoupper(substr($resp->resp_nome, 0, 1)) }}

                                </div>

                                <div>

                                    <div class="flex flex-wrap items-center gap-2">

                                        <span class="font-semibold text-gray-900">
                                            {{ Str::upper($resp->resp_nome) }}
                                        </span>

                                        @if ($resp->alunos_count == 0)

                                        <span class="rounded-full border border-red-200 bg-red-50 px-2 py-0.5 text-[10px] font-bold text-red-600">
                                            Sem aluno
                                        </span>

                                        @endif

                                    </div>

                                    <p class="mt-0.5 text-xs text-gray-400">
                                        Responsável
                                    </p>

                                </div>

                            </div>

                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">

                            <div class="flex items-center gap-2">

                                <svg class="h-4 w-4 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.502 5.502l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.611 21 3 14.389 3 6V5z" />

                                </svg>

                                {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $resp->resp_telefone ?? "-") }}

                            </div>

                        </td>

                        <td class="px-6 py-4 text-gray-600">

                            <div class="flex items-center gap-2">

                                <svg class="h-4 w-4 shrink-0 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />

                                </svg>

                                <span class="truncate">
                                    {{ $resp->resp_email ?? "-" }}
                                </span>

                            </div>

                        </td>

                        <td class="px-6 py-4">

                            <div class="flex items-center justify-end gap-2">

                                <a href="{{ route('alunos', Crypt::encrypt($resp->id_responsavel)) }}"
                                    class="{{ $btnKids }}">

                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 100-6" />

                                    </svg>

                                    Alunos
                                </a>

                                <a href="{{ route('responsaveis.edit', Crypt::encrypt($resp->id_responsavel)) }}"
                                    class="{{ $btnRowSecondary }}">

                                    Editar
                                </a>

                                <form action="{{ route('responsaveis.destroy', Crypt::encrypt($resp->id_responsavel)) }}"
                                    method="POST"
                                    onsubmit="return confirm('Deseja remover este responsável?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="{{ $btnRowDanger }}">

                                        Excluir

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4" class="px-6 py-16 text-center">

                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">

                                <svg class="h-7 w-7"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6M3 10a3 3 0 106 0" />

                                </svg>

                            </div>

                            <p class="mt-4 text-sm font-bold text-gray-900">
                                Nenhum responsável encontrado
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Ajuste os filtros ou cadastre um novo responsável.
                            </p>

                            <button type="button"
                                onclick="toggleCadastro()"
                                class="mt-5 {{ $btnPrimary }}">

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 5v14M5 12h14" />

                                </svg>

                                Cadastrar responsável

                            </button>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">

            <x-pagination-compact :paginator="$responsaveis" />

        </div>

    </div>
    ```

</div>

<script>
    function toggleCadastro() {

        const form = document.getElementById('cadastroForm');

        if (!form.classList.contains('hidden')) {
            fecharCadastro();
            return;
        }

        document.getElementById('escolhaTipoResponsavel').classList.remove('hidden');
    }

    function fecharEscolhaTipo() {
        document.getElementById('escolhaTipoResponsavel').classList.add('hidden');
    }

    function abrirCadastroKids() {

        fecharEscolhaTipo();

        document.getElementById('cadastroForm').classList.remove('hidden');

        document.getElementById('tipo_cadastro').value = 'kids';

        document.querySelectorAll('.campo-kids').forEach(campo => {
            campo.classList.remove('hidden');
        });

        document.getElementById('cadastroForm').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function abrirCadastroAdulto() {

        fecharEscolhaTipo();

        document.getElementById('cadastroForm').classList.remove('hidden');

        document.getElementById('tipo_cadastro').value = 'adulto';

        document.querySelectorAll('.campo-kids').forEach(campo => {
            campo.classList.add('hidden');
        });

        document.getElementById('cadastroForm').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function bloquearSubmit(event, form) {

        if (!form.checkValidity()) {
            return;
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function validarTexto(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');
    }

    function mascaraCPF(input) {

        let value = input.value.replace(/\D/g, '').slice(0, 11);

        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        input.value = value;
    }

    function mascaraCEP(input) {

        let value = input.value.replace(/\D/g, '').slice(0, 8);

        if (value.length > 5) {
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        }

        input.value = value;
    }

    const tel = document.getElementById('resp_telefone');

    if (tel) {

        tel.addEventListener('input', () => {

            let v = tel.value.replace(/\D/g, '');

            if (v.length > 11) {
                v = v.slice(0, 11);
            }

            let formatado = '';

            if (v.length > 0) {
                formatado = '(' + v.slice(0, 2);
            }

            if (v.length >= 3) {
                formatado += ') ' + v.slice(2, 7);
            }

            if (v.length >= 8) {
                formatado += '-' + v.slice(7, 11);
            }

            tel.value = formatado;
        });
    }

    function buscarCEP() {

        const cepInput = document.getElementById('resp_cep');

        if (!cepInput) {
            return;
        }

        const cep = cepInput.value.replace(/\D/g, '');

        if (cep.length !== 8) {
            return;
        }

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {

                if (data.erro) {
                    alert('CEP não encontrado');
                    return;
                }

                document.querySelector('input[name="resp_logradouro"]').value = data.logradouro || '';
                document.querySelector('input[name="resp_bairro"]').value = data.bairro || '';
                document.querySelector('input[name="resp_cidade"]').value = data.localidade || '';

            })
            .catch(() => {
                alert('Erro ao buscar o CEP');
            });
    }
</script>

@endsection