<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
use App\Models\Mensalidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AlunoUserFinanceiroController extends Controller
{
    public function index(Request $request, $id)
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

        $aluno = Aluno::with('responsavel')
            ->where('id_aluno', $id)
            ->where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->firstOrFail();

        // Atualiza mensalidades atrasadas
        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $query = Mensalidade::with([
            'matricula.grade.professor',
            'detalhes'
        ]);

        if ($request->has('matricula')) {
            $query->where('matricula_id_matricula', $request->matricula);
        }

        $mensalidades = $query
            ->where('aluno_id_aluno', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'view_aluno_user.financeiro.index',
            compact('aluno', 'mensalidades')
        );
    }
}