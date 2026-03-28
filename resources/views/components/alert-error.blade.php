@if ($errors->any())
<div class="bg-gray-100 text-gray-800 p-4 rounded-xl mb-4 border border-gray-300 shadow-sm">

    <div class="flex items-center gap-3 mb-3">
        <span class="text-2xl">⚠️</span>
        <div>
            <p class="font-extrabold text-lg">Erro ao salvar!</p>
            <p class="text-sm">Corrija os campos obrigatórios antes de continuar.</p>
        </div>
    </div>

    <ul class="list-disc pl-6 text-sm space-y-1 font-medium">
        @foreach ($errors->all() as $error)
        <li>• {{ $error }}</li>
        @endforeach
    </ul>

</div>
@endif