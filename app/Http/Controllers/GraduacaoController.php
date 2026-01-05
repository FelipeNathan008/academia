<?php

namespace App\Http\Controllers;

use App\Models\Graduacao;
use Illuminate\Http\Request;

class GraduacaoController extends Controller
{
    public function index()
    {
        $graduacoes = Graduacao::all();

        return response()->json([
            'data' => $graduacoes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gradu_nome_cor' => 'required|string|max:80',
            'gradu_grau'     => 'required|integer',
        ]);

        $graduacao = Graduacao::create($request->all());

        return response()->json([
            'message' => 'Graduação cadastrada com sucesso',
            'data' => $graduacao
        ], 201);
    }

    public function show($id)
    {
        $graduacao = Graduacao::findOrFail($id);

        return response()->json([
            'data' => $graduacao
        ]);
    }

    public function update(Request $request, $id)
    {
        $graduacao = Graduacao::findOrFail($id);

        $request->validate([
            'gradu_nome_cor' => 'sometimes|string|max:80',
            'gradu_grau'     => 'sometimes|integer',
        ]);

        $graduacao->update($request->all());

        return response()->json([
            'message' => 'Graduação atualizada com sucesso',
            'data' => $graduacao
        ]);
    }

    public function destroy($id)
    {
        $graduacao = Graduacao::findOrFail($id);
        $graduacao->delete();

        return response()->json([
            'message' => 'Graduação removida com sucesso'
        ], 204);
    }
}
