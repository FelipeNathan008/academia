<?php

namespace App\Http\Controllers;

use App\Models\Mensalidade;
use Illuminate\Http\Request;

class MensalidadeController extends Controller
{
    public function index()
    {
        $mensalidades = Mensalidade::with('aluno')->get();

        return response()->json([
            'data' => $mensalidades
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_id_aluno'        => 'required|exists:aluno,id_aluno',
            'mensa_periodo_vigente' => 'required|string|max:60',
            'mensa_data_venc'       => 'required|date',
            'mensa_valor'           => 'required|numeric|min:0',
            'mensa_status'          => 'required|string|max:60',
        ]);

        $mensalidade = Mensalidade::create([
            'aluno_id_aluno'        => $request->aluno_id_aluno,
            'mensa_periodo_vigente' => $request->mensa_periodo_vigente,
            'mensa_data_venc'       => $request->mensa_data_venc,
            'mensa_valor'           => $request->mensa_valor,
            'mensa_status'          => $request->mensa_status,
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
            'aluno_id_aluno'        => 'sometimes|exists:aluno,id_aluno',
            'mensa_periodo_vigente' => 'sometimes|string|max:60',
            'mensa_data_venc'       => 'sometimes|date',
            'mensa_valor'           => 'sometimes|numeric|min:0',
            'mensa_status'          => 'sometimes|string|max:60',
        ]);

        $mensalidade->update($request->all());

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
        ], 204);
    }
}
