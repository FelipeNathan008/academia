@extends('layouts.dashboard')

@section('title', 'Alterar Plano')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('admin.planos') }}"
                class="hover:text-[#8E251F] transition">
                Planos
            </a>
        </li>

        <li>/</li>

        <li class="font-semibold text-gray-700">
            Contrato
        </li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Alterar Plano
        </h2>

        <p class="text-gray-500 mt-2">
            Faça upgrade do seu plano atual para um plano superior.
        </p>
    </div>

    <a href="{{ route('admin.planos') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

</div>

<div class="max-w-4xl mx-auto">

    {{-- Plano atual --}}
    <div class="bg-white rounded-2xl shadow-md border-l-8 border-gray-400 p-6 mb-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Plano Atual
        </p>

        <div class="mt-3">

            <h3 class="text-2xl font-bold text-gray-800">
                {{ $contrato->detalheAtual->det_plano_nome }}
            </h3>

            <div class="mt-3 space-y-1 text-gray-600">

                <p>
                    <strong>Valor atual:</strong>
                    R$
                    {{ number_format(
                        $contrato->detalheAtual->det_plano_valor,
                        2,
                        ',',
                        '.'
                    ) }}
                    / mês
                </p>

                <p>
                    <strong>Limite atual:</strong>
                    {{ $contrato->detalheAtual->det_plano_quantidade }}
                    matrículas
                </p>

            </div>

        </div>

    </div>


    {{-- Novo plano --}}
    <div class="bg-white rounded-2xl shadow-md border-l-8 border-[#8E251F] p-6 mb-6">

        <p class="text-xs uppercase tracking-widest text-[#8E251F]">
            Novo Plano
        </p>

        <div class="mt-3">

            <h3 class="text-2xl font-bold text-gray-800">
                {{ $plano->plano_nome }}
            </h3>

            <div class="mt-3 space-y-1 text-gray-600">

                <p>
                    <strong>Novo valor:</strong>
                    R$
                    {{ number_format(
                        $plano->plano_valor,
                        2,
                        ',',
                        '.'
                    ) }}
                    / mês
                </p>

                <p>
                    <strong>Novo limite:</strong>
                    {{ $plano->plano_quantidade }}
                    matrículas
                </p>

            </div>

        </div>

    </div>


    {{-- Resumo --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">

        <h3 class="font-bold text-blue-800">
            O que acontecerá?
        </h3>

        <ul class="mt-3 space-y-2 text-sm text-blue-700">

            <li>
                ✓ O plano atual será encerrado.
            </li>

            <li>
                ✓ O novo plano passará a ser o plano vigente.
            </li>

            <li>
                ✓ As mensalidades dos próximos meses serão atualizadas
                para o novo valor.
            </li>

            <li>
                ✓ Mensalidades já vencidas ou pagas não serão alteradas.
            </li>

            <li>
                ✓ O contrato continuará sendo o mesmo.
            </li>

        </ul>

    </div>


    {{-- Formulário --}}
    <form
        method="POST"
        action="{{ route(
            'contrato.atualizarPlano',
            Crypt::encrypt($plano->id_plano)
        ) }}" onsubmit="bloquearSubmit(event, this)">

        @csrf

        <div class="bg-white rounded-2xl shadow-md p-6">

            <label class="flex items-start gap-3 cursor-pointer">

                <input
                    type="checkbox"
                    name="aceite_alteracao"
                    value="1"
                    required
                    class="mt-1 w-4 h-4 text-[#8E251F] border-gray-300 rounded focus:ring-[#8E251F]">

                <span class="text-sm text-gray-700">
                    Estou ciente de que a alteração do plano atualizará
                    o valor das mensalidades futuras conforme o novo plano.
                </span>

            </label>


            <div class="flex flex-col sm:flex-row gap-3 mt-6">

                <a
                    href="{{ route('admin.planos') }}"
                    class="w-full sm:w-auto px-6 py-3 text-center rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition font-semibold">
                    Cancelar
                </a>

                <button
                    type="submit" id="btnGerarContrato"
                    class="w-full sm:flex-1 px-6 py-3 rounded-lg bg-[#8E251F] text-white hover:bg-[#732920] transition font-semibold">
                    Confirmar alteração de plano
                </button>

            </div>

        </div>

    </form>

</div>
@endsection
<script>
    function bloquearSubmit(event, form) {

        // Verifica se o checkbox obrigatório foi marcado
        if (!form.checkValidity()) {
            return;
        }

        const confirmar = confirm(
            `Confirma a alteração do plano?\n\n` +
            `Plano atual: {{ $contrato->detalheAtual->det_plano_nome }}\n` +
            `Novo plano: {{ $plano->plano_nome }}\n` +
            `Novo valor mensal: R$ {{ number_format($plano->plano_valor, 2, ',', '.') }}\n\n` +
            `As mensalidades futuras serão atualizadas para o novo valor.\n\n` +
            `Deseja continuar?`
        );

        if (!confirmar) {
            event.preventDefault();
            return;
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Alterando plano...';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
</script>