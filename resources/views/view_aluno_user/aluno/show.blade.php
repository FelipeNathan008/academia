@extends('layouts.dashboard')

@section('title', 'Ver Aluno')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
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


<!-- CABEÇALHO -->
<div class="flex items-center justify-between mb-8">

    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            INFORMAÇÕES DO ALUNO
        </h2>
    </div>

    <div class="flex gap-3">

        <a href="{{ route('aluno.index') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <button type="button"
            onclick="toggleEdicao()"
            class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
            Editar
        </button>

    </div>

</div>


<div id="formEdicao" class="hidden mb-8">

    <form action="{{ route('aluno.update', Crypt::encrypt($aluno->id_aluno)) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-8">

            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Editar Aluno
            </h3>


            <!-- NOME | PARENTESCO -->
            <div class="flex gap-4 mb-4">

                <div class="flex-1">

                    <label class="text-sm font-medium text-gray-600">
                        Nome do Aluno
                    </label>

                    <input type="text"
                        name="aluno_nome"
                        required
                        maxlength="120"
                        value="{{ old('aluno_nome', $aluno->aluno_nome) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">

                </div>


                <div class="flex-1">

                    <label class="text-sm font-medium text-gray-600">
                        Parentesco
                    </label>

                    <select name="aluno_parentesco" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                        <option value="Filho" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'filho' ? 'selected' : '' }}>
                            Filho
                        </option>

                        <option value="Sobrinho" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'sobrinho' ? 'selected' : '' }}>
                            Sobrinho
                        </option>

                        <option value="Neto" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'neto' ? 'selected' : '' }}>
                            Neto
                        </option>

                        <option value="Responsável" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'responsavel' ? 'selected' : '' }}>
                            Responsável
                        </option>

                        <option value="Outro" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'outro' ? 'selected' : '' }}>
                            Outro
                        </option>

                    </select>
                </div>

            </div>


            <!-- NASCIMENTO | BOLSISTA -->
            <div class="flex gap-4 mb-4">

                <div class="flex-1">

                    <label class="text-sm font-medium text-gray-600">
                        Data de Nascimento
                    </label>

                    <input type="date"
                        name="aluno_nascimento"
                        required
                        value="{{ old('aluno_nascimento', \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">

                </div>


                <div class="flex-1">

                    <label class="text-sm font-medium text-gray-600">
                        Bolsista
                    </label>

                    <select name="aluno_bolsista"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">

                        <option value="sim"
                            {{ old('aluno_bolsista', $aluno->aluno_bolsista) == 'sim' ? 'selected' : '' }}>
                            Sim
                        </option>

                        <option value="nao"
                            {{ old('aluno_bolsista', $aluno->aluno_bolsista) == 'nao' ? 'selected' : '' }}>
                            Não
                        </option>

                    </select>

                </div>

            </div>


            <!-- FOTO -->
            <div class="mb-4">

                <label class="text-sm font-medium text-gray-600">
                    Foto do Aluno
                </label>

                <div class="mt-2 flex items-center gap-5">

                    @if($aluno->aluno_foto)

                    <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                        class="w-24 h-24 object-cover rounded-xl shadow">

                    @else

                    <div class="w-24 h-24 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                        Sem foto
                    </div>

                    @endif


                    <div>

                        <input type="file"
                            name="aluno_foto"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full text-sm text-gray-600">

                        <p class="text-xs text-gray-400 mt-1">
                            JPG, JPEG, PNG ou WEBP. Máximo de 5 MB.
                        </p>

                    </div>

                </div>

            </div>


            <!-- OBSERVAÇÕES -->
            <div class="mb-6">

                <label class="text-sm font-medium text-gray-600">
                    Observações
                </label>

                <textarea name="aluno_desc"
                    required
                    rows="5"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]"
                    placeholder="Digite observações sobre o aluno...">{{ old('aluno_desc', $aluno->aluno_desc) }}</textarea>

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

<div id="informacoesAluno"
    class="bg-white rounded-2xl shadow-md p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">


        <!-- COLUNA 1 -->
        <div class="space-y-6">

            <div>

                <p class="text-xs uppercase text-gray-400">
                    Nome
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->aluno_nome }}
                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-400">
                    Responsável
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->responsavel->resp_nome }}
                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-400">
                    Parentesco
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->aluno_parentesco ?? '-' }}
                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-400">
                    Nascimento
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
                </p>

            </div>

        </div>


        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>

                <p class="text-xs uppercase text-gray-400">
                    Idade
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->age }} anos
                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-400">
                    Bolsista
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    {{ ucfirst($aluno->aluno_bolsista) }}
                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-400">
                    Status
                </p>

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


        <!-- FOTO -->
        <div>

            <p class="text-xs uppercase text-gray-400 mb-2">
                Foto
            </p>

            @if($aluno->aluno_foto)

            <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                class="w-36 h-36 object-cover rounded-xl shadow">

            @else

            <p class="text-gray-500">
                Sem foto
            </p>

            @endif

        </div>

    </div>


    <!-- OBSERVAÇÕES -->
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


<script>
    function toggleEdicao() {

        const form = document.getElementById('formEdicao');
        const informacoes = document.getElementById('informacoesAluno');

        form.classList.remove('hidden');
        informacoes.classList.add('hidden');

        window.scrollTo({
            top: form.offsetTop - 30,
            behavior: 'smooth'
        });

    }


    function fecharEdicao() {

        const form = document.getElementById('formEdicao');
        const informacoes = document.getElementById('informacoesAluno');

        form.classList.add('hidden');
        informacoes.classList.remove('hidden');

    }
</script>

@endsection