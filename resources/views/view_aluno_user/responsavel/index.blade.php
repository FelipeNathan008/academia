@extends('layouts.dashboard')

@section('title', 'Responsável')

@section('content')


<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-800">
        INFORMAÇÕES DO RESPONSÁVEL
    </h2>
</div>

<!-- CARD -->
<div class="bg-white rounded-2xl shadow-md p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- COLUNA 1 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Nome
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_nome }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Parentesco
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_parentesco ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    CPF
                </p>

                <p class="text-lg font-semibold text-gray-800">

                    @if($responsavel->resp_cpf_mascarado)
                    {{ $responsavel->resp_cpf_mascarado }}
                    @else
                    -
                    @endif

                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Telefone
                </p>

                <p class="text-lg font-semibold text-gray-800">

                    @if($responsavel->resp_telefone)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $responsavel->resp_telefone)) }}
                    @else
                    -
                    @endif

                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    E-mail
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_email ?? '-' }}
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    CEP
                </p>

                <p class="text-lg font-semibold text-gray-800">

                    @if($responsavel->resp_cep)
                    {{ preg_replace('/(\d{5})(\d{3})/', '$1-$2', preg_replace('/\D/', '', $responsavel->resp_cep)) }}
                    @else
                    -
                    @endif

                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Cidade
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_cidade ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Bairro
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_bairro ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Endereço
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_logradouro ?? '-' }},
                    {{ $responsavel->resp_numero ?? 'S/N' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Complemento
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_complemento ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Quantidade de Alunos
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->qtd_alunos }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection