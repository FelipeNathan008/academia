@extends('layouts.dashboard')

@section('title', 'Editar Usuário')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

<!-- TOPO -->
<div class="flex items-center justify-between mb-10">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Editar Usuário
    </h2>

    <a href="{{ route('usuarios.indexEmpresa') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
        ← Voltar
    </a>
</div>

@if ($errors->any())
<div class="mb-6 p-4 bg-red-100 border-l-4 border-red-600 text-red-800 rounded-xl">
    <ul>
        @foreach ($errors->all() as $error)
        <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- CARD -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs text-gray-500 uppercase">Empresa</p>

        <h3 class="text-2xl font-bold text-gray-800 mt-1">
            {{ $user->empresa->emp_nome ?? '-' }}
        </h3>
    </div>
</div>

<!-- FORM -->
<form action="{{route('usuarios.updateEmpresa', Crypt::encrypt($user->id))}}" method="POST" onsubmit="bloquearSubmitEdit(event)">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl shadow-md p-8">

        <input type="hidden" name="id_emp_id" value="{{ $user->id_emp_id }}">
        <input type="hidden" name="id_filial_id" value="">

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-600">Nome</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            @php
            $login = explode('@', $user->email)[0];
            $dominio = '@' . explode('@', $user->email)[1];
            @endphp

            <div style="display:flex; gap:4%;">

                <div style="flex:1;">
                    <label class="text-sm text-gray-600">Login</label>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        value="{{ $login }}"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div style="flex:1;">
                    <label class="text-sm text-gray-600">Domínio</label>
                    <input
                        type="text"
                        value="{{ $dominio }}"
                        readonly
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                </div>

            </div>
            <div>
                <label class="text-sm text-gray-600">Nova Senha</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="senha"
                        placeholder="Deixe em branco para manter"
                        class="w-full border rounded-lg px-4 py-2 mt-1 pr-10"
                        oninput="verificarSenha(this.value)">
                    <button
                        type="button"
                        onclick="toggleSenha()"
                        class="absolute right-3 top-[55%] -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <span id="icone-olho">👁</span>
                    </button>
                </div>

                <!-- Barra de força -->
                <div id="wrapper-forca" class="hidden">
                    <div class="mt-2 h-2 rounded-full bg-gray-200 overflow-hidden">
                        <div id="barra-forca" class="h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="texto-forca" class="text-xs mt-1 font-medium text-gray-400"></p>

                    <!-- Checklist -->
                    <ul class="mt-2 space-y-1 text-xs text-gray-500">
                        <li id="check-min">✗ Mínimo 8 caracteres</li>
                        <li id="check-maiuscula">✗ Letra maiúscula</li>
                        <li id="check-numero">✗ Número</li>
                        <li id="check-especial">✗ Caractere especial (!@#$...)</li>
                    </ul>
                </div>
            </div>


        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('usuarios.indexEmpresa') }}"
                class="px-4 py-2 border rounded-lg">
                Cancelar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                Atualizar
            </button>
        </div>

    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('login').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });

    });

    function verificarSenha(valor) {
        const wrapper = document.getElementById('wrapper-forca');

        if (valor.trim() === '') {
            wrapper.classList.add('hidden');
            return;
        }

        wrapper.classList.remove('hidden');

        const checks = {
            min: valor.length >= 8,
            maiuscula: /[A-Z]/.test(valor),
            numero: /[0-9]/.test(valor),
            especial: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(valor),
        };

        atualizarCheck('check-min', checks.min, 'Mínimo 8 caracteres');
        atualizarCheck('check-maiuscula', checks.maiuscula, 'Letra maiúscula');
        atualizarCheck('check-numero', checks.numero, 'Número');
        atualizarCheck('check-especial', checks.especial, 'Caractere especial (!@#$...)');

        const pontos = Object.values(checks).filter(Boolean).length;

        const niveis = [{
                label: '',
                cor: '',
                largura: '0%'
            },
            {
                label: 'Muito fraca',
                cor: '#ef4444',
                largura: '20%'
            },
            {
                label: 'Fraca',
                cor: '#f97316',
                largura: '45%'
            },
            {
                label: 'Média',
                cor: '#eab308',
                largura: '65%'
            },
            {
                label: 'Forte',
                cor: '#22c55e',
                largura: '100%'
            },
        ];

        const nivel = niveis[pontos];
        const barra = document.getElementById('barra-forca');
        const texto = document.getElementById('texto-forca');

        barra.style.width = nivel.largura;
        barra.style.backgroundColor = nivel.cor;
        texto.textContent = nivel.label;
        texto.style.color = nivel.cor;
    }

    function atualizarCheck(id, passou, rotulo) {
        const el = document.getElementById(id);
        el.textContent = (passou ? '✓ ' : '✗ ') + rotulo;
        el.style.color = passou ? '#16a34a' : '#6b7280';
    }

    function toggleSenha() {
        const input = document.getElementById('senha');
        const icone = document.getElementById('icone-olho');
        input.type = input.type === 'password' ? 'text' : 'password';
        icone.textContent = input.type === 'password' ? '👁' : '🙈';
    }

    function bloquearSubmitEdit(event) {
        const senha = document.getElementById('senha').value;

        if (senha.trim() === '') return true;

        const forte = senha.length >= 8 &&
            /[A-Z]/.test(senha) &&
            /[0-9]/.test(senha) &&
            /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(senha);

        if (!forte) {
            event.preventDefault();
            alert('A senha não atende aos requisitos mínimos de segurança.');
            return false;
        }

        return true;
    }
</script>
@endsection