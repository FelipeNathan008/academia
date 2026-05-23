<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AlunoUserAlunoController extends Controller
{
    // LISTAR TODOS OS ALUNOS DO RESPONSÁVEL
    public function index()
    {
        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $alunos = Aluno::with(['matriculas', 'detalhes'])
            ->where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->get();

        return view('view_aluno_user.aluno.index', compact(
            'responsavel',
            'alunos'
        ));
    }

    // SHOW DO ALUNO
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $responsavel = $user->responsavel;

        $aluno = Aluno::with([
            'responsavel',
            'matriculas',
            'detalhes'
        ])
            ->where('id_aluno', $id)
            ->where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->firstOrFail();

        return view('view_aluno_user.aluno.show', compact('aluno'));
    }
}