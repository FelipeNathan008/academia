@extends('layouts.dashboard')

@section('title', 'Detalhes do Responsável')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('professor-aluno.index') }}" class="hover:text-[#8E251F]">
                Meus Alunos
            </a>
        </li>

        <li>/</li>

        <li class="text-gray-400">
            {{$aluno->aluno_nome }}
        </li>
        <li>/</li>

        <li class="font-semibold text-gray-700">
            Responsável
        </li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

    <h2 class="text-3xl font-extrabold text-gray-800">
        INFORMAÇÕES DO RESPONSÁVEL
    </h2>

    <div class="flex gap-3">

        <button onclick="toggleEdicao()"
            class="px-5 py-2 bg-[#8E251F] text-white rounded-lg shadow hover:bg-[#732920] transition">
            Editar
        </button>

        <a href="{{ route('professor-aluno.hub', Crypt::encrypt($aluno->id_aluno)) }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
            ← Voltar
        </a>

    </div>
</div>

{{-- CARD DO ALUNO --}}
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Aluno relacionado
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $aluno->aluno_nome ?? 'Aluno não encontrado' }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">

            Data de nascimento:
            <strong class="text-gray-800">
                @if(isset($aluno->aluno_nascimento))
                {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
                @else
                -
                @endif
            </strong>

            <br>

            Idade:
            <strong class="text-gray-800">
                @if(isset($aluno->aluno_nascimento))
                {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->age }}
                @else
                -
                @endif
            </strong>

        </p>

    </div>
</div>

<!-- FORM EDIT -->
<div id="editForm" class="hidden mb-10">

    <form action="{{ route('professor-responsavel.update', Crypt::encrypt($responsavel->id_responsavel)) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-8">

            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Editar Responsável
            </h3>

            <!-- Nome e Tipo -->
            <div class="flex gap-4 mb-4">

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Nome do Responsável
                    </label>

                    <input type="text"
                        name="resp_nome"
                        required
                        maxlength="120"
                        placeholder="Ex: Maria Lúcia"
                        value="{{ old('resp_nome', $responsavel->resp_nome) }}"
                        oninput="validarNome(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Tipo
                    </label>

                    <select name="resp_parentesco"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">

                        <option value="">Selecione</option>

                        @foreach (['Responsável','Outro'] as $p)
                        <option value="{{ $p }}"
                            {{ $responsavel->resp_parentesco == $p ? 'selected' : '' }}>
                            {{ $p }}
                        </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <!-- Email | Telefone -->
            <div class="flex gap-4 mb-4">

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Email
                    </label>

                    <input type="email"
                        name="resp_email"
                        required
                        maxlength="150"
                        placeholder="Ex: email@email.com"
                        value="{{ old('resp_email', $responsavel->resp_email) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Telefone
                    </label>

                    <input type="text"
                        name="resp_telefone"
                        id="resp_telefone"
                        required
                        placeholder="Ex: (99) 99999-9999"
                        value="{{ old('resp_telefone', $responsavel->resp_telefone) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

            </div>

            <!-- CPF | CEP -->
            <div class="flex gap-4 mb-4">

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        CPF
                    </label>

                    <input type="text"
                        name="resp_cpf"
                        maxlength="14"
                        required
                        placeholder="000.000.000-00"
                        value="{{ old('resp_cpf', $responsavel->resp_cpf) }}"
                        oninput="mascaraCPF(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        CEP
                    </label>

                    <input type="text"
                        name="resp_cep"
                        id="resp_cep"
                        maxlength="9"
                        required
                        placeholder="00000-000"
                        value="{{ old('resp_cep', $responsavel->resp_cep) }}"
                        oninput="mascaraCEP(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

            </div>

            <!-- Cidade | Bairro -->
            <div class="flex gap-4 mb-4">

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Cidade
                    </label>

                    <input type="text"
                        name="resp_cidade"
                        required
                        placeholder="Ex: São Paulo"
                        value="{{ old('resp_cidade', $responsavel->resp_cidade) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Bairro
                    </label>

                    <input type="text"
                        name="resp_bairro"
                        required
                        placeholder="Ex: Centro"
                        value="{{ old('resp_bairro', $responsavel->resp_bairro) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

            </div>

            <!-- Logradouro | Número | Complemento -->
            <div class="flex gap-4 mb-6">

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Logradouro
                    </label>

                    <input type="text"
                        name="resp_logradouro"
                        required
                        placeholder="Rua, Avenida, etc."
                        value="{{ old('resp_logradouro', $responsavel->resp_logradouro) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div class="w-32">
                    <label class="text-sm font-medium text-gray-600">
                        Número
                    </label>

                    <input type="text"
                        name="resp_numero"
                        placeholder="Ex: 63"
                        value="{{ old('resp_numero', $responsavel->resp_numero) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">
                        Complemento
                    </label>

                    <input type="text"
                        name="resp_complemento"
                        placeholder="Ex: Bloco B"
                        value="{{ old('resp_complemento', $responsavel->resp_complemento) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

            </div>

            <!-- AÇÕES -->
            <div class="flex justify-end gap-4 border-t pt-6">

                <button type="button"
                    onclick="fecharEdicao()"
                    class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                    Salvar Alterações
                </button>

            </div>

        </div>
    </form>
</div>

<!-- CARD -->
<div class="bg-white rounded-2xl shadow-md p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- COLUNA 1 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Nome</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_nome }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Telefone</p>

                <p class="text-lg font-semibold text-gray-800">
                    @if($responsavel->resp_telefone)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $responsavel->resp_telefone)) }}
                    @else
                    -
                    @endif
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Email</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_email ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">CPF</p>

                <p class="text-lg font-semibold text-gray-800">
                    @if($responsavel->resp_cpf)
                    {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', preg_replace('/\D/', '', $responsavel->resp_cpf)) }}
                    @else
                    -
                    @endif
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">CEP</p>

                <p class="text-lg font-semibold text-gray-800">
                    @if($responsavel->resp_cep)
                    {{ preg_replace('/(\d{5})(\d{3})/', '$1-$2', preg_replace('/\D/', '', $responsavel->resp_cep)) }}
                    @else
                    -
                    @endif
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Cidade</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_cidade ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Bairro</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_bairro ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Logradouro</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_logradouro ?? '-' }},
                    {{ $responsavel->resp_numero ?? '' }}
                </p>
            </div>

            @if($responsavel->resp_complemento)
            <div>
                <p class="text-xs uppercase text-gray-400">Complemento</p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $responsavel->resp_complemento }}
                </p>
            </div>
            @endif

        </div>

    </div>

    <!-- ALUNO -->
    <div class="mt-10 pt-6 border-t">

        <p class="text-xs uppercase text-gray-400 mb-2">
            Aluno Relacionado
        </p>

        <p class="text-gray-700 font-semibold">
            {{ $aluno->aluno_nome }}
        </p>

    </div>

</div>

<!-- JS -->
<script>
    function toggleEdicao() {
        const form = document.getElementById('editForm');

        form.classList.toggle('hidden');

        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharEdicao() {
        document.getElementById('editForm').classList.add('hidden');
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function mascaraCPF(input) {

        let v = input.value.replace(/\D/g, '').slice(0, 11);

        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        input.value = v;
    }

    function mascaraCEP(input) {

        let v = input.value.replace(/\D/g, '').slice(0, 8);

        if (v.length > 5) {
            v = v.replace(/^(\d{5})(\d)/, '$1-$2');
        }

        input.value = v;
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
</script>

@endsection