<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\Modalidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AlunoUserDetalhesAlunoController extends Controller
{
    public function index($id)
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

        $aluno = Aluno::where('id_aluno', $id)
            ->where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->firstOrFail();

        $graduacoes = DetalhesAluno::with([
            'graduacao',
            'graduacao.modalidade'
        ])

            ->where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->get()
            ->sortByDesc('graduacao.gradu_ordem');

        $graduacoesTotais = Graduacao::with('modalidade')
            ->ordem()
            ->where('id_emp_id', $user->id_emp_id)
            ->get();
            
        $modalidades = Modalidade::all();

        return view('view_aluno_user.aluno.detalhes_aluno', compact(
            'aluno',
            'graduacoes',
            'graduacoesTotais',
            'modalidades',
            'responsavel'
        ));
    }

    public function showCertificado($path)
    {
        try {
            $filePath = Crypt::decrypt($path);
        } catch (DecryptException $e) {
            abort(404);
        }

        if (!file_exists(public_path($filePath))) {
            abort(404);
        }

        return response()->file(public_path($filePath));
    }
}
