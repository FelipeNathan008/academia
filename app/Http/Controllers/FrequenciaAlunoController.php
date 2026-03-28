<?php

namespace App\Http\Controllers;

use App\Models\FrequenciaAluno;
use App\Models\GradeHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;


class FrequenciaAlunoController extends Controller
{

    public function listagemGrades()
    {
        $user = Auth::user();
        $grades = GradeHorario::with('matriculas')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();
        return view('view_frequencia_aluno.listagem', compact('grades'));
    }

    public function listagemDias($gradeId)
    {
        try {
            $gradeId = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $grade = GradeHorario::with([
            'matriculas' => function ($query) use ($user) {
                $query->where('matri_status', 'Matriculado')
                    ->where('id_emp_id', $user->id_emp_id);
            },
            'matriculas.aluno'
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->findOrFail($gradeId);

        $grade->matriculas = $grade->matriculas->sortBy(function ($matricula) {
            return $matricula->aluno->aluno_nome ?? '';
        });

        $dias = FrequenciaAluno::with('matricula.aluno')
            ->where('grade_horario_id_grade', $gradeId)
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('freq_data_aula', 'desc')
            ->get()
            ->groupBy('freq_data_aula');

        return view('view_frequencia_aluno.dias', compact('dias', 'grade'));
    }

    public function visualizar($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $grade = GradeHorario::with([
            'matriculas.aluno.detalhes',
            'matriculas.frequencias'
        ])->where('id_emp_id', $user->id_emp_id)
            ->findOrFail($id);

        return view('view_frequencia_aluno.visualizar', compact('grade'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'grade_id'  => 'required|exists:grade_horario,id_grade',
            'data_aula' => 'required|date',
            'presenca'  => 'required|array'
        ]);

        foreach ($request->presenca as $matriculaId => $status) {

            $jaExiste = FrequenciaAluno::where('grade_horario_id_grade', $request->grade_id)
                ->where('matricula_id_matricula', $matriculaId)
                ->where('freq_data_aula', $request->data_aula)
                ->where('id_emp_id', $user->id_emp_id)
                ->exists();

            if ($jaExiste) {
                return back()->withErrors([
                    'matricula' => 'Erro, já existe registro de frequência para este aluno nesta data.'
                ])->withInput();
            }

            FrequenciaAluno::create([
                'grade_horario_id_grade' => $request->grade_id,
                'matricula_id_matricula' => $matriculaId,
                'freq_presenca'           => $status,
                'freq_data_aula'          => $request->data_aula,
                'freq_observacao'         => $request->observacao[$matriculaId] ?? null,
                'id_emp_id'               => $user->id_emp_id
            ]);
        }

        return redirect()->route('frequencia.dias', Crypt::encrypt($request->grade_id))
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
        $frequencia = FrequenciaAluno::where('id_frequencia_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_frequencia_aluno.edit', compact('frequencia'));
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
            'freq_presenca'   => 'required',
            'freq_observacao' => 'nullable|string'
        ]);

        $frequencia->update([
            'freq_presenca'   => $request->freq_presenca,
            'freq_observacao' => $request->freq_observacao
        ]);

        return redirect()
            ->route('frequencia.dias', Crypt::encrypt($frequencia->grade_horario_id_grade))
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

        // EVITA DUPLICAR DATA
        $jaExiste = FrequenciaAluno::where('grade_horario_id_grade', $request->grade_id)
            ->where('freq_data_aula', $request->nova_data)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'data' => 'Já existe frequência registrada nessa nova data.'
            ]);
        }

        // ATUALIZA TODOS OS REGISTROS DO DIA
        FrequenciaAluno::where('grade_horario_id_grade', $request->grade_id)
            ->where('freq_data_aula', $request->data_atual)
            ->where('id_emp_id', $user->id_emp_id)
            ->update([
                'freq_data_aula' => $request->nova_data
            ]);


        return redirect()->route('frequencia.dias', Crypt::encrypt($request->grade_id))
            ->with('success', 'Data atualizada com sucesso!');
    }
}
