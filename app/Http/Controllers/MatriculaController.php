<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\GradeHorario;
use App\Models\Matricula;
use App\Models\Professor;
use App\Models\Responsavel;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index($id)
    {
        $aluno = Aluno::findOrFail($id);

        $matriculas = Matricula::with(['professor', 'grade'])
            ->where('aluno_id_aluno', $id)
            ->get();

        $professores = Professor::whereIn(
            'id_professor',
            GradeHorario::pluck('professor_id_professor')
        )
            ->orderBy('prof_nome')
            ->get();

        return view('view_matricula.index', compact('aluno', 'matriculas', 'professores'));
    }

    public function indexSidebar()
    {
        $alunos = Aluno::with('responsavel')
            ->orderBy('aluno_nome')
            ->get();

        return view('view_matricula.index_sidebar', compact('alunos'));
    }


    public function getTurmasPorProfessor($professorId)
    {
        $turmas = GradeHorario::where('professor_id_professor', $professorId)
            ->orderBy('grade_turma')
            ->get([
                'id_grade',
                'grade_turma',
                'grade_dia_semana',
                'grade_inicio',
                'grade_fim',
                'grade_modalidade'
            ]);

        return response()->json($turmas);
    }


    public function store(Request $request, $alunoId)
    {
        $request->validate([
            'matri_data'     => 'required|date',
            'matri_desc'     => 'nullable|string|max:150',
            'matri_plano'    => 'required|string|max:40',
            'professor_id'   => 'required|exists:professor,id_professor',
            'matri_turma'    => 'required|exists:grade_horario,id_grade',
        ]);

        Matricula::create([
            'aluno_id_aluno'  => $alunoId,
            'matri_status'    => 'Ativa',
            'matri_data'      => $request->matri_data,
            'matri_plano'     => $request->matri_plano,
            'matri_professor' => $request->professor_id,
            'matri_turma'     => $request->matri_turma,
            'matri_desc'      => $request->matri_desc,
        ]);

        return redirect()
            ->route('matricula', $alunoId)
            ->with('success', 'Matrícula realizada com sucesso!');
    }


    public function show($id)
    {
        $matricula = Matricula::with(['aluno', 'professor', 'grade'])
            ->findOrFail($id);

        return view('view_matricula.show', compact('matricula'));
    }



    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $request->validate([
            'aluno_id_aluno' => 'sometimes|exists:aluno,id_aluno',
            'matri_desc' => 'sometimes|string',
        ]);

        $matricula->update($request->all());

        return response()->json([
            'message' => 'Matrícula atualizada com sucesso',
            'data' => $matricula
        ]);
    }

    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        $alunoId = $matricula->aluno_id_aluno;

        $matricula->update([
            'matri_status' => 'Encerrada'
        ]);

        return redirect()
            ->route('matricula', $alunoId)
            ->with('success', 'Matrícula encerrada com sucesso!');
    }
}
