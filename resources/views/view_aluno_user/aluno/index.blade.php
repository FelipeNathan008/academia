@extends('layouts.dashboard')

@section('title', 'Meus Alunos')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            MEUS ALUNOS
        </h2>


    </div>
</div>

<!-- CARD DO RESPONSÁVEL -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">Responsável selecionado</p>
        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $responsavel->resp_nome }}
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            Telefone:
            <strong class="text-gray-800">
                {{ $responsavel->resp_telefone
                    ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $responsavel->resp_telefone)
                    : '-' }}
            </strong>
        </p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Parentesco</th>
                <th class="py-3 px-4">Idade</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Bolsista</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse($alunos as $aluno)

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4">
                    {{ $aluno->aluno_nome }}
                </td>

                <td class="py-3 px-4">
                    {{ $aluno->aluno_parentesco }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->age }} anos
                </td>

                <!-- FOTO -->
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


                <td class="py-3 px-4">

                    @if($aluno->aluno_bolsista === 'sim')

                    <span style="
                        padding:4px 10px;
                        font-size:0.75rem;
                        font-weight:600;
                        border-radius:9999px;
                        color:#166534;
                        background-color:#bbf7d0;">
                        Sim
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

                <td class="py-3 px-4">

                    <a href="{{ route('aluno.show', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Ver aluno
                    </a>

                    <a href="{{ route('aluno-detalhes.index', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Graduações
                    </a>

                    <a href="{{ route('aluno-matricula.index', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #275cce; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Matrícula
                    </a>

                    @if(strtolower($aluno->aluno_bolsista) !== 'sim')
                    <a href="{{ route('aluno-financeiro.index', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>
                    @endif

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Nenhum aluno encontrado
                </td>
            </tr>

            @endforelse

        </tbody>
    </table>

</div>

@endsection