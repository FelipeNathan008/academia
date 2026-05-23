<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AlunoUserResponsavelController extends Controller
{
    public function index()
    {
        $responsavel = Auth::user()->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $responsavel->qtd_alunos = $responsavel->alunos()->count();

        return view('view_aluno_user.responsavel.index', compact('responsavel'));
    }
}