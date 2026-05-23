<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AlunoUserMatriculaController extends Controller
{
    // LISTAGEM DAS MATRÍCULAS DO ALUNO
    public function index($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $responsavel = Auth::user()->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $aluno = Aluno::with([
            'responsavel',
            'matriculas.grade.professor'
        ])
            ->where('id_aluno', $id)
            ->where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->firstOrFail();

        $matriculas = Matricula::with([
            'grade.professor',
            'aluno.responsavel'
        ])
            ->where('aluno_id_aluno', $aluno->id_aluno)
            ->get();

        return view('view_aluno_user.matricula.index', compact(
            'aluno',
            'matriculas'
        ));
    }

    // VER MATRÍCULA
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $responsavel = Auth::user()->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $matricula = Matricula::with([
            'aluno.responsavel',
            'grade.professor'
        ])
            ->whereHas('aluno', function ($query) use ($responsavel) {
                $query->where(
                    'responsavel_id_responsavel',
                    $responsavel->id_responsavel
                );
            })
            ->where('id_matricula', $id)
            ->firstOrFail();

        return view('view_aluno_user.matricula.show', compact(
            'matricula'
        ));
    }
}
