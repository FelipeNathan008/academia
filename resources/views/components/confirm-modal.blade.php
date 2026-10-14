@props([
    'id' => 'modalConfirmacao',
    'title' => 'Confirmar ação',
    'message' => 'Deseja realmente continuar?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'confirmId' => 'btnConfirmarAcao',
    'onConfirm' => 'executarExclusao()',
    'onCancel' => 'fecharModalExclusao()'
])

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}Titulo"
>
    <div class="w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
        <div class="border-b border-gray-200 px-6 py-5">
            <h3 id="{{ $id }}Titulo" class="text-lg font-bold text-gray-800">
                {{ $title }}
            </h3>

            <p class="mt-2 text-sm leading-relaxed text-gray-600">
                {{ $message }}
            </p>
        </div>

        <div class="flex flex-col-reverse gap-3 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
            <x-button-secondary
                onclick="{{ $onCancel }}"
                class="bg-white"
            >
                {{ $cancelText }}
            </x-button-secondary>

            <x-button-primary
                :id="$confirmId"
                onclick="{{ $onConfirm }}"
            >
                {{ $confirmText }}
            </x-button-primary>
        </div>
    </div>
</div>