@extends('layouts.dashboard')

@section('title', 'Matrículas e Financeiro')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">

    <h2 class="text-3xl font-extrabold text-gray-800">
        Visualizar Alunos Matrícula / Modalidade / Financeiro
    </h2>

</div>

<!-- FILTROS -->
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto mb-8">
    <form method="GET">
        <div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto">

            <!-- Buscar Aluno -->
            <div class="flex flex-col w-[300px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                    Buscar Aluno
                </label>
                <input type="text" name="nome"
                    value="{{ request('nome') }}"
                    placeholder="Digite o nome..."
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <!-- Buscar Responsável -->
            <div class="flex flex-col w-[300px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                    Buscar Responsável
                </label>
                <input type="text" name="responsavel"
                    value="{{ request('responsavel') }}"
                    placeholder="Digite o responsável..."
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <!-- Bolsista -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                    Bolsista
                </label>
                <select name="bolsista"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    <option value="">Todos</option>
                    <option value="sim" {{ request('bolsista') == 'sim' ? 'selected' : '' }}>Sim</option>
                    <option value="nao" {{ request('bolsista') == 'nao' ? 'selected' : '' }}>Não</option>
                </select>
            </div>

            <!-- Matrícula -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                    Matrícula
                </label>
                <select name="matricula"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    <option value="">Todos</option>
                    <option value="Matriculado" {{ request('matricula') == 'Matriculado' ? 'selected' : '' }}>
                        Matriculado
                    </option>
                    <option value="Encerrada" {{ request('matricula') == 'Encerrada' ? 'selected' : '' }}>
                        Não Matriculado
                    </option>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex gap-3">

                <button type="submit"
                    class="h-[48px] px-6 rounded-xl bg-[#8E251F] text-white
                           hover:bg-[#732920] transition shadow-md">
                    Filtrar
                </button>

                <a href="{{ route('matricula.index') }}"
                    class="h-[48px] px-6 rounded-xl bg-gray-300 text-gray-800
                           flex items-center justify-center
                           hover:bg-gray-400 transition shadow-md">
                    Limpar
                </a>

            </div>

        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4 flex-wrap">
        <span>ALUNOS CADASTRADOS</span>

        <!-- TOTAL GERAL -->
        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Total: {{ $totalAlunos }}
        </span>

        <!-- TOTAL FILTRADO -->
        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Filtrados: {{ $alunos->total() }}
        </span>
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Idade</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Mensalidade</th>
                <th class="py-3 px-4">Bolsista</th>
                <th class="py-3 px-4">Matriculado</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($alunos as $aluno)

            @php
            $nascimento = $aluno->aluno_nascimento
            ? \Carbon\Carbon::parse($aluno->aluno_nascimento)
            : null;
            @endphp

            <tr class="border-b hover:bg-gray-50 transition linha-aluno"
                data-nome="{{ strtolower($aluno->aluno_nome) }}"
                data-bolsista="{{ strtolower($aluno->aluno_bolsista) }}"
                data-responsavel="{{ strtolower($aluno->responsavel->resp_nome ?? '') }}"
                data-matricula="{{ $aluno->matriculas->count() > 0 ? 'com' : 'sem' }}">


                <!-- NOME -->
                <td class="py-3 px-4 font-medium text-gray-800">
                    {{ $aluno->aluno_nome }}
                </td>

                <!-- IDADE -->
                <td class="py-3 px-4">
                    {{ $nascimento ? $nascimento->age : '-' }}
                </td>

                <!-- MODALIDADE  -->
                <td class="py-3 px-4">
                    @if($aluno->matriculas->count() === 0)
                    <span class="py-3 px-4 font-medium text-gray-800">
                        S/ MATRÍCULA
                    </span>

                    @elseif($aluno->matriculas->count() === 1)
                    <span class="py-3 px-4 font-medium text-gray-800">
                        {{ Str::upper($aluno->matriculas->first()?->grade?->grade_modalidade) }}
                    </span>

                    @else
                    <span class="py-3 px-4 font-medium text-gray-800">
                        MMA
                    </span>
                    @endif
                </td>

                <!-- MENSALIDADES  -->
                <td class="py-3 px-4">
                    @php
                    $atrasado = false;
                    @endphp

                    @foreach($aluno->matriculas as $matricula)
                    @foreach($matricula->mensalidades as $mensalidade)
                    @foreach($mensalidade->detalhes as $detalhe)
                    @if(
                    \Carbon\Carbon::parse($detalhe->det_mensa_data_venc)->isPast() &&
                    $detalhe->det_mensa_status !== 'Pago'
                    )
                    @php
                    $atrasado = true;
                    break 3; // sai de todos os loops
                    @endphp
                    @endif
                    @endforeach
                    @endforeach
                    @endforeach

                    @if($atrasado)
                    <span style="padding:2px 8px; font-size:0.75rem;
                        font-weight:600; border-radius:9999px;
                        color:#991b1b; background-color:#fecaca;">
                        Atrasado
                    </span>
                    @else
                    <span style="padding:2px 8px; font-size:0.75rem;
                    font-weight:600; border-radius:9999px;
                    color:#166534; background-color:#bbf7d0;">
                        Em dia
                    </span>
                    @endif
                </td>

                <!-- BOLSISTA -->
                <td class="py-3 px-4">
                    @if(strtolower($aluno->aluno_bolsista) === 'sim')
                    <span style="padding:2px 8px; font-size:0.75rem;
                    font-weight:600; border-radius:9999px;
                    color:#166534; background-color:#bbf7d0;">
                        Sim
                    </span>
                    @else
                    <span style="padding:2px 8px; font-size:0.75rem;
                    font-weight:600; border-radius:9999px;
                    color:#444; background-color:#f3f4f6;">
                        Não
                    </span>
                    @endif
                </td>

                <!-- Matriculado -->
                <td class="py-3 px-4">
                    @if($aluno->matriculas->count() > 0)
                    <span style="padding:2px 8px; font-size:0.75rem;
                    font-weight:600; border-radius:9999px;
                    color:#166534; background-color:#bbf7d0;"> 🎓 Sim
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full text-gray-700 bg-gray-200">
                        Não
                    </span>
                    @endif
                </td>

                <!-- AÇÕES -->
                <td class="py-3 px-4 flex gap-2">

                    @if($aluno->responsavel)
                    <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel->id_responsavel)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Ver Aluno
                    </a>
                    @endif

                    @if(strtolower($aluno->aluno_bolsista) !== 'sim' && $aluno->matriculas->count() > 0)
                    <a href="{{ route('mensalidade', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>
                    @endif

                    @if($aluno->matriculas->count() == 0)
                    <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                        class="btn-editar px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #ca8a04;">
                        Matricular
                    </a>
                    @else
                    <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Ver Matrícula
                    </a>
                    @endif
                </td>

            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center py-6 text-gray-500">
                    Nenhum aluno cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-6">
        {{ $alunos->links() }}
    </div>
</div>

@endsection