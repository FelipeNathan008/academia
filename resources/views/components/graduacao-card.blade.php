<div 
    data-border-color="{{ $borderColor }}"
    data-circle-style="{{ $circleStyle }}"
    class="graduacao-card p-6 {{ $bgColor }} rounded-xl shadow-sm flex justify-between items-center"
>

    <div>
        <h3 class="font-semibold text-lg mb-2 flex items-center gap-3">
            <span 
                class="graduacao-circle"
                data-style="{{ $circleStyle }}"
            ></span>

            {{ $titulo }}
        </h3>

        <span class="text-sm {{ $textColor }}">
            {{ $subtitulo }}
        </span>
    </div>

    <p class="text-3xl font-bold {{ $valueColor }}">
        {{ $valor }}
    </p>
</div>