<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\FrequenciaAluno;
use App\Models\GradeHorario;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Aula;

class FrequenciaAlunoController extends Controller
{

    public function relatorioPdf(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'data' => 'required|date',
        ]);

        $data = $request->data;

        $empresa = \App\Models\Empresa::where('id_empresa', $user->id_emp_id)
            ->firstOrFail();

        $frequencias = FrequenciaAluno::with([
            'grade',
            'grade.professor',
            'matricula.aluno',
            'visitanteGrade.visitante'
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->whereDate('freq_data_aula', $data)
            ->where(function ($query) {
                $query->whereNotNull('matricula_id_matricula')
                    ->orWhereNotNull('visitante_grade_id_visitante_grade');
            })
            ->get();

        $grades = $frequencias
            ->groupBy('grade_horario_id_grade')
            ->map(function ($registros) use ($data, $user) {
                $grade = $registros->first()->grade;

                $aulas = Aula::where('id_grade_horario', $grade->id_grade)
                    ->where('id_emp_id', $user->id_emp_id)
                    ->whereDate('aula_inicio', '<=', $data)
                    ->whereDate('aula_fim', '>=', $data)
                    ->orderBy('aula_inicio')
                    ->get();

                $presentesAlunos = $registros
                    ->whereNotNull('matricula_id_matricula')
                    ->where('freq_presenca', 'Presente')
                    ->count();

                $presentesVisitantes = $registros
                    ->whereNotNull('visitante_grade_id_visitante_grade')
                    ->where('freq_presenca', 'Presente')
                    ->count();

                return [
                    'grade' => $grade,
                    'aulas' => $aulas,
                    'registros' => $registros,
                    'presentes_alunos' => $presentesAlunos,
                    'presentes_visitantes' => $presentesVisitantes,
                    'total_presentes' => $presentesAlunos + $presentesVisitantes,
                ];
            })
            ->sortBy(function ($item) {
                $turma = strtolower($item['grade']->grade_turma ?? '');

                if (str_contains($turma, 'crianca') || str_contains($turma, 'kids')) {
                    return 1;
                }

                return 2;
            })
            ->values();

        $totalAlunos = $grades->sum('presentes_alunos');
        $totalVisitantes = $grades->sum('presentes_visitantes');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'view_admin_user.view_principal.view_frequencia_aluno.relatorio_pdf',
            compact(
                'empresa',
                'grades',
                'data',
                'totalAlunos',
                'totalVisitantes'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'relatorio-treino-' . \Carbon\Carbon::parse($data)->format('d-m-Y') . '.pdf'
        );
    }


    public function listagemGrades(Request $request)
    {
        $user = Auth::user();

        $todasGrades = GradeHorario::where('id_emp_id', $user->id_emp_id)
            ->with('professor')
            ->get();

        $query = GradeHorario::with(['matriculas', 'professor'])
            ->where('id_emp_id', $user->id_emp_id);

        if ($request->filled('turma')) {
            $query->where('grade_turma', $request->turma);
        }

        if ($request->filled('modalidade')) {
            $query->where('grade_modalidade', $request->modalidade);
        }

        if ($request->filled('professor')) {
            $query->whereHas('professor', function ($q) use ($request) {
                $q->where('prof_nome', $request->professor);
            });
        }

        $totalGrades = GradeHorario::where('id_emp_id', $user->id_emp_id)->count();

        $grades = $query
            ->orderBy('grade_turma')
            ->paginate(10)
            ->withQueryString();

        return view('view_admin_user.view_principal.view_frequencia_aluno.listagem', [
            'grades' => $grades,
            'totalGrades' => $totalGrades,
            'turmas' => $todasGrades->pluck('grade_turma')->filter()->unique()->values(),
            'modalidades' => $todasGrades->pluck('grade_modalidade')->filter()->unique()->values(),
            'professores' => $todasGrades->pluck('professor.prof_nome')->filter()->unique()->values(),
        ]);
    }

    public function listagemDias(Request $request, $gradeId)
    {
        try {
            $gradeId = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $filtroData = $request->get('data');

        $grade = GradeHorario::with([
            'matriculas' => function ($query) use ($user) {
                $query->where('matri_status', 'Matriculado')
                    ->where('id_emp_id', $user->id_emp_id);
            },
            'matriculas.aluno',
            'visitanteGrades' => function ($query) use ($user) {
                $query->where('id_emp_id', $user->id_emp_id)
                    ->whereHas('visitante', function ($q) {
                        $q->where('vis_status', 'Ativo');
                    });
            },
            'visitanteGrades.visitante'
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->findOrFail($gradeId);

        $grade->setRelation(
            'matriculas',
            $grade->matriculas->sortBy(fn($matricula) => $matricula->aluno->aluno_nome ?? '')
        );

        $grade->setRelation(
            'visitanteGrades',
            $grade->visitanteGrades->sortBy(fn($visitanteGrade) => $visitanteGrade->visitante->vis_nome ?? '')
        );

        $datasPaginadas = FrequenciaAluno::where('grade_horario_id_grade', $gradeId)
            ->where('id_emp_id', $user->id_emp_id)
            ->when($filtroData, function ($query) use ($filtroData) {
                $query->whereDate('freq_data_aula', $filtroData);
            })
            ->select('freq_data_aula')
            ->groupBy('freq_data_aula')
            ->orderByDesc('freq_data_aula')
            ->paginate(10)
            ->withQueryString();

        $datasDaPagina = $datasPaginadas->getCollection()->pluck('freq_data_aula');

        $dias = FrequenciaAluno::with([
            'matricula.aluno',
            'visitanteGrade.visitante'
        ])
            ->where('grade_horario_id_grade', $gradeId)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereIn('freq_data_aula', $datasDaPagina)
            ->orderByDesc('freq_data_aula')
            ->get()
            ->groupBy('freq_data_aula');

        return view(
            'view_admin_user.view_principal.view_frequencia_aluno.dias',
            compact(
                'dias',
                'grade',
                'datasPaginadas',
                'filtroData'
            )
        );
    }

    public function visualizar(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $tipo = $request->get('tipo', 'aluno');

        if (!in_array($tipo, ['aluno', 'visitante'])) {
            $tipo = 'aluno';
        }

        $grade = GradeHorario::with('professor')
            ->where('id_emp_id', $user->id_emp_id)
            ->findOrFail($id);

        $anoSelecionado = $request->get('ano', now()->year);

        if ($tipo === 'visitante') {
            $anosDisponiveis = FrequenciaAluno::where('grade_horario_id_grade', $grade->id_grade)
                ->where('id_emp_id', $user->id_emp_id)
                ->whereNotNull('visitante_grade_id_visitante_grade')
                ->selectRaw('YEAR(freq_data_aula) as ano')
                ->distinct()
                ->orderByDesc('ano')
                ->pluck('ano');

            $frequenciaMensal = FrequenciaAluno::selectRaw("
                YEAR(freq_data_aula) as ano,
                MONTH(freq_data_aula) as mes,
                freq_presenca,
                COUNT(*) as total
            ")
                ->where('grade_horario_id_grade', $grade->id_grade)
                ->where('id_emp_id', $user->id_emp_id)
                ->whereNotNull('visitante_grade_id_visitante_grade')
                ->whereYear('freq_data_aula', $anoSelecionado)
                ->groupBy(
                    'ano',
                    'mes',
                    'freq_presenca'
                )
                ->orderBy('mes')
                ->get();

            $visitantes = FrequenciaAluno::with([
                'visitanteGrade.visitante'
            ])
                ->where('grade_horario_id_grade', $grade->id_grade)
                ->where('id_emp_id', $user->id_emp_id)
                ->whereNotNull('visitante_grade_id_visitante_grade')
                ->get()
                ->groupBy('visitante_grade_id_visitante_grade')
                ->map(function ($frequencias) {
                    $primeiro = $frequencias->first();
                    $visitante = $primeiro->visitanteGrade->visitante ?? null;

                    return [
                        'id' => $primeiro->visitante_grade_id_visitante_grade,
                        'visitante_id' => $visitante?->id_visitante,
                        'nome' => $visitante->vis_nome ?? '-',
                        'frequencias' => $frequencias
                    ];
                })
                ->sortBy('nome')
                ->values();

            return view(
                'view_admin_user.view_principal.view_frequencia_aluno.visualizar',
                compact(
                    'grade',
                    'frequenciaMensal',
                    'anosDisponiveis',
                    'anoSelecionado',
                    'tipo',
                    'visitantes'
                )
            );
        }

        $grade->load([
            'matriculas.aluno.detalhes.graduacao',
            'matriculas.frequencias'
        ]);

        $anosDisponiveis = FrequenciaAluno::where('grade_horario_id_grade', $grade->id_grade)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotNull('matricula_id_matricula')
            ->selectRaw('YEAR(freq_data_aula) as ano')
            ->distinct()
            ->orderByDesc('ano')
            ->pluck('ano');

        $frequenciaMensal = FrequenciaAluno::selectRaw("
            YEAR(freq_data_aula) as ano,
            MONTH(freq_data_aula) as mes,
            freq_presenca,
            COUNT(*) as total
        ")
            ->where('grade_horario_id_grade', $grade->id_grade)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotNull('matricula_id_matricula')
            ->whereYear('freq_data_aula', $anoSelecionado)
            ->groupBy(
                'ano',
                'mes',
                'freq_presenca'
            )
            ->orderBy('mes')
            ->get();

        $visitantes = collect();

        return view(
            'view_admin_user.view_principal.view_frequencia_aluno.visualizar',
            compact(
                'grade',
                'frequenciaMensal',
                'anosDisponiveis',
                'anoSelecionado',
                'tipo',
                'visitantes'
            )
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'grade_id' => 'required|exists:grade_horario,id_grade',
            'data_aula' => 'required|date',
            'presenca_aluno' => 'nullable|array',
            'presenca_visitante' => 'nullable|array',
        ]);

        $grade = GradeHorario::where('id_grade', $request->grade_id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        foreach ($request->presenca_aluno ?? [] as $matriculaId => $status) {
            $jaExiste = FrequenciaAluno::where('grade_horario_id_grade', $grade->id_grade)
                ->where('matricula_id_matricula', $matriculaId)
                ->where('freq_data_aula', $request->data_aula)
                ->where('id_emp_id', $user->id_emp_id)
                ->exists();

            if ($jaExiste) {
                return back()
                    ->withErrors([
                        'matricula' => 'Já existe registro de frequência para um aluno nesta data.'
                    ])
                    ->withInput();
            }

            FrequenciaAluno::create([
                'grade_horario_id_grade' => $grade->id_grade,
                'matricula_id_matricula' => $matriculaId,
                'visitante_grade_id_visitante_grade' => null,
                'freq_presenca' => $status,
                'freq_data_aula' => $request->data_aula,
                'freq_observacao' => $request->observacao_aluno[$matriculaId] ?? null,
                'id_emp_id' => $user->id_emp_id
            ]);
        }

        foreach ($request->presenca_visitante ?? [] as $visitanteGradeId => $status) {
            $visitanteGrade = \App\Models\VisitanteGrade::with('visitante')
                ->where('id_visitante_grade', $visitanteGradeId)
                ->where('grade_id_grade', $grade->id_grade)
                ->where('id_emp_id', $user->id_emp_id)
                ->whereHas('visitante', function ($query) {
                    $query->where('vis_status', 'Ativo');
                })
                ->firstOrFail();

            $jaExiste = FrequenciaAluno::where('grade_horario_id_grade', $grade->id_grade)
                ->where('visitante_grade_id_visitante_grade', $visitanteGrade->id_visitante_grade)
                ->where('freq_data_aula', $request->data_aula)
                ->where('id_emp_id', $user->id_emp_id)
                ->exists();

            if ($jaExiste) {
                return back()
                    ->withErrors([
                        'visitante' => 'Já existe registro de frequência para um visitante nesta data.'
                    ])
                    ->withInput();
            }

            FrequenciaAluno::create([
                'grade_horario_id_grade' => $grade->id_grade,
                'matricula_id_matricula' => null,
                'visitante_grade_id_visitante_grade' => $visitanteGrade->id_visitante_grade,
                'freq_presenca' => $status,
                'freq_data_aula' => $request->data_aula,
                'freq_observacao' => $request->observacao_visitante[$visitanteGradeId] ?? null,
                'id_emp_id' => $user->id_emp_id
            ]);
        }

        return redirect()
            ->route('frequencia.dias', Crypt::encrypt($grade->id_grade))
            ->with('success', 'Frequência salva com sucesso!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $frequencia = FrequenciaAluno::with([
            'matricula.aluno',
            'visitanteGrade.visitante'
        ])
            ->where('id_frequencia_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view(
            'view_admin_user.view_principal.view_frequencia_aluno.edit',
            compact('frequencia')
        );
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $frequencia = FrequenciaAluno::where('id_frequencia_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'freq_presenca' => 'required',
            'freq_observacao' => 'nullable|string'
        ]);

        $frequencia->update([
            'freq_presenca' => $request->freq_presenca,
            'freq_observacao' => $request->freq_observacao
        ]);

        return redirect()
            ->route(
                'frequencia.dias',
                Crypt::encrypt($frequencia->grade_horario_id_grade)
            )
            ->with('success', 'Frequência atualizada com sucesso!');
    }

    public function alterarData(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'grade_id' => 'required',
            'data_atual' => 'required|date',
            'nova_data' => 'required|date'
        ]);

        $jaExiste = FrequenciaAluno::where('grade_horario_id_grade', $request->grade_id)
            ->where('freq_data_aula', $request->nova_data)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'data' => 'Já existe frequência registrada nessa nova data.'
            ]);
        }

        FrequenciaAluno::where('grade_horario_id_grade', $request->grade_id)
            ->where('freq_data_aula', $request->data_atual)
            ->where('id_emp_id', $user->id_emp_id)
            ->update([
                'freq_data_aula' => $request->nova_data
            ]);

        return redirect()
            ->route('frequencia.dias', Crypt::encrypt($request->grade_id))
            ->with('success', 'Data atualizada com sucesso!');
    }
}
