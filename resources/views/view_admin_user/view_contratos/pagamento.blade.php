@extends('layouts.dashboard')

@section('title', 'Pagamento da Mensalidade')

@section('content')

<x-alert-error />
<x-alert-sucess />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex gap-2">
        <li><a href="{{ route('admin.planos') }}" class="hover:text-[#8E251F]">Planos</a></li>
        <li>/</li>

        <li><a href="{{ route('contrato.show', Crypt::encrypt($contrato->id_contrato)) }}" class="hover:text-[#8E251F]">Contrato</a></li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Financeiro</li>
    </ol>
</nav>

<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            Pagamento da Mensalidade
        </h2>
        <p class="text-gray-500 mt-1">
            Efetue o pagamento via Pix e envie o comprovante.
        </p>
    </div>
    <a href="{{ route('contrato.show', Crypt::encrypt($contrato->id_contrato)) }}" class="px-5 py-2 border rounded-lg hover:bg-gray-100">
        ← Voltar
    </a>
</div>


<div class="grid lg:grid-cols-2 gap-8">

    <!-- DADOS -->
    <div class="space-y-6">

        <div class="bg-white rounded-2xl shadow border overflow-hidden">

            <div class="px-6 py-4 border-b bg-gray-50">

                <h3 class="font-bold text-lg text-gray-800">
                    Informações da Mensalidade
                </h3>

            </div>

            <div class="p-6 space-y-5">

                <div class="flex justify-between">

                    <span class="text-gray-500">
                        Nome do Recebedor
                    </span>
                    <span class="font-semibold">
                        {{ $contrato->contrato_pix_recebedor }}
                    </span>

                </div>


                <div class="flex justify-between">
                    <span class="text-gray-500">
                        Plano
                    </span>
                    <span class="font-semibold">
                        {{ $mensalidade->detalheContrato->det_plano_nome ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between">

                    <span class="text-gray-500">
                        Mês
                    </span>

                    <span class="font-semibold">
                        {{ $mensalidade->mensa_contrato_referencia }}
                    </span>

                </div>

                <div class="flex justify-between">

                    <span class="text-gray-500">
                        Chave Pix
                    </span>

                    <span class="font-semibold">
                        {{ $contrato->contrato_pix_chave }}
                    </span>

                </div>

                <div class="flex justify-between">

                    <span class="text-gray-500">
                        Vencimento
                    </span>

                    <span class="font-semibold">
                        {{ \Carbon\Carbon::parse($mensalidade->mensa_contrato_vencimento)->format('d/m/Y') }}
                    </span>

                </div>

                <div class="flex justify-between">

                    <span class="text-gray-500">
                        Valor
                    </span>

                    <span class="text-2xl font-bold text-green-700">
                        R$ {{ number_format($mensalidade->mensa_contrato_valor, 2, ',', '.') }}
                    </span>

                </div>

                <div class="pt-2">

                    @switch($mensalidade->mensa_contrato_status)

                    @case('Pendente')

                    <span class="px-4 py-2 rounded-lg bg-yellow-500 text-white text-sm font-semibold">
                        Pendente
                    </span>

                    @break
                    @case('Pago')

                    <span class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold">
                        Pago
                    </span>

                    @break

                    @case('Comprovante enviado')

                    <span class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold">
                        Comprovante enviado
                    </span>

                    @break

                    @case('Atrasado')

                    <span class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold">
                        Atrasado
                    </span>

                    @break

                    @case('Rejeitado')

                    <span class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold">
                        Rejeitado
                    </span>

                    @break

                    @default

                    <span class="px-4 py-2 rounded-lg bg-yellow-500 text-white text-sm font-semibold">
                        Em aberto
                    </span>

                    @endswitch

                </div>

            </div>

        </div>

        @if($mensalidade->mensa_contrato_status != 'Pago')

        <div class="bg-white rounded-2xl shadow border overflow-hidden">

            <div class="px-6 py-4 border-b bg-gray-50">

                <h3 class="font-bold text-lg text-gray-800">
                    Enviar comprovante
                </h3>

            </div>

            <form
                action="{{ route('contrato-financeiro.comprovante',Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-6">

                @csrf

                @if($mensalidade->mensa_contrato_comprovante)

                <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4">

                    <div class="flex items-center justify-between flex-wrap gap-3">

                        <div>

                            <p class="font-semibold text-blue-800">
                                Comprovante já enviado
                            </p>

                            <p class="text-sm text-blue-700">
                                Caso necessário, você pode visualizar ou enviar um novo comprovante.
                            </p>

                        </div>

                        <a
                            href="{{ route('contrato-financeiro.comprovante.show',
                            Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-white font-semibold hover:bg-blue-700 transition">
                            Ver comprovante
                        </a>
                    </div>

                </div>

                @endif

                <label class="block mb-2 text-sm font-semibold text-gray-700">
                    Selecione o comprovante
                </label>

                <input
                    type="file"
                    name="comprovante"
                    accept=".jpg,.jpeg,.png,.webp,.pdf"
                    required
                    class="block w-full rounded-xl border border-gray-300 bg-gray-50 p-3
            file:mr-4
            file:px-4
            file:py-2
            file:rounded-lg
            file:border-0
            file:bg-[#174ab9]
            file:text-white
            file:cursor-pointer
            hover:file:bg-[#12398f]">

                <button
                    id="btnEnviar"
                    type="submit"
                    class="mt-6 w-full inline-flex items-center justify-center
            rounded-xl bg-green-600 px-6 py-3 text-white font-semibold
            shadow-sm hover:bg-green-700 hover:shadow-lg
            active:scale-[0.98]
            disabled:opacity-60
            disabled:cursor-not-allowed
            transition-all duration-200">

                    {{ $mensalidade->mensa_contrato_comprovante ? 'Substituir comprovante' : 'Enviar comprovante' }}
                </button>
            </form>
        </div>

        @endif


    </div>

    <!-- PAGAMENTO -->
    <div class="space-y-6">

        <div class="bg-white rounded-2xl shadow border overflow-hidden">

            <div class="px-6 py-4 border-b bg-green-50">

                <h3 class="font-bold text-lg text-green-800">
                    Pagamento via Pix
                </h3>

            </div>

            <div class="p-6">

                <!-- QR CODE -->
                <div class="flex justify-center mb-6">
                    {!! QrCode::size(250)
                    ->margin(1)
                    ->generate($payloadPix) !!}
                </div>

                <label class="text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    Pix Copia e Cola
                </label>

                <div class="flex mt-2">

                    <input
                        id="pix"
                        readonly
                        value="{{ $payloadPix }}"
                        class="flex-1 border rounded-l-xl bg-gray-50 px-4 py-3 text-gray-700 break-all">
                    <button
                        type="button"
                        id="copiar"
                        class="px-6 py-3 rounded-r-xl bg-blue-700 text-white font-semibold hover:bg-blue-800 transition-all duration-200
                        hover:shadow-lg active:scale-95">
                        📋 Copiar
                    </button>
                </div>

                <p id="copiado"
                    class="hidden mt-3 text-green-600 text-sm font-semibold">

                    ✓ Código Pix copiado com sucesso.

                </p>

                <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-5">

                    <h4 class="font-bold text-green-700 mb-3">
                        Como realizar o pagamento
                    </h4>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div>① Abra o aplicativo do seu banco.</div>
                        <div>② Acesse a opção <strong>Pix</strong>.</div>
                        <div>③ Escaneie o QR Code ou utilize o Pix Copia e Cola.</div>
                        <div>④ Efetue o pagamento.</div>
                        <div>⑤ Envie o comprovante nesta página.</div>
                        <div>⑥ Aguarde a confirmação do gestor.</div>

                    </div>

                </div>

            </div>

        </div>


    </div>

</div>

<script>
    const copiar = document.getElementById('copiar');

    copiar.addEventListener('click', async () => {
        const codigo = document.getElementById('pix').value;
        try {
            await navigator.clipboard.writeText(codigo);
            const msg = document.getElementById('copiado');
            msg.classList.remove('hidden');
            copiar.disabled = true;
            copiar.disabled = true;
            copiar.classList.remove('bg-blue-700', 'hover:bg-blue-800');
            copiar.classList.add('bg-green-600');
            copiar.innerHTML = '✓ Copiado';

            setTimeout(() => {
                copiar.disabled = false;
                copiar.classList.remove('bg-green-600');
                copiar.classList.add('bg-blue-700', 'hover:bg-blue-800');
                copiar.innerHTML = '📋 Copiar';
            }, 3000);
        } catch (error) {
            alert('Não foi possível copiar o código Pix.');
        }
    });

    const form = document.querySelector('form[action*="comprovante"]');

    if (form) {

        form.addEventListener('submit', function() {

            document.getElementById('btnEnviar').disabled = true;

            document.getElementById('btnEnviar').innerHTML =
                'Enviando comprovante...';
        });

    }
</script>

@endsection