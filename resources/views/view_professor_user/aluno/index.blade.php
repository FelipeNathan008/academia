@extends('layouts.dashboard')

@section('title', 'Matrículas do Professor')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Meus Alunos / Matrícula / Financeiro
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

            <!-- Responsável -->
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

            <!-- BOTÕES -->
            <div class="flex gap-3">
                <button type="submit"
                    class="h-[48px] px-6 rounded-xl bg-[#8E251F] text-white hover:bg-[#732920] transition shadow-md">
                    Filtrar
                </button>

                <a href="{{ route('professor-aluno.index') }}"
                    class="h-[48px] px-6 rounded-xl bg-gray-300 text-gray-800 flex items-center justify-center hover:bg-gray-400 transition shadow-md">
                    Limpar
                </a>
            </div>

        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4 flex-wrap">
        <span>ALUNOS</span>

        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
            Total: {{ $totalAlunos }}
        </span>

        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
            Filtrados: {{ $alunos->total() }}
        </span>
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Responsável</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Mensalidade</th>
                <th class="py-3 px-4">Bolsista</th>
                <th class="py-3 px-4">Matriculado</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($alunos as $aluno)

            @php $atrasado = false; @endphp

            @foreach($aluno->matriculas as $matricula)
            @foreach($matricula->mensalidades as $mensalidade)
            @foreach($mensalidade->detalhes as $detalhe)
            @if(
            \Carbon\Carbon::parse($detalhe->det_mensa_data_venc)->isPast() &&
            $detalhe->det_mensa_status !== 'Pago'
            )
            @php $atrasado = true; break 3; @endphp
            @endif
            @endforeach
            @endforeach
            @endforeach

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4">
                    @if($aluno->aluno_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                            alt="Foto" style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>

                <td class="py-3 px-4 font-medium">
                    {{ $aluno->aluno_nome }}
                </td>

                <td class="py-3 px-4">
                    {{ $aluno->responsavel->resp_nome }}
                </td>

                <td class="py-3 px-4">
                    {{ strtoupper($aluno->matriculas->first()?->grade?->grade_modalidade ?? 'S/ MATRÍCULA') }}
                </td>

                <td class="py-3 px-4">
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

                <td class="py-3 px-4">
                    @if($aluno->matriculas->count() > 0)
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

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('professor-aluno.hub', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Detalhes
                    </a>

                    <a href="{{ route('professor-matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Matrícula
                    </a>

                </td>

            </tr>

            @empty
            <tr>
                <td colspan="8" class="text-center py-6 text-gray-500">
                    Nenhum aluno encontrado
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