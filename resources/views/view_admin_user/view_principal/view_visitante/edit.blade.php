@extends('layouts.dashboard')

@section('title', 'Editar Visitante')

@section('content')

<x-alert-error />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('visitantes') }}" class="hover:text-[#8E251F] transition">
                Visitantes
            </a>
        </li>
        <li>/</li>
        <li>
            <span class="text-gray-400">{{ $visitante->vis_nome }}</span>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Visitante</li>
    </ol>
</nav>

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Visitante – {{ $visitante->vis_nome }}
    </h2>

    <form action="{{ route('visitantes.update', Crypt::encrypt($visitante->id_visitante)) }}"
        method="POST"
        onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">
                    Nome do Visitante
                </label>
                <input type="text"
                    name="vis_nome"
                    required
                    maxlength="120"
                    placeholder="Ex: João da Silva"
                    value="{{ old('vis_nome', $visitante->vis_nome) }}"
                    oninput="validarNome(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">
                    E-mail
                </label>
                <input type="email"
                    name="vis_email"
                    maxlength="150"
                    placeholder="Ex: joao@email.com"
                    value="{{ old('vis_email', $visitante->vis_email) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">
                    Telefone
                </label>
                <input type="text"
                    name="vis_telefone"
                    id="vis_telefone"
                    maxlength="15"
                    placeholder="Ex: (99) 99999-9999"
                    value="{{ old('vis_telefone', $visitante->vis_telefone) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">
                    Data de Nascimento
                </label>
                <input type="date"
                    name="vis_data_nasc"
                    id="vis_data_nasc"
                    max="{{ now()->format('Y-m-d') }}"
                    value="{{ old('vis_data_nasc', $visitante->vis_data_nasc?->format('Y-m-d')) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <div class="flex gap-4 mb-6">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">
                    Status
                </label>
                <select name="vis_status"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    <option value="Ativo"
                        {{ old('vis_status', $visitante->vis_status) === 'Ativo' ? 'selected' : '' }}>
                        Ativo
                    </option>
                    <option value="Inativo"
                        {{ old('vis_status', $visitante->vis_status) === 'Inativo' ? 'selected' : '' }}>
                        Inativo
                    </option>
                </select>
            </div>

            <div class="flex-1"></div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('visitantes') }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                Voltar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Salvar Alterações
            </button>
        </div>
    </form>

</div>

<script>
    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) {
            return;
        }

        const dataNascimento = document.getElementById('vis_data_nasc');
        const hoje = new Date().toISOString().split('T')[0];

        if (dataNascimento.value && dataNascimento.value > hoje) {
            event.preventDefault();
            dataNascimento.setCustomValidity('A data de nascimento não pode ser futura.');
            dataNascimento.reportValidity();
            return;
        }

        dataNascimento.setCustomValidity('');

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    const tel = document.getElementById('vis_telefone');

    if (tel) {
        let valor = tel.value.replace(/\D/g, '');

        if (valor.length === 11) {
            tel.value = `(${valor.slice(0, 2)}) ${valor.slice(2, 7)}-${valor.slice(7, 11)}`;
        } else if (valor.length === 10) {
            tel.value = `(${valor.slice(0, 2)}) ${valor.slice(2, 6)}-${valor.slice(6, 10)}`;
        }

        tel.addEventListener('input', () => {
            let v = tel.value.replace(/\D/g, '').slice(0, 11);
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

    const dataNascimento = document.getElementById('vis_data_nasc');

    if (dataNascimento) {
        dataNascimento.addEventListener('input', () => {
            const hoje = new Date().toISOString().split('T')[0];

            if (dataNascimento.value > hoje) {
                dataNascimento.value = hoje;
            }

            dataNascimento.setCustomValidity('');
        });
    }
</script>

@endsection