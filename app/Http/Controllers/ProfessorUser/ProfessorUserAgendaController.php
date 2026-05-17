<?php

namespace App\Http\Controllers\ProfessorUser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfessorUserAgendaController extends Controller
{
    public function agenda()
    {
        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $grades = \App\Models\GradeHorario::with('professor')
            ->where('professor_id_professor', $professor->id_professor)
            ->get();

        $modalidades = $grades->pluck('grade_modalidade')->unique();

        return view('view_professor_user.agenda.agenda_professor', compact('grades', 'modalidades'));
    }

    public function showAgenda(string $id)
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

        $grade = \App\Models\GradeHorario::with([
            'professor',
            'matriculas.aluno'
        ])
            ->where('id_grade', $id)
            ->where('professor_id_professor', $professor->id_professor)
            ->firstOrFail();

        return view('view_professor_user.agenda.show_agenda_professor', compact('grade'));
    }
}
