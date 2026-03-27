<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
use App\Models\Mensalidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class MensalidadeController extends Controller
{

    public function index(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        // Atualiza atrasados SOMENTE da empresa
        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->where('id_emp_id', $user->id_emp_id)
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
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('view_financeiro.index', compact('aluno', 'mensalidades'));
    }

    public function darBaixa($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $detalhe = DetalhesMensalidade::where('id_detalhes_mensalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $detalhe->update([
            'det_mensa_status' => 'Pago',
            'det_mensa_data_pagamento' => Carbon::now()->format('Y-m-d')
        ]);

        return back()->with('success', 'Parcela baixada com sucesso!');
    }


    public function desfazerBaixa($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $detalhe = DetalhesMensalidade::where('id_detalhes_mensalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $detalhe->update([
            'det_mensa_status' => 'Em aberto',
            'det_mensa_data_pagamento' => null
        ]);

        return back()->with('success', 'Baixa desfeita com sucesso!');
    }

    public function editarForma(Request $request)
    {
        $request->validate([
            'mensalidade_id' => 'required',
            'nova_forma' => 'required'
        ]);
        $user = Auth::user();

        // valida se a mensalidade pertence à empresa
        $mensalidade = Mensalidade::where('id_mensalidade', $request->mensalidade_id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        DetalhesMensalidade::where('mensalidade_id_mensalidade', $mensalidade->id_mensalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->update([
                'det_mensa_forma_pagamento' => $request->nova_forma
            ]);


        return back()->with('success', 'Forma de pagamento atualizada com sucesso!');
    }
}
