<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Mensalidade;

class FinanceiroController extends Controller
{
    public function index($id)
    {
        $matricula = Matricula::with('aluno')->findOrFail($id);

        $mensalidade = Mensalidade::with('detalhes')
            ->where('aluno_id_aluno', $matricula->aluno_id_aluno)
            ->first();

        return view('view_financeiro.index', compact(
            'matricula',
            'mensalidade'
        ));
    }
}
