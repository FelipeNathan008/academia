<?php

namespace App\Http\Controllers\professorUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfessorUserMatriculaController extends Controller
{

    public function index(string $id)
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

        // ALUNO (somente se for do professor)
        $aluno = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->with(['responsavel', 'matriculas.grade.professor'])
            ->findOrFail($id);

        // MATRÍCULAS DO ALUNO (SÓ DO PROFESSOR)
        $matriculas = $aluno->matriculas()
            ->whereHas('grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->with('grade.professor')
            ->get();

        // GRADES DO PROFESSOR (para cadastro)
        $grades = \App\Models\GradeHorario::where('professor_id_professor', $professor->id_professor)
            ->with('professor')
            ->get();

        return view('view_professor_user.matricula.index', compact(
            'aluno',
            'matriculas',
            'grades'
        ));
    }
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $matricula = \App\Models\Matricula::where('id_matricula', $id)
            ->whereHas('grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->with(['aluno', 'grade.professor'])
            ->firstOrFail();

        return view('view_professor_user.matricula.show', compact('matricula'));
    }
}
