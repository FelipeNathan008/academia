<?php

namespace App\Http\Controllers;

use App\Models\ValorAula;
use Illuminate\Http\Request;

class ValorAulaController extends Controller
{
    public function index()
    {
        $valores = ValorAula::with('modalidade')->get();

        return response()->json([
            'data' => $valores
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'modalidade_id' => 'required|exists:modalidade,id_modalidade',
            'valor_aula'    => 'required|numeric|min:0',
        ]);

        $valor = ValorAula::create([
            'modalidade_id' => $request->modalidade_id,
            'valor_aula'    => $request->valor_aula,
        ]);

        return response()->json([
            'message' => 'Valor da aula cadastrado com sucesso',
            'data' => $valor
        ], 201);
    }

    public function show($id)
    {
        $valor = ValorAula::with('modalidade')->findOrFail($id);

        return response()->json([
            'data' => $valor
        ]);
    }

    public function update(Request $request, $id)
    {
        $valor = ValorAula::findOrFail($id);

        $request->validate([
            'modalidade_id' => 'sometimes|exists:modalidade,id_modalidade',
            'valor_aula'    => 'sometimes|numeric|min:0',
        ]);

        $valor->update($request->all());

        return response()->json([
            'message' => 'Valor da aula atualizado com sucesso',
            'data' => $valor
        ]);
    }

    public function destroy($id)
    {
        $valor = ValorAula::findOrFail($id);
        $valor->delete();

        return response()->json([
            'message' => 'Valor da aula removido com sucesso'
        ], 204);
    }
}
