<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\GradeHorario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AlunoUserAulaController extends Controller
{
    // LISTAR AULAS DE UMA GRADE (TURMA) DO ALUNO
    public function index($gradeId)
    {
        try {
            $id = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        // Garante que a grade pertence a algum aluno do responsável logado
        $grade = GradeHorario::with('professor')
            ->where('id_grade', $id)
            ->whereHas('matriculas.aluno', function ($query) use ($responsavel) {
                $query->where('responsavel_id_responsavel', $responsavel->id_responsavel);
            })
            ->firstOrFail();

        $aulas = Aula::where('id_grade_horario', $id)
            ->where('aula_status', 'ativo')
            ->orderBy('aula_inicio')
            ->get();

        return view('view_aluno_user.aula.index', compact('grade', 'aulas'));
    }

    // SHOW DA AULA
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $aula = Aula::with('gradeHorario.professor')
            ->where('id_aula', $id)
            ->whereHas('gradeHorario.matriculas.aluno', function ($query) use ($responsavel) {
                $query->where('responsavel_id_responsavel', $responsavel->id_responsavel);
            })
            ->firstOrFail();

        return view('view_aluno_user.aula.show', compact('aula'));
    }
}