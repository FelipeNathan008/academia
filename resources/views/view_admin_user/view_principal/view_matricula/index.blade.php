@extends('layouts.dashboard')

@section('title', 'Matrículas do Aluno')

@section('content')

<x-alert-error />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li><a href="{{ route('responsaveis') }}" class="hover:text-[#8E251F] transition">Responsáveis</a></li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->responsavel->resp_nome }}</li>
        <li>/</li>
        <li><a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}" class="hover:text-[#8E251F] transition">Alunos</a></li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->aluno_nome }}</li>
        <li>/</li>
        <li><a href="{{ route('detalhes-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}" class="hover:text-[#8E251F] transition">Graduações</a></li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Matrícula</li>
        <li>/</li>
        <li class="text-gray-400">Financeiro</li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}" class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">← Voltar</a>
        <h2 class="text-3xl font-extrabold text-gray-800">Matrículas do Aluno</h2>
    </div>

    @if($limiteAtingido)
    <div class="flex flex-col items-end gap-2">
        <button type="button" disabled class="px-6 py-3 bg-gray-400 text-white rounded-xl shadow-md cursor-not-allowed">+ Nova Matrícula</button>
        <p class="text-sm text-red-600 font-medium">Limite de matrículas do plano atingido.</p>
    </div>
    @else
    <button onclick="toggleCadastro()" class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">+ Nova Matrícula</button>
    @endif
</div>

@php
$limiteBase = $limiteMatriculas ?? 0;
$limitePermitido = $limiteBase > 0 ? $limiteBase + 3 : 0;
$vagasRestantes = $limitePermitido > 0 ? max($limitePermitido - $quantidadeMatriculas, 0) : 0;
$percentualUso = $limitePermitido > 0 ? min(($quantidadeMatriculas / $limitePermitido) * 100, 100) : 0;
@endphp

@if($contrato && $contrato->detalheAtual && $contrato->detalheAtual->det_plano_tipo != 'financeiro' && $contagemMatriculas >= $contrato->detalheAtual->det_plano_quantidade)
<div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 mb-4">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="text-yellow-600">⚠️</span>
            <p class="text-sm text-yellow-800">
                O limite de matrículas do seu plano foi excedido.
                <strong>{{ $contagemMatriculas }}/{{ $contrato->detalheAtual->det_plano_quantidade }}</strong> matrículas utilizadas.
            </p>
        </div>
        <a href="{{ route('admin.planos') }}" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">Atualizar contrato</a>
    </div>
</div>
@endif

<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500">Utilização do plano</p>

                    @if($planoFinanceiro)
                    <h3 class="text-xl font-bold text-gray-800 mt-1">Pacote adicional</h3>
                    <p class="text-sm text-gray-500 mt-1">Este plano não possui limite de matrículas.</p>
                    @elseif($limiteMatriculas !== null && $limiteMatriculas > 0)
                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                        {{ $quantidadeMatriculas }} <span class="text-gray-400 font-medium">/ {{ $limiteBase }}</span> matrículas
                    </h3>
                    @else
                    <h3 class="text-xl font-bold text-gray-500 mt-1">Limite não definido</h3>
                    @endif
                </div>

                @if(!$planoFinanceiro && $limiteBase > 0)
                @if($quantidadeMatriculas > $limiteBase)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">Acima do limite contratado</span>
                @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Dentro do limite</span>
                @endif
                @endif
            </div>
        </div>

        @if(!$planoFinanceiro && $limiteBase > 0)
        <div class="px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-3">
                <div>
                    <p class="text-sm text-gray-500">Matrículas utilizadas</p>
                    <p class="text-2xl font-extrabold text-gray-800">
                        {{ $quantidadeMatriculas }}
                        <span class="text-sm font-medium text-gray-400">de {{ $limitePermitido }} permitidas</span>
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-sm font-semibold {{ $vagasRestantes > 0 ? 'text-gray-600' : 'text-red-600' }}">
                        {{ $vagasRestantes }} {{ $vagasRestantes == 1 ? 'vaga disponível' : 'vagas disponíveis' }}
                    </p>
                </div>
            </div>

            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                @php
                $larguraBarra = $percentualUso <= 10 ? 'w-[10%]' : ($percentualUso <=20 ? 'w-[20%]' : ($percentualUso <=30 ? 'w-[30%]' : ($percentualUso <=40 ? 'w-[40%]' : ($percentualUso <=50 ? 'w-[50%]' : ($percentualUso <=60 ? 'w-[60%]' : ($percentualUso <=70 ? 'w-[70%]' : ($percentualUso <=80 ? 'w-[80%]' : ($percentualUso <=90 ? 'w-[90%]' : 'w-full' ))))))));
                    $corBarra=$quantidadeMatriculas> $limiteBase ? 'bg-yellow-500' : 'bg-[#8E251F]';
                    @endphp
                    <div class="h-3 rounded-full transition-all duration-500 {{ $larguraBarra }} {{ $corBarra }}"></div>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 mt-3 text-xs text-gray-500">
                <span>Limite contratado: <strong class="text-gray-700">{{ $limiteBase }}</strong></span>
                <span>Tolerância: <strong class="text-gray-700">+3</strong></span>
                <span>Máximo permitido: <strong class="text-gray-700">{{ $limitePermitido }}</strong></span>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">Aluno selecionado</p>

        <div class="flex items-center gap-3 mt-1">
            <h3 class="text-2xl font-extrabold text-gray-800">{{ $aluno->aluno_nome }}</h3>

            @if(strtolower($aluno->aluno_bolsista) === 'sim')
            <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #fef9c3; color: #854d0e;">🎓 Bolsista</span>
            @else
            <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #f3f4f6; color: #6b7280;">Não bolsista</span>
            @endif
        </div>

        <p class="mt-2 text-sm text-gray-600">
            Data de nascimento:
            <strong class="text-gray-800">{{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}</strong><br>
            Idade:
            <strong class="text-gray-800">{{ $aluno->aluno_nascimento ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->age : '-' }}</strong>
        </p>
    </div>
</div>

<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('matricula.store', Crypt::encrypt($aluno->id_aluno)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-xl font-bold text-gray-700 mb-6">Nova Matrícula</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select id="modalidadeSelect" required class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione a modalidade</option>
                        @foreach($grades->pluck('grade_modalidade')->unique() as $modalidade)
                        <option value="{{ $modalidade }}">{{ $modalidade }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Categoria</label>
                    <select name="matri_categoria" id="categoriaSelect" required disabled class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione a modalidade primeiro</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Plano</label>
                    <select name="matri_plano" id="planoSelect" required disabled class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione a categoria primeiro</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Data</label>
                    <input type="date" name="matri_data" required min="{{ $aluno->aluno_nascimento }}" max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>



                <div>
                    <label class="text-sm font-medium text-gray-600">Turma</label>
                    <select id="turmaSelect" required disabled class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione a turma</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Horário</label>
                    <select name="grade_id_grade" id="gradeSelect" required disabled class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione o horário</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Dia do vencimento</label>

                    @if(strtolower($aluno->aluno_bolsista) === 'sim')
                    <select id="matri_dia_vencimento" name="matri_dia_vencimento" disabled class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100 text-gray-400 cursor-not-allowed">
                        <option value="">Não se aplica — aluno bolsista</option>
                    </select>

                    <p class="mt-2 text-xs text-gray-500">
                        Alunos bolsistas não possuem cobrança mensal.
                    </p>
                    @else
                    <select id="matri_dia_vencimento" name="matri_dia_vencimento" required class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione</option>
                        @foreach($diasVencimento as $dia)
                        <option value="{{ $dia->dia_vencimento }}" {{ old('matri_dia_vencimento') == $dia->dia_vencimento ? 'selected' : '' }}>
                            Dia {{ str_pad($dia->dia_vencimento, 2, '0', STR_PAD_LEFT) }}
                        </option>
                        @endforeach
                    </select>

                    <div id="infoPrimeiraParcelaMatricula" class="hidden mt-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
                            </svg>

                            <div class="text-sm text-blue-800">
                                <p class="font-semibold">Primeira mensalidade</p>
                                <p class="mt-1">
                                    A entrada será gerada na data informada. A primeira mensalidade vencerá em
                                    <strong id="dataPrimeiraParcelaMatricula"></strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="matri_desc" required rows="3" placeholder="Ex.: Matrícula anual realizada sem bolsa" class="w-full border rounded-lg px-4 py-2 mt-1"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()" class="px-5 py-2 border rounded-lg hover:bg-gray-100">Cancelar</button>
                <button type="submit" class="px-6 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">Cadastrar</button>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">HISTÓRICO DE MATRÍCULAS</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($matriculas as $matricula)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-4 px-4">
                    <p class="font-semibold text-gray-800">{{ ucfirst($matricula->grade->grade_turma ?? '-') }}</p>
                    <p class="text-sm text-gray-600">{{ $matricula->grade->grade_modalidade ?? '-' }}</p>
                    <p class="text-sm text-gray-500">Categoria: <strong>{{ $matricula->matri_categoria ?? '-' }}</strong></p>
                    <p class="text-sm text-gray-500">Plano: <strong>{{ $matricula->matri_plano }}</strong></p>
                    <p class="text-sm text-gray-500">Prof. {{ $matricula->grade->professor->prof_nome ?? '-' }}</p>

                    @if($matricula->grade)
                    <p class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_inicio)->format('H:i') }} às
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_fim)->format('H:i') }}
                    </p>
                    @endif
                </td>

                <td class="py-3 px-4">
                    @if($matricula->matri_status === 'Matriculado')
                    <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background-color: #dcfce7; color: #166534;">Matriculado</span>
                    @elseif($matricula->matri_status === 'Pausada')
                    <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background-color: #fef9c3; color: #854d0e;">Pausada</span>
                    @else
                    <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background-color: #fee2e2; color: #991b1b;">Encerrada</span>
                    @endif
                </td>

                <td class="py-3 px-4 flex gap-2 flex-wrap">
                    <a href="{{ route('matricula.show', Crypt::encrypt($matricula->id_matricula)) }}" style="background-color: #174ab9; color: white;" class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition text-center">Ver Matrícula</a>

                    @if(strtolower($aluno->aluno_bolsista) !== 'sim')
                    <a href="{{ route('mensalidade', Crypt::encrypt($matricula->aluno_id_aluno)) }}" style="background-color: #15803d; color: white;" class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition text-center">Financeiro</a>
                    @endif

                    @if($matricula->matri_status === 'Matriculado')
                    <button type="button" onclick="abrirModalMotivo('{{ Crypt::encrypt($matricula->id_matricula) }}', 'pausar', '{{ $matricula->matri_data }}')" class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">Pausar</button>

                    <button type="button" onclick="abrirModalMotivo('{{ Crypt::encrypt($matricula->id_matricula) }}', 'encerrar', '{{ $matricula->matri_data_pausa ?? $matricula->matri_data }}')" style="background-color: #7c3aed; color: white;" class="px-4 py-3 rounded-lg shadow hover:opacity-90 transition text-center font-semibold">Encerrar</button>
                    @endif

                    @if($matricula->matri_status === 'Pausada')
                    <form action="{{ route('matricula.reativar', Crypt::encrypt($matricula->id_matricula)) }}" method="POST" onsubmit="return confirm('Deseja reativar esta matrícula?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 rounded-lg shadow text-white" style="background:#8E251F;">Reativar</button>
                    </form>

                    <button type="button" onclick="abrirModalMotivo('{{ Crypt::encrypt($matricula->id_matricula) }}', 'encerrar', '{{ $matricula->matri_data_pausa ?? $matricula->matri_data }}')" style="background-color: #7c3aed; color: white;" class="px-4 py-3 rounded-lg shadow hover:opacity-90 transition text-center font-semibold">Encerrar</button>
                    @endif

                    @if($matricula->matri_status === 'Encerrada')
                    <form action="{{ route('matricula.destroy', Crypt::encrypt($matricula->id_matricula)) }}" method="POST" onsubmit="return confirm('Deseja remover esta matrícula?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">Excluir</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">Nenhuma matrícula encontrada</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div id="modalMotivo" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
            <h3 id="modalMotivtitulo" class="text-xl font-bold mb-4 text-gray-800">Informe o motivo</h3>

            <form id="formMotivo" method="POST" onsubmit="bloquearSubmit(event, this)">
                @csrf
                @method('PUT')

                <textarea name="matri_motivo" rows="4" required placeholder="Descreva o motivo..." class="w-full border rounded-lg px-4 py-2 mb-4"></textarea>

                <label id="modalDataLabel" class="text-sm font-medium text-gray-600">Data do evento</label>
                <input type="date" name="matri_data_evento" id="modalDataEvento" required class="w-full border rounded-lg px-4 py-2 mt-1 mb-6">

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="fecharModalMotivo()" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="gradesData" data-grades='@json($grades)'></div>
<div id="precosData" data-precos='@json($precos)'></div>

<script>
    function calcularPrimeiraParcelaMatricula() {
        const select = document.getElementById('matri_dia_vencimento');
        const dataMatriculaInput = document.querySelector('input[name="matri_data"]');
        const info = document.getElementById('infoPrimeiraParcelaMatricula');
        const dataElement = document.getElementById('dataPrimeiraParcelaMatricula');

        if (!select || !dataMatriculaInput || !info || !dataElement) return;

        if (select.disabled) {
            info.classList.add('hidden');
            return;
        }

        const diaVencimento = parseInt(select.value);
        const dataMatricula = dataMatriculaInput.value;

        if (!diaVencimento || !dataMatricula) {
            info.classList.add('hidden');
            return;
        }

        const [ano, mes, dia] = dataMatricula.split('-').map(Number);

        let primeiraData = new Date(ano, mes - 1, 1);
        const ultimoDiaMes = new Date(ano, mes, 0).getDate();

        primeiraData.setDate(Math.min(diaVencimento, ultimoDiaMes));

        const dataReferencia = new Date(ano, mes - 1, dia);

        if (primeiraData <= dataReferencia) {
            primeiraData = new Date(ano, mes, 1);

            const ultimoDiaProximoMes = new Date(ano, mes + 1, 0).getDate();

            primeiraData.setDate(
                Math.min(diaVencimento, ultimoDiaProximoMes)
            );
        }

        dataElement.textContent = primeiraData.toLocaleDateString('pt-BR');
        info.classList.remove('hidden');
    }

    document.getElementById('matri_dia_vencimento')?.addEventListener('change', calcularPrimeiraParcelaMatricula);
    document.querySelector('input[name="matri_data"]')?.addEventListener('change', calcularPrimeiraParcelaMatricula);

    function bloquearSubmit(event, form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function abrirModalMotivo(idCriptografado, acao, dataMinima) {
        const modal = document.getElementById('modalMotivo');
        const form = document.getElementById('formMotivo');
        const titulo = document.getElementById('modalMotivtitulo');
        const labelData = document.getElementById('modalDataLabel');
        const inputData = document.getElementById('modalDataEvento');
        const baseUrl = "{{ url('matricula') }}";

        inputData.removeAttribute('max');
        inputData.removeAttribute('min');

        if (acao === 'pausar') {
            form.action = `${baseUrl}/${idCriptografado}/pausar`;
            titulo.textContent = 'Motivo da Pausa';
            labelData.textContent = 'Data da Pausa';
            inputData.max = new Date().toISOString().split('T')[0];
            if (dataMinima) inputData.min = dataMinima;
        } else {
            form.action = `${baseUrl}/${idCriptografado}/encerrar`;
            titulo.textContent = 'Motivo do Encerramento';
            labelData.textContent = 'Data do Encerramento';
            if (dataMinima) inputData.min = dataMinima;
        }

        modal.classList.remove('hidden');
    }

    function fecharModalMotivo() {
        const modal = document.getElementById('modalMotivo');
        const form = document.getElementById('formMotivo');
        modal.classList.add('hidden');
        form.reset();
        form.querySelector('#modalDataEvento').removeAttribute('max');
        form.querySelector('#modalDataEvento').removeAttribute('min');
    }

    const grades = JSON.parse(document.getElementById('gradesData').dataset.grades);
    const precos = JSON.parse(document.getElementById('precosData').dataset.precos);
    const modalidadeSelect = document.getElementById('modalidadeSelect');
    const turmaSelect = document.getElementById('turmaSelect');
    const gradeSelect = document.getElementById('gradeSelect');
    const planoSelect = document.getElementById('planoSelect');
    const categoriaSelect = document.getElementById('categoriaSelect');

    modalidadeSelect.addEventListener('change', function() {
        categoriaSelect.innerHTML = '<option value="">Selecione a categoria</option>';
        categoriaSelect.disabled = true;
        planoSelect.innerHTML = '<option value="">Selecione a categoria primeiro</option>';
        planoSelect.disabled = true;
        turmaSelect.innerHTML = '<option value="">Selecione a turma</option>';
        turmaSelect.disabled = true;
        gradeSelect.innerHTML = '<option value="">Selecione o horário</option>';
        gradeSelect.disabled = true;

        if (!this.value) return;

        const modalidadeSelecionada = this.value;

        const categoriasDisponiveis = [...new Set(
            precos
            .filter(p => p.modalidade && p.modalidade.mod_nome === modalidadeSelecionada)
            .map(p => p.preco_categoria)
            .filter(categoria => categoria)
        )];

        if (categoriasDisponiveis.length) {
            categoriaSelect.disabled = false;
            categoriasDisponiveis.forEach(categoria => {
                categoriaSelect.innerHTML += `<option value="${categoria}">${categoria}</option>`;
            });
        } else {
            categoriaSelect.innerHTML = '<option value="">Nenhuma categoria cadastrada</option>';
        }

        const turmasFiltradas = [...new Set(
            grades
            .filter(g => g.grade_modalidade === modalidadeSelecionada)
            .map(g => g.grade_turma)
        )];

        turmasFiltradas.forEach(turma => {
            let textoExibicao = turma;
            if (turma.toLowerCase() === 'criancas') textoExibicao = 'Crianças';
            if (turma.toLowerCase() === 'adultos') textoExibicao = 'Adultos';
            if (turma.toLowerCase() === 'mulheres') textoExibicao = 'Mulheres';
            turmaSelect.innerHTML += `<option value="${turma}">${textoExibicao}</option>`;
        });

        turmaSelect.disabled = false;
    });

    categoriaSelect.addEventListener('change', function() {
        planoSelect.innerHTML = '<option value="">Selecione o plano</option>';
        planoSelect.disabled = true;

        if (!this.value || !modalidadeSelect.value) return;

        const modalidadeSelecionada = modalidadeSelect.value;
        const categoriaSelecionada = this.value;

        const planosDisponiveis = [...new Set(
            precos
            .filter(p => p.modalidade && p.modalidade.mod_nome === modalidadeSelecionada && p.preco_categoria === categoriaSelecionada)
            .map(p => p.preco_plano)
            .filter(plano => plano)
        )];

        if (planosDisponiveis.length) {
            planoSelect.disabled = false;
            planosDisponiveis.forEach(plano => {
                planoSelect.innerHTML += `<option value="${plano}">${plano}</option>`;
            });
        } else {
            planoSelect.innerHTML = '<option value="">Nenhum plano cadastrado para esta categoria</option>';
        }
    });

    turmaSelect.addEventListener('change', function() {
        gradeSelect.innerHTML = '<option value="">Selecione o horário</option>';

        if (!this.value) {
            gradeSelect.disabled = true;
            return;
        }

        const resultado = grades.filter(g =>
            g.grade_modalidade === modalidadeSelect.value &&
            g.grade_turma === this.value
        );

        resultado.forEach(grade => {
            gradeSelect.innerHTML += `<option value="${grade.id_grade}">Prof. ${grade.professor ? grade.professor.prof_nome : '-'} (${grade.grade_inicio.substring(0, 5)} às ${grade.grade_fim.substring(0, 5)})</option>`;
        });

        gradeSelect.disabled = false;
    });

    document.addEventListener('DOMContentLoaded', calcularPrimeiraParcelaMatricula);
</script>

@endsection