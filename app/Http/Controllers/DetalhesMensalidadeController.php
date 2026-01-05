<?php

namespace App\Http\Controllers;

use App\Models\DetalhesMensalidade;
use Illuminate\Http\Request;

class DetalhesMensalidadeController extends Controller
{
    public function index()
    {
        $detalhes = DetalhesMensalidade::with('mensalidade')->get();

        return response()->json([
            'data' => $detalhes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mensalidade_id_mensalidade'   => 'required|exists:mensalidade,id_mensalidade',
            'det_mensa_forma_pagamento'    => 'required|string|max:60',
            'det_mensa_per_vig_pago'       => 'required|string|max:60',
        ]);

        $detalhe = DetalhesMensalidade::create([
            'mensalidade_id_mensalidade' => $request->mensalidade_id_mensalidade,
            'det_mensa_forma_pagamento'  => $request->det_mensa_forma_pagamento,
            'det_mensa_per_vig_pago'     => $request->det_mensa_per_vig_pago,
        ]);

        return response()->json([
            'message' => 'Detalhes da mensalidade criados com sucesso',
            'data' => $detalhe
        ], 201);
    }

    public function show($id)
    {
        $detalhe = DetalhesMensalidade::with('mensalidade')->findOrFail($id);

        return response()->json([
            'data' => $detalhe
        ]);
    }

    public function update(Request $request, $id)
    {
        $detalhe = DetalhesMensalidade::findOrFail($id);

        $request->validate([
            'mensalidade_id_mensalidade' => 'sometimes|exists:mensalidade,id_mensalidade',
            'det_mensa_forma_pagamento'  => 'sometimes|string|max:60',
            'det_mensa_per_vig_pago'     => 'sometimes|string|max:60',
        ]);

        $detalhe->update($request->all());

        return response()->json([
            'message' => 'Detalhes da mensalidade atualizados com sucesso',
            'data' => $detalhe
        ]);
    }

    public function destroy($id)
    {
        $detalhe = DetalhesMensalidade::findOrFail($id);
        $detalhe->delete();

        return response()->json([
            'message' => 'Detalhes da mensalidade removidos com sucesso'
        ], 204);
    }
}
