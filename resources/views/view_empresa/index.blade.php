@extends('layouts.dashboard')

@section('title', 'Empresa')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Empresa</h2>
    </div>

    <button onclick="toggleEdicao()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        Editar Empresa
    </button>
</div>

<!-- CARD -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Dados da Empresa
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $empresa->emp_nome }}
        </h3>

        <p class="text-gray-600 mt-2">
            {{ $empresa->emp_apelido }}
        </p>
    </div>
</div>

<!-- FORM EDIT -->
<div id="editForm" class="hidden mb-10">
    <form action="{{ route('empresa.update', urlencode(Crypt::encrypt($empresa->id_empresa))) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Editar Empresa</h3>

            <div class="flex flex-col gap-4">

                <!-- Nome + Apelido -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Nome</label>
                        <input type="text" name="emp_nome"
                            value="{{ $empresa->emp_nome }}"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Apelido</label>
                        <input type="text" name="emp_apelido"
                            value="{{ $empresa->emp_apelido }}"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>
                <input type="hidden" name="emp_tipo" value="{{ $empresa->emp_tipo }}">

                <!-- Responsável -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Responsável</label>
                        <input type="text" name="emp_nome_responsavel"
                            value="{{ $empresa->emp_nome_responsavel }}"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Email</label>
                        <input type="email" name="emp_email_responsavel"
                            value="{{ $empresa->emp_email_responsavel }}"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- Telefone + CPF -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Telefone</label>
                        <input type="text" id="telefone"
                            name="emp_telefone_responsavel"
                            value="{{ $empresa->emp_telefone_responsavel }}"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">CPF</label>
                        <input type="text" name="emp_cpf"
                            value="{{ $empresa->emp_cpf }}"
                            oninput="mascaraCPF(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- FOTO -->
                <div>
                    <label class="text-sm text-gray-600">Logo</label>
                    <input type="file" name="emp_foto" id="logoInput" accept="image/*">

                    <div style="margin-top:10px;">
                        <img id="previewLogo" style="display:none;max-height:120px;border-radius:8px;">
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharEdicao()" class="px-4 py-2 border rounded-lg">
                    Cancelar
                </button>

                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- DETALHES -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Informações</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <p class="text-gray-500 text-sm">Responsável</p>
            <p class="font-semibold">{{ $empresa->emp_nome_responsavel }}</p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Email</p>
            <p class="font-semibold">{{ $empresa->emp_email_responsavel }}</p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Telefone</p>
            <p class="font-semibold">{{ $empresa->emp_telefone_responsavel }}</p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">CPF</p>
            <p class="font-semibold">{{ $empresa->emp_cpf }}</p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Tipo</p>
            <p class="font-semibold">{{ $empresa->emp_tipo }}</p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Logo</p>
            @if($empresa->emp_foto)
            <img src="{{ asset('images/empresas/' . $empresa->emp_foto) }}"
                class="w-16 h-16 object-cover rounded">
            @else
            -
            @endif
        </div>

    </div>
</div>

<!-- JS -->
<script>
    const input = document.getElementById("logoInput");

    if (input) {
        input.addEventListener("change", function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById("previewLogo");
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                }
                reader.readAsDataURL(file);
            }
        });
    }



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

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;

        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function mascaraCPF(input) {
        let value = input.value.replace(/\D/g, '').slice(0, 11);

        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        input.value = value;
    }

    const tel = document.getElementById('telefone');

    if (tel) {
        tel.addEventListener('input', () => {
            let v = tel.value.replace(/\D/g, '');

            if (v.length > 11) v = v.slice(0, 11);

            let formatado = '';

            if (v.length > 0) formatado = '(' + v.slice(0, 2);
            if (v.length >= 3) formatado += ') ' + v.slice(2, 7);
            if (v.length >= 8) formatado += '-' + v.slice(7, 11);

            tel.value = formatado;
        });
    }
</script>

@endsection