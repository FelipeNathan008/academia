
@props([
    'id' => 'modalExclusao',
    'titulo' => 'Confirmar ação',
    'mensagem' => 'Deseja realmente continuar?',
    'textoConfirmar' => 'Confirmar',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}Titulo">
    <div class="w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
        <div class="border-b border-gray-200 px-6 py-5">
            <h3 id="{{ $id }}Titulo" class="text-lg font-bold text-gray-800">{{ $titulo }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $mensagem }}</p>
        </div>
        <div class="flex flex-col-reverse gap-3 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
            <x-secondary-button onclick="fecharModalExclusao()">Cancelar</x-secondary-button>
            <x-primary-button id="{{ $id }}Confirmar" onclick="executarExclusao('{{ $id }}')">{{ $textoConfirmar }}</x-primary-button>
        </div>
    </div>
</div>

@once
<script>
    let formularioExclusao = null;
    let modalExclusaoAtivo = null;

    function confirmarExclusao(event, form, modalId = 'modalExclusao') {
        event.preventDefault();
        formularioExclusao = form;
        modalExclusaoAtivo = modalId;

        const modal = document.getElementById(modalId);
        if (!modal) return false;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.querySelector('button')?.focus();

        return false;
    }

    function fecharModalExclusao() {
        if (modalExclusaoAtivo) {
            const modal = document.getElementById(modalExclusaoAtivo);
            modal?.classList.add('hidden');
            modal?.classList.remove('flex');
        }

        formularioExclusao = null;
        modalExclusaoAtivo = null;
    }

    function executarExclusao(modalId = modalExclusaoAtivo) {
        if (!formularioExclusao || !modalId) return;

        const form = formularioExclusao;
        const modal = document.getElementById(modalId);
        const botao = document.getElementById(modalId + 'Confirmar');

        if (botao) {
            botao.disabled = true;
            botao.innerText = 'Excluindo...';
        }

        modal?.querySelectorAll('button').forEach(button => button.disabled = true);
        formularioExclusao = null;
        modalExclusaoAtivo = null;
        form.submit();
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && modalExclusaoAtivo) fecharModalExclusao();
    });
</script>
@endonce
