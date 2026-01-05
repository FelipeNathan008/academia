<?php

namespace App\Http\Controllers;

use App\Models\Modalidade;
use Illuminate\Http\Request;

class ModalidadeController extends Controller
{
    public function index()
    {
        $modalidades = Modalidade::with('valores')->get();

        return response()->json([
            'data' => $modalidades
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mod_nome' => 'required|string|max:100',
            'mod_desc' => 'required|string',
        ]);

        $modalidade = Modalidade::create($request->all());

        return response()->json([
            'message' => 'Modalidade cadastrada com sucesso',
            'data' => $modalidade
        ], 201);
    }

    public function show($id)
    {
        $modalidade = Modalidade::with('valores')->findOrFail($id);

        return response()->json([
            'data' => $modalidade
        ]);
    }

    public function update(Request $request, $id)
    {
        $modalidade = Modalidade::findOrFail($id);

        $request->validate([
            'mod_nome' => 'sometimes|string|max:100',
            'mod_desc' => 'sometimes|string',
        ]);

        $modalidade->update($request->all());

        return response()->json([
            'message' => 'Modalidade atualizada com sucesso',
            'data' => $modalidade
        ]);
    }

    public function destroy($id)
    {
        $modalidade = Modalidade::findOrFail($id);
        $modalidade->delete();

        return response()->json([
            'message' => 'Modalidade removida com sucesso'
        ], 204);
    }
}
