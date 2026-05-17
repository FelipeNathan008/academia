<?php

namespace App\Http\Controllers\ProfessorUser;

use App\Http\Controllers\Controller;

use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ProfessorUserProfessorController extends Controller
{
    public function index()
    {
        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $professor->qtd_aluno = DB::table('matricula as m')
            ->join('grade_horario as g', 'm.grade_id_grade', '=', 'g.id_grade')
            ->where('g.professor_id_professor', $professor->id_professor)
            ->where('m.matri_status', 'Matriculado')
            ->count();
        
           
        return view('view_professor_user.professor.index', compact('professor'));
    }

    public function show(string $id)
    {
        $id = decrypt($id);

        $professor = Auth::user()->professor;

        if (!$professor || $professor->id_professor != $id) {
            abort(403);
        }

        return view('view_professor_user.professor.show', compact('professor'));
    }
}
