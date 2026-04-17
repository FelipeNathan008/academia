@extends('layouts.dashboard')

@section('title', 'Professores')
@section('content')


<x-alert-error />

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Professores</h2>
    </div>

</div>


<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">LISTA DE PROFESSORES</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Idade</th>
                <th class="py-3 px-4">Empresa</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Qtd. Alunos</th>
                <th class="py-3 px-4">Graduado</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($professores as $professor)

            @php
            $nascimento = $professor->prof_nascimento
            ? \Carbon\Carbon::parse($professor->prof_nascimento)
            : null;
            $hoje = \Carbon\Carbon::today();
            @endphp

            <tr class="border-b transition professor-row"
                data-prof="{{ $professor->id_professor }}">
                <td class="py-3 px-4">{{ $professor->prof_nome }}</td>



                <td class="py-3 px-4">
                    @if($nascimento)
                    {{ $nascimento->age }}

                    @if ($nascimento->isBirthday())
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem; font-weight:600;
                    border-radius:9999px; color:#166534; background-color:#bbf7d0;">
                        🧁 Hoje
                    </span>
                    @elseif ($nascimento->month === $hoje->month)
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem; font-weight:600;
                    border-radius:9999px; color:#854d0e; background-color:#fef3c7;">
                        🎉 Este mês
                    </span>
                    @endif
                    @else
                    -
                    @endif
                </td>


                <td class="py-3 px-4 font-bold">
                    {{ $professor->empresas->emp_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    @if($professor->prof_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/professores/' . $professor->prof_foto) }}"
                            alt="Foto" style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>

                <td class="py-3 px-4 font-bold">
                    {{ $professor->qtd_aluno ?? '0'}}
                </td>

                <!-- Graduado -->
                <td class="py-3 px-4">
                    @if($professor->detalhes->where('professor_id_professor',$professor->id_professor)->count() > 0)
                    <span style="padding:2px 8px; font-size:0.75rem;
                        font-weight:600; border-radius:9999px;
                        color:#166534; background-color:#bbf7d0;"> 🥋 Sim
                    </span>
                    @else
                    <span style="
                        padding:4px 10px;
                        font-size:0.75rem;
                        font-weight:600;
                        border-radius:9999px;
                        color:#7f1d1d;
                        background-color:#fecaca;">
                        Não
                    </span>
                    @endif
                </td>


                <td class="py-3 px-4 flex gap-2">
                    <button type="button"
                        data-id="{{ $professor->id_professor }}"
                        class="btn-ver-prof px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #275cce; color: white;">
                        Ver Alunos
                    </button>

                    <a href="{{ route('professores.show', Crypt::encrypt($professor->id_professor)) }}"
                        style="background-color: #ca8a04; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Ver Professor
                    </a>

                </td>
            </tr>

            <!--ABA OCULTA -->
            <tr id="detalhe-prof-{{ $professor->id_professor }}" class="hidden bg-gray-50">
                <td colspan="8" class="px-6 py-6">

                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">

                        <!-- HEADER -->
                        <div class="px-4 py-3 bg-gray-100 border-b">
                            <h4 class="text-sm font-semibold text-gray-700">
                                Alunos do Professor
                            </h4>
                        </div>

                        <!-- TABELA -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Aluno</th>
                                        <th class="px-4 py-3">Idade</th>
                                        <th class="px-4 py-3">Modalidade</th>
                                        <th class="px-4 py-3">Mensalidade</th>
                                        <th class="px-4 py-3">Bolsista</th>
                                        <th class="px-4 py-3">Ações</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">

                                    @forelse($professor->alunos as $aluno)
                                    @php
                                    $nascimento = $aluno->aluno_nascimento
                                    ? \Carbon\Carbon::parse($aluno->aluno_nascimento)
                                    : null;
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition">

                                        <td class="px-4 py-3">
                                            {{ $aluno->aluno_nome }}
                                        </td>

                                        <!-- IDADE -->
                                        <td class="py-3 px-4">
                                            {{ $nascimento ? $nascimento->age : '-' }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $aluno->grade_modalidade ?? '-' }}
                                        </td>

                                        <!-- MENSALIDADES  -->
                                        <td class="py-3 px-4">
                                            @if($aluno->atrasado)
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


                                        <td class="py-3 px-4 flex gap-2">

                                            {{-- VER ALUNO --}}
                                            @if($aluno->id_responsavel)
                                            <a href="{{ route('alunos.show', Crypt::encrypt($aluno->id_aluno)) }}"
                                                style="background-color: #174ab9; color: white;"
                                                class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                                                Detalhes
                                            </a>
                                            @endif

                                            {{-- FINANCEIRO --}}
                                            @if(strtolower($aluno->aluno_bolsista) !== 'sim' && $aluno->matriculado > 0)
                                            <a href="{{ route('mensalidade', Crypt::encrypt($aluno->id_aluno)) }}"
                                                style="background-color: #15803d; color: white;"
                                                class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                                                Financeiro
                                            </a>
                                            @endif

                                            {{-- MATRÍCULA --}}
                                            @if($aluno->matriculado == 0)
                                            <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                                                class="px-4 py-2 rounded-lg shadow text-white"
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
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-400">
                                            Nenhum aluno vinculado
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="8" class="text-center text-gray-500 py-6">Nenhum professor cadastrado</td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

<!-- JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const botoes = document.querySelectorAll(".btn-ver-prof");

        botoes.forEach(botao => {
            botao.addEventListener("click", function() {

                const id = this.dataset.id;
                const detalhe = document.getElementById("detalhe-prof-" + id);

                detalhe.classList.toggle("hidden");

            });
        });

    });
</script>

@endsection