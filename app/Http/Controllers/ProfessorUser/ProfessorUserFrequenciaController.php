<?php

namespace App\Http\Controllers\professorUser;

use App\Http\Controllers\Controller;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\FrequenciaAluno;
use App\Models\GradeHorario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class ProfessorUserFrequenciaController extends Controller
{

    public function listagemGrades()
    {
        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $grades = \App\Models\GradeHorario::with(['professor', 'matriculas'])
            ->where('professor_id_professor', $professor->id_professor)
            ->get();

        return view('view_professor_user.frequencia_aluno.listagem_professor', compact('grades'));
    }

    public function listagemDias(string $gradeId)
    {
        try {
            $gradeId = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user?->professor;

        if (!$professor) {
            abort(403);
        }

        $grade = GradeHorario::with([
            'professor',
            'matriculas' => function ($query) use ($user) {
                $query->with('aluno')
                    ->where('matri_status', 'Matriculado')
                    ->where('id_emp_id', $user->id_emp_id);
            },
        ])
            ->where('id_grade', $gradeId)
            ->where('professor_id_professor', $professor->id_professor)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $grade->matriculas = $grade->matriculas
            ->sortBy(function ($matricula) {
                return $matricula->aluno->aluno_nome ?? '';
            })
            ->values();

        $dias = FrequenciaAluno::with('matricula.aluno')
            ->where('grade_horario_id_grade', $grade->id_grade)
            ->where('id_emp_id', $user->id_emp_id)
            ->orderByDesc('freq_data_aula')
            ->get()
            ->groupBy('freq_data_aula');

        return view('view_professor_user.frequencia_aluno.frequencia_dias_professor', compact('dias', 'grade'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $request->validate([
            'grade_id'  => 'required|exists:grade_horario,id_grade',
            'data_aula' => 'required|date|before_or_equal:today',
            'presenca'  => 'required|array'
        ]);

        // VERIFICA SE A GRADE É DO PROFESSOR
        $grade = GradeHorario::where('id_grade', $request->grade_id)
            ->where('professor_id_professor', $professor->id_professor)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        foreach ($request->presenca as $matriculaId => $status) {

            // EVITA DUPLICAR FREQUÊNCIA
            $jaExiste = FrequenciaAluno::where('grade_horario_id_grade', $grade->id_grade)
                ->where('matricula_id_matricula', $matriculaId)
                ->where('freq_data_aula', $request->data_aula)
                ->where('id_emp_id', $user->id_emp_id)
                ->exists();

            if ($jaExiste) {

                return back()->withErrors([
                    'matricula' => 'Já existe frequência cadastrada para este aluno nesta data.'
                ])->withInput();
            }

            FrequenciaAluno::create([
                'grade_horario_id_grade' => $grade->id_grade,
                'matricula_id_matricula' => $matriculaId,
                'freq_presenca'          => $status,
                'freq_data_aula'         => $request->data_aula,
                'freq_observacao'        => $request->observacao[$matriculaId] ?? null,
                'id_emp_id'              => $user->id_emp_id
            ]);
        }

        return redirect()->route('professor-frequencia.dias', Crypt::encrypt($grade->id_grade))->with('success', 'Frequência cadastrada com sucesso!');
    }
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $frequencia = FrequenciaAluno::with(['matricula.aluno', 'grade'])
            ->where('id_frequencia_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        if ($frequencia->grade->professor_id_professor != $professor->id_professor) {
            abort(403);
        }

        return view('view_professor_user.frequencia_aluno.edit_professor', compact('frequencia'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $frequencia = FrequenciaAluno::with('grade')
            ->where('id_frequencia_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        if ($frequencia->grade->professor_id_professor != $professor->id_professor) {
            abort(403);
        }

        $request->validate([
            'freq_presenca'   => 'required',
            'freq_observacao' => 'nullable|string'
        ]);

        $frequencia->update([
            'freq_presenca'   => $request->freq_presenca,
            'freq_observacao' => $request->freq_observacao
        ]);

        return redirect()
            ->route('professor-frequencia.dias', Crypt::encrypt($frequencia->grade_horario_id_grade))
            ->with('success', 'Frequência atualizada com sucesso!');
    }

    public function alterarData(Request $request)
    {
        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $request->validate([
            'grade_id'   => 'required',
            'data_atual' => 'required|date',
            'nova_data'  => 'required|date|before_or_equal:today'
        ]);

        $grade = GradeHorario::where('id_grade', $request->grade_id)
            ->where('professor_id_professor', $professor->id_professor)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $jaExiste = FrequenciaAluno::where('grade_horario_id_grade', $grade->id_grade)
            ->where('freq_data_aula', $request->nova_data)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'data' => 'Já existe frequência registrada nessa nova data.'
            ]);
        }

        FrequenciaAluno::where('grade_horario_id_grade', $grade->id_grade)
            ->where('freq_data_aula', $request->data_atual)
            ->where('id_emp_id', $user->id_emp_id)
            ->update([
                'freq_data_aula' => $request->nova_data
            ]);

        return redirect()
            ->route('professor-frequencia.dias', Crypt::encrypt($grade->id_grade))
            ->with('success', 'Data atualizada com sucesso!');
    }

    public function relatorio($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $grade = GradeHorario::with([
            'professor',
            'matriculas.aluno.detalhes.graduacao',
            'matriculas.frequencias'
        ])
            ->where('id_grade', $id)
            ->where('professor_id_professor', $professor->id_professor)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $anoSelecionado = request()->get('ano', now()->year);

        $anosDisponiveis = FrequenciaAluno::selectRaw('YEAR(freq_data_aula) as ano')
            ->where('grade_horario_id_grade', $grade->id_grade)
            ->where('id_emp_id', $user->id_emp_id)
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
            ->whereYear('freq_data_aula', $anoSelecionado)
            ->groupBy(
                DB::raw('YEAR(freq_data_aula)'),
                DB::raw('MONTH(freq_data_aula)'),
                'freq_presenca'
            )
            ->orderBy('mes')
            ->get();

        return view(
            'view_professor_user.frequencia_aluno.relatorio_professor',
            compact('grade', 'frequenciaMensal', 'anosDisponiveis', 'anoSelecionado')
        );
    }
}
