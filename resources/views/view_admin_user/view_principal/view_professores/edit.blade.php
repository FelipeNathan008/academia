@extends('layouts.dashboard')

@section('title', 'Editar Professor')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Editar Professor"
        :breadcrumbs="[
            ['label' => 'Administração', 'url' => route('admin.administracao')],
            ['label' => 'Professores', 'url' => route('professores')],
            ['label' => 'Editar Professor']
        ]" />

    <form action="{{ route('professores.update', Crypt::encrypt($professor->id_professor)) }}" method="POST" enctype="multipart/form-data" onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <x-form-card title="Dados do professor" description="Atualize as informações de {{ $professor->prof_nome }} nos campos abaixo.">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="edit_nome" class="mb-2 block text-sm font-semibold text-gray-700">Nome completo</label>
                    <input type="text" id="edit_nome" name="prof_nome" maxlength="120" required value="{{ old('prof_nome', $professor->prof_nome) }}" oninput="validarNome(this)" placeholder="Nome completo do professor" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>

                <div>
                    <label for="prof_nascimento" class="mb-2 block text-sm font-semibold text-gray-700">Data de nascimento</label>
                    <input
                        type="date"
                        id="prof_nascimento"
                        name="prof_nascimento"
                        required
                        min="{{ now()->subYears(100)->format('Y-m-d') }}"
                        max="{{ now()->subYears(18)->format('Y-m-d') }}"
                        value="{{ old('prof_nascimento', $professor->prof_nascimento) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>

                <div>
                    <label for="prof_telefone" class="mb-2 block text-sm font-semibold text-gray-700">Telefone</label>
                    <input type="text" id="prof_telefone" name="prof_telefone" maxlength="15" required placeholder="(99) 99999-9999" value="{{ old('prof_telefone', $professor->prof_telefone) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>

                <div class="md:col-span-2">
                    <label for="edit_desc" class="mb-2 block text-sm font-semibold text-gray-700">Observações</label>
                    <textarea id="edit_desc" name="prof_desc" rows="4" required placeholder="Adicione observações sobre o professor..." class="w-full resize-y rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">{{ old('prof_desc', $professor->prof_desc) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Foto atual</label>
                    <div class="mb-4 flex items-center gap-4">
                        @if ($professor->prof_foto)
                        <img src="{{ asset('images/professores/' . $professor->prof_foto) }}" alt="Foto do professor" class="h-20 w-20 rounded-xl border border-gray-200 object-cover">
                        @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-400">Sem foto</div>
                        @endif
                        <span class="text-sm text-gray-500">{{ $professor->prof_foto ? 'Foto cadastrada' : 'Nenhuma foto cadastrada' }}</span>
                    </div>

                    <label for="prof_foto" class="mb-2 block text-sm font-semibold text-gray-700">Alterar foto</label>
                    <input type="file" id="prof_foto" name="prof_foto" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200 focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    <p class="mt-1 text-xs text-gray-500">Selecione uma nova imagem somente se desejar alterar a foto atual.</p>
                </div>
            </div>

            <x-slot:actions>
                <x-back-button :url="route('professores')" />
                <x-primary-button type="submit" icon="check">Salvar alterações</x-primary-button>
            </x-slot:actions>
        </x-form-card>
    </form>
</div>

<script>
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function aplicarMascaraTelefone(input) {
        let v = input.value.replace(/\D/g, '').slice(0, 11);
        let f = '';

        if (v.length > 0) f = '(' + v.slice(0, 2);
        if (v.length >= 3) f += ') ' + v.slice(2, 7);
        if (v.length >= 8) f += '-' + v.slice(7, 11);

        input.value = f;
    }

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;

        const botao = form.querySelector('button[type="submit"]');

        if (botao) {
            botao.disabled = true;
            botao.innerText = 'Salvando...';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const telefone = document.getElementById('prof_telefone');

        if (telefone) {
            aplicarMascaraTelefone(telefone);
            telefone.addEventListener('input', () => aplicarMascaraTelefone(telefone));
        }
    });
</script>
@endsection