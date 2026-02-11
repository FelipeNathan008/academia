<!-- resources/views/grade_horarios/agenda_semanal.blade.php -->

<style>
    /* Cores das modalidades */
    .modalidade-jiu-jitsu {
        background-color: #e53935;
        color: white;
    }

    .modalidade-judo {
        background-color: #1e88e5;
        color: white;
    }

    .modalidade-NO-GI {
        background-color: #43a047;
        color: white;
    }

    .modalidade-karate {
        background-color: #fb8c00;
        color: white;
    }

    /* Evento */
    .evento {
        border-radius: 0.5rem;
        padding: 0.25rem;
        font-size: 0.75rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        position: relative;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;

        /* Adicionado espaçamento entre eventos */
        margin-bottom: 0.25rem;
    }


    .evento:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Popover */
    .popover {
        display: none;
        position: absolute;
        bottom: 110%;
        /* abriu acima agora */
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
        /* z-index maior para sobrepor outros eventos */
        width: 260px;
        background-color: white;
        color: #1f2937;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        font-size: 0.75rem;
    }

    /* Setinha do popover apontando para baixo agora */
    .popover::before {
        content: "";
        position: absolute;
        bottom: -6px;
        /* seta abaixo do popover */
        left: 50%;
        transform: translateX(-50%) rotate(180deg);
        /* gira a seta para baixo */
        border-width: 6px;
        border-style: solid;
        border-color: transparent transparent white transparent;
    }


    /* Mostrar popover ao clicar */
    .evento.show-popover .popover {
        display: block;
    }

    /* Títulos no popover */
    .popover p {
        margin: 0.25rem 0;
    }

    .popover strong {
        font-weight: 600;
    }

    /* Botões no popover */
    .popover .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
        border-radius: 0.25rem;
        transition: background 0.2s;
    }

    .popover .btn-edit {
        background-color: #1e88e5;
        color: white;
    }

    .popover .btn-edit:hover {
        background-color: #1565c0;
    }

    .popover .btn-delete {
        background-color: #e53935;
        color: white;
    }

    .popover .btn-delete:hover {
        background-color: #b71c1c;
    }
</style>


<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto">

        <!-- Modalidade -->
        <div class="flex flex-col w-[300px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Modalidade
            </label>
            <select id="filtroModalidade"
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                <option value="">Todas</option>
                <option value="Jiu-jitsu">Jiu-Jitsu</option>
                <option value="Judô">Judô</option>
                <option value="NO GI">NO GI</option>
                <option value="Karate">Karate</option>
            </select>
        </div>

        <!-- Professor -->
        <div class="flex flex-col w-[300px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Professor
            </label>
            <select id="filtroProfessor"
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                <option value="">Todos</option>
                @foreach ($grades->pluck('professor.prof_nome')->unique() as $profNome)
                @if($profNome)
                <option value="{{ $profNome }}">{{ $profNome }}</option>
                @endif
                @endforeach
            </select>
        </div>

        <!-- Limpar -->
        <button id="limparFiltros"
            class="self-end h-[48px] px-8 rounded-xl bg-gradient-to-r from-gray-300 to-gray-400
           text-gray-800 font-semibold hover:from-gray-400 hover:to-gray-500
           transition shadow-md">
            Limpar filtros
        </button>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 overflow-x-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-2xl font-bold text-gray-700">
                Agenda Semanal
            </h3>
            <span class="text-xs text-gray-500">
                Clique em um horário para ver detalhes
            </span>
        </div>

        @php
        use Carbon\Carbon;

        $inicioSemana = Carbon::now()->startOfWeek(Carbon::SUNDAY);

        // dias com número (1–7)
        $diasSemana = [
        1 => 'Domingo',
        2 => 'Segunda-feira',
        3 => 'Terça-feira',
        4 => 'Quarta-feira',
        5 => 'Quinta-feira',
        6 => 'Sexta-feira',
        7 => 'Sábado',
        ];

        // gera dias da semana com número correto
        $dias = [];
        for ($i = 1; $i <= 7; $i++) {
            $dias[]=[ 'num'=> $i,
            'nome' => $diasSemana[$i],
            ];
            }

            $mapaClasses = [
            'Jiu-Jitsu' => 'modalidade-jiu-jitsu',
            'Judô' => 'modalidade-judo',
            'NO GI' => 'modalidade-NO-GI',
            'Karate' => 'modalidade-karate',
            ];

            $mapaTurmas = [
            'criancas' => 'Crianças',
            'adultos' => 'Adultos',
            'mulheres' => 'Mulheres',
            ];
            @endphp


            <table class="w-full border border-gray-300 text-sm text-center border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2 w-24">Hora</th>
                        @foreach ($dias as $dia)
                        <th class="border p-2">
                            {{ $dia['nome'] }}
                        </th>
                        @endforeach
                    </tr>
                </thead>


                <tbody>
                    @for ($h = 7; $h <= 22; $h++)
                        <tr>
                        <!-- COLUNA HORA -->
                        <td class="border p-2 font-medium text-gray-600 bg-gray-50">
                            {{ sprintf('%02d:00', $h) }}
                        </td>

                        <!-- COLUNAS DIAS -->
                        @foreach ($dias as $dia)
                        @php
                        $eventos = $grades->filter(function ($g) use ($dia, $h) {
                        $dias = explode(',', $g->grade_dia_semana);

                        return in_array($dia['num'], array_map('intval', $dias))
                        && intval(substr($g->grade_inicio, 0, 2)) === $h;
                        });

                        @endphp

                        <td class="border h-16 align-top p-1">
                            @foreach ($eventos as $evento)
                            @php
                            $classe = $mapaClasses[$evento->grade_modalidade] ?? 'modalidade-jiu-jitsu';
                            @endphp

                            <div class="evento {{ $classe }}"
                                data-modalidade="{{ $evento->grade_modalidade }}"
                                data-professor="{{ $evento->professor->prof_nome ?? '' }}">

                                <span class="text-[10px] font-semibold">
                                    {{ substr($evento->grade_inicio, 0, 5) }} -
                                    {{ substr($evento->grade_fim, 0, 5) }} |
                                    {{ $evento->grade_modalidade }}
                                </span>

                                <div class="popover">
                                    <p><strong>Modalidade:</strong> {{ $evento->grade_modalidade }}</p>
                                    <p>
                                        <strong>Turma:</strong>
                                        {{ $mapaTurmas[$evento->grade_turma] ?? ucfirst($evento->grade_turma) }}
                                    </p>
                                    <p>
                                        <strong>Início:</strong>
                                        {{ \Carbon\Carbon::parse($evento->grade_inicio)->format('H:i') }}
                                    </p>
                                    <p>
                                        <strong>Fim:</strong>
                                        {{ \Carbon\Carbon::parse($evento->grade_fim)->format('H:i') }}
                                    </p>

                                    <p><strong>Descrição:</strong> {{ $evento->grade_desc ?? 'Sem descrição' }}</p>

                                    <div class="flex justify-end gap-2 mt-2">
                                        <a href="{{ route('grade_horarios.edit', $evento->id_grade) }}"
                                            class="btn btn-edit">Editar</a>

                                        <form action="{{ route('grade_horarios.destroy', $evento->id_grade) }}"
                                            method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete">Excluir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </td>
                        @endforeach
                        </tr>
                        @endfor
                </tbody>
            </table>
    </div>
    <script>
        const eventos = document.querySelectorAll('.evento');
        const filtroModalidade = document.getElementById('filtroModalidade');
        const filtroProfessor = document.getElementById('filtroProfessor');
        const limpar = document.getElementById('limparFiltros');

        // Abrir / fechar popover
        eventos.forEach(evento => {
            evento.addEventListener('click', function(e) {
                e.stopPropagation();

                eventos.forEach(ev => {
                    if (ev !== evento) ev.classList.remove('show-popover');
                });

                evento.classList.toggle('show-popover');
            });
        });

        // Fechar popover ao clicar fora
        document.addEventListener('click', () => {
            eventos.forEach(ev => ev.classList.remove('show-popover'));
        });

        // Função de filtro combinada
        function aplicarFiltros() {
            const mod = filtroModalidade.value;
            const prof = filtroProfessor.value;

            eventos.forEach(evento => {
                const evMod = evento.dataset.modalidade;
                const evProf = evento.dataset.professor;

                const okModalidade = !mod || evMod === mod;
                const okProfessor = !prof || evProf === prof;

                evento.style.display = (okModalidade && okProfessor) ? 'block' : 'none';
            });
        }

        filtroModalidade.addEventListener('change', aplicarFiltros);
        filtroProfessor.addEventListener('change', aplicarFiltros);

        limpar.addEventListener('click', () => {
            filtroModalidade.value = '';
            filtroProfessor.value = '';
            aplicarFiltros();
        });
    </script>