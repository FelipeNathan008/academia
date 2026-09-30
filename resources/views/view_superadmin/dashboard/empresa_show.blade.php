@extends('layouts.dashboard')

@section('title', 'Detalhes da Empresa')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">

    <ol class="flex items-center gap-2">

        <li>
            <a href="{{ route('dashboard-superadmin') }}"
                class="hover:text-[#8E251F] transition">
                Dashboard
            </a>
        </li>

        <li>/</li>

        <li>
            <a href="{{ route('superadmin.empresas') }}"
                class="hover:text-[#8E251F] transition">
                Empresas Clientes
            </a>
        </li>

        <li>/</li>

        <li class="font-semibold text-gray-700">
            Ver Empresa
        </li>

    </ol>

</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">

    <div>

        <h2 class="text-3xl font-extrabold text-gray-800">
            INFORMAÇÕES COMPLETAS DA EMPRESA
        </h2>

        <p class="text-gray-500 mt-1">
            {{ $empresa->emp_nome ?? '-' }}
        </p>

    </div>

    <a href="{{ route('superadmin.empresas') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

</div>

<!-- CARD -->
<div class="bg-white rounded-2xl shadow-md p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- COLUNA 1 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Nome da Empresa
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->emp_nome ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Apelido
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->emp_apelido ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Tipo
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->emp_tipo ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Responsável
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->emp_nome_responsavel ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    CPF do Responsável
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->emp_cpf ?? '-' }}
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    E-mail
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->emp_email_responsavel ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Telefone
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->emp_telefone_responsavel ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Status
                </p>

                @if($empresa->emp_status === 'Cliente')

                <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                    Cliente
                </span>

                @elseif($empresa->emp_status === 'Degustação')

                <span class="px-4 py-2 text-sm rounded-full bg-yellow-100 text-yellow-700 font-medium">
                    Degustação
                </span>

                @else

                <span class="px-4 py-2 text-sm rounded-full bg-gray-100 text-gray-700 font-medium">
                    {{ $empresa->emp_status ?? '-' }}
                </span>

                @endif

            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    ID da Empresa
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->id_empresa }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Filiais
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $empresa->filiais->count() }}
                </p>
            </div>

        </div>

    </div>

    <!-- ENDEREÇO -->
    <div class="mt-10 pt-6 border-t">

        <h3 class="text-xl font-bold text-gray-700 mb-6">
            Endereço
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    CEP
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_end_cep ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Logradouro
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_end_logradouro ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Número
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_end_numero ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Complemento
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_end_complemento ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Bairro
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_end_bairro ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Cidade
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_end_cidade ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Estado
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_end_estado ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    <!-- PIX -->
    <div class="mt-10 pt-6 border-t">

        <h3 class="text-xl font-bold text-gray-700 mb-6">
            Dados PIX
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Chave PIX
                </p>

                <p class="font-semibold text-gray-800 break-all">
                    {{ $empresa->emp_chave_pix ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Nome do Recebedor
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_nome_recebedor ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Cidade PIX
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $empresa->emp_cidade ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    <!-- DEGUSTAÇÃO -->
    <div class="mt-10 pt-6 border-t">

        <h3 class="text-xl font-bold text-gray-700 mb-6">
            Período de Degustação
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Início
                </p>

                <p class="font-semibold text-gray-800">
                    {{ optional($empresa->emp_degustacao_inicio)->format('d/m/Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Fim
                </p>

                <p class="font-semibold text-gray-800">
                    {{ optional($empresa->emp_degustacao_fim)->format('d/m/Y') ?? '-' }}
                </p>
            </div>

        </div>

    </div>


    <!-- FILIAIS -->
    <div class="mt-10 pt-6 border-t">

        <h3 class="text-xl font-bold text-gray-700 mb-6">
            Filiais
        </h3>

        @if($empresa->filiais->count())

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead>

                    <tr class="border-b text-gray-600 text-sm">

                        <th class="py-3 px-4">
                            Filial
                        </th>

                        <th class="py-3 px-4">
                            Cidade
                        </th>

                        <th class="py-3 px-4">
                            Estado
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($empresa->filiais as $filial)

                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="py-4 px-4 font-semibold text-gray-800">
                            {{ $filial->fil_nome ?? '-' }}
                        </td>

                        <td class="py-4 px-4">
                            {{ $filial->fil_cidade ?? '-' }}
                        </td>

                        <td class="py-4 px-4">
                            {{ $filial->fil_estado ?? '-' }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        @else

        <p class="text-gray-500">
            Nenhuma filial cadastrada.
        </p>

        @endif

    </div>

</div>

@endsection