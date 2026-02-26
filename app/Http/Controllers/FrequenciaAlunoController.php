<?php

namespace App\Http\Controllers;

use App\Models\FrequenciaAluno;
use App\Models\GradeHorario;
use Illuminate\Http\Request;

class FrequenciaAlunoController extends Controller
{

    public function listagemGrades()
    {
        $grades = GradeHorario::with('matriculas')->get();

        return view('view_frequencia_aluno.listagem', compact('grades'));
    }

    public function listagemDias($gradeId)
    {
        $grade = GradeHorario::with(['matriculas' => function ($query) {
            $query->where('matri_status', 'Matriculado');
        }, 'matriculas.aluno'])
            ->findOrFail($gradeId);

        $dias = FrequenciaAluno::with('matricula.aluno')
            ->where('grade_horario_id_grade', $gradeId)
            ->orderBy('freq_data_aula', 'desc')
            ->get()
            ->groupBy('freq_data_aula');

        return view('view_frequencia_aluno.dias', compact('dias', 'grade'));
    }

    public function visualizar($id)
    {
        $grade = GradeHorario::with([
            'matriculas.aluno.detalhes',
            'matriculas.frequencias'
        ])->findOrFail($id);

        return view('view_frequencia_aluno.visualizar', compact('grade'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grade_horario,id_grade',
            'data_aula' => 'required|date',
            'presenca' => 'required|array'
        ]);

        foreach ($request->presenca as $matriculaId => $status) {

            FrequenciaAluno::create([
                'grade_horario_id_grade' => $request->grade_id,
                'matricula_id_matricula' => $matriculaId,
                'freq_presenca' => $status,
                'freq_data_aula' => $request->data_aula,
                'freq_observacao' => $request->observacao[$matriculaId] ?? null
            ]);
        }

        return redirect()->route('frequencia.dias', $request->grade_id)
            ->with('success', 'Frequência salva com sucesso!');
    }

    public function edit($id)
    {
        $frequencia = FrequenciaAluno::with('matricula.aluno')
            ->findOrFail($id);

        return view('view_frequencia_aluno.edit', compact('frequencia'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'freq_presenca' => 'required',
            'freq_observacao' => 'nullable|string'
        ]);

        $frequencia = FrequenciaAluno::findOrFail($id);

        $frequencia->update([
            'freq_presenca' => $request->freq_presenca,
            'freq_observacao' => $request->freq_observacao
        ]);

        return redirect()
            ->route('frequencia.dias', $frequencia->grade_horario_id_grade)
            ->with('success', 'Frequência atualizada!');
    }
}
