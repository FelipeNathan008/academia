<div 
    data-border-color="{{ $borderColor }}"
    data-circle-style="{{ $circleStyle }}"
    class="graduacao-card group relative bg-white rounded-2xl shadow-md p-6 
           flex items-center justify-between gap-4
           transition-all duration-200
           hover:shadow-xl hover:-translate-y-1"
>

    <div class="flex items-center gap-4 min-w-0">

        <span 
            class="graduacao-circle w-10 h-10 rounded-full shadow-sm flex-shrink-0"
            data-style="{{ $circleStyle }}"
        ></span>

        <div class="min-w-0">
            <h3 class="font-bold text-base text-gray-800 leading-tight truncate">
                {{ $titulo }}
            </h3>

            <span class="text-xs {{ $textColor }} font-medium">
                {{ $subtitulo }}
            </span>
        </div>
    </div>

    <div class="text-right flex-shrink-0">
        <p class="text-4xl font-extrabold {{ $valueColor }} leading-none">
            {{ $valor }}
        </p>
        <span class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">
            {{ $valor == 1 ? 'aluno' : 'alunos' }}
        </span>
    </div>

</div>