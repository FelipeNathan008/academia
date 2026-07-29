@if (session('success'))

<div class="bg-green-100 border border-green-200 text-green-800 p-4 rounded-xl mb-4 shadow-sm">
    <div class="flex items-start gap-3">
        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600 text-xl">
            ✓
        </div>
        <div class="flex-1">
            <p class="font-extrabold text-lg">
                Operação realizada com sucesso!
            </p>
            <p class="text-sm mt-1">
                {{ session('success') }}
            </p>
        </div>
    </div>
</div>

@endif
