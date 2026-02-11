<?php

namespace App\Http\Controllers;

use App\Models\GradeHorario;
use App\Models\HorarioTreino;
use App\Models\Modalidade;
use App\Models\Professor;
use Illuminate\Http\Request;

class GradeHorarioController extends Controller
{
    public function index()
    {
        $grades = GradeHorario::with(['professor', 'horarioTreino'])->get();
        $professores = Professor::all();

        $horariosTreino = HorarioTreino::whereDoesntHave('gradeHorario')->get();

        return view('view_grade_horarios.index', compact(
            'grades',
            'professores',
            'horariosTreino'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'professor_id_professor' => 'required|exists:professor,id_professor',
            'horario_treino_id_hora' => 'required|exists:horario_treino,id_hora',
            'grade_modalidade'       => 'required|string|max:80',
            'grade_dia_semana'       => 'required|string|max:80',
            'grade_inicio'           => 'required|date_format:H:i:s',
            'grade_fim'              => 'required|date_format:H:i:s',
            'grade_turma'            => 'required|string|max:60',
            'grade_desc'             => 'required|string',
        ]);


        $horario = HorarioTreino::findOrFail($request->horario_treino_id_hora);

        GradeHorario::create([
            'professor_id_professor' => $request->professor_id_professor,
            'horario_treino_id_hora' => $request->horario_treino_id_hora,
            'grade_modalidade'       => $request->grade_modalidade,
            'grade_dia_semana'       => $request->grade_dia_semana,
            'grade_inicio'           => $request->grade_inicio,
            'grade_fim'              => $request->grade_fim,
            'grade_turma'            => $request->grade_turma,
            'grade_desc'             => $request->grade_desc,
        ]);

        return redirect()
            ->route('grade_horarios')
            ->with('success', 'Grade de horário cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $grade = GradeHorario::findOrFail($id);
        $professores = Professor::all();

        $horariosTreino = HorarioTreino::whereDoesntHave('gradeHorario')
            ->orWhere('id_hora', $grade->horario_treino_id_hora)
            ->get();

        return view('view_grade_horarios.edit', compact(
            'grade',
            'professores',
            'horariosTreino'
        ));
    }

    public function update(Request $request, $id)
    {
        $grade = GradeHorario::findOrFail($id);

        $request->validate([
            'professor_id_professor' => 'required|exists:professor,id_professor',
            'horario_treino_id_hora' => 'required|exists:horario_treino,id_hora',
            'grade_modalidade'       => 'required|string|max:80',
            'grade_dia_semana'       => 'required|string|max:80',
            'grade_inicio'           => 'required|date_format:H:i:s',
            'grade_fim'              => 'required|date_format:H:i:s',
            'grade_turma'            => 'required|string|max:60',
            'grade_desc'             => 'required|string',
        ]);

        $grade->update($request->all());

        return redirect()
            ->route('grade_horarios')
            ->with('success', 'Grade de horário atualizada com sucesso!');
    }

    public function destroy($id)
    {
        GradeHorario::findOrFail($id)->delete();

        return redirect()
            ->route('grade_horarios')
            ->with('success', 'Grade de horário excluída com sucesso!');
    }
}
