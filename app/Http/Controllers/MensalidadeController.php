<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
use App\Models\Mensalidade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MensalidadeController extends Controller
{
    public function index(Request $request, $id_aluno)
    {
        $aluno = Aluno::findOrFail($id_aluno);

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
            ->orderBy('created_at', 'desc')
            ->get();

        return view('view_financeiro.index', compact('aluno', 'mensalidades'));
    }
    public function darBaixa($id)
    {
        $detalhe = DetalhesMensalidade::findOrFail($id);

        $detalhe->update([
            'det_mensa_status' => 'Pago',
            'det_mensa_data_pagamento' => Carbon::now()->format('Y-m-d')
        ]);

        return back()->with('success', 'Parcela baixada com sucesso!');
    }


    public function desfazerBaixa($id)
    {
        $detalhe = DetalhesMensalidade::findOrFail($id);

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

        DetalhesMensalidade::where(
            'mensalidade_id_mensalidade',
            $request->mensalidade_id
        )
            ->update([
                'det_mensa_forma_pagamento' => $request->nova_forma
            ]);

        return back()->with('success', 'Forma de pagamento atualizada com sucesso!');
    }


    public function store(Request $request)
    {
        $request->validate([
            'aluno_id_aluno' => 'required|exists:aluno,id_aluno',
            'mensa_dia_venc' => 'required|string|max:2',
            'mensa_valor'    => 'required|numeric|min:0',
        ]);

        $mensalidade = Mensalidade::create([
            'aluno_id_aluno' => $request->aluno_id_aluno,
            'mensa_dia_venc' => $request->mensa_dia_venc,
            'mensa_valor'    => $request->mensa_valor,
        ]);

        return response()->json([
            'message' => 'Mensalidade criada com sucesso',
            'data' => $mensalidade
        ], 201);
    }

    public function show($id)
    {
        $mensalidade = Mensalidade::with('aluno')->findOrFail($id);

        return response()->json([
            'data' => $mensalidade
        ]);
    }

    public function update(Request $request, $id)
    {
        $mensalidade = Mensalidade::findOrFail($id);

        $request->validate([
            'aluno_id_aluno' => 'sometimes|exists:aluno,id_aluno',
            'mensa_dia_venc' => 'sometimes|string|max:2',
            'mensa_valor'    => 'sometimes|numeric|min:0',
        ]);

        $mensalidade->update($request->only([
            'aluno_id_aluno',
            'mensa_dia_venc',
            'mensa_valor'
        ]));

        return response()->json([
            'message' => 'Mensalidade atualizada com sucesso',
            'data' => $mensalidade
        ]);
    }

    public function destroy($id)
    {
        $mensalidade = Mensalidade::findOrFail($id);
        $mensalidade->delete();

        return response()->json([
            'message' => 'Mensalidade removida com sucesso'
        ]);
    }
}
