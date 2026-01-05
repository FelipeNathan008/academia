<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use App\Models\Aluno;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    public function index()
    {
        $responsaveis = Responsavel::with('aluno')->get();

        return response()->json([
            'data' => $responsaveis
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_id_aluno' => 'required|exists:aluno,id_aluno',
            'resp_nome' => 'required|string|max:120',
            'resp_parentesco' => 'required|string|max:60',
            'resp_cpf' => 'required|string|size:11',
            'resp_logradouro' => 'required|string|max:150',
            'resp_bairro' => 'required|string|max:150',
            'resp_cidade' => 'required|string|max:150',
        ]);

        $responsavel = Responsavel::create($request->all());

        return response()->json([
            'message' => 'Responsável cadastrado com sucesso',
            'data' => $responsavel
        ], 201);
    }

    public function show($id)
    {
        $responsavel = Responsavel::with('aluno')->findOrFail($id);

        return response()->json([
            'data' => $responsavel
        ]);
    }

    public function update(Request $request, $id)
    {
        $responsavel = Responsavel::findOrFail($id);

        $request->validate([
            'aluno_id_aluno' => 'sometimes|exists:aluno,id_aluno',
            'resp_nome' => 'sometimes|string|max:120',
            'resp_parentesco' => 'sometimes|string|max:60',
            'resp_cpf' => 'sometimes|string|size:11',
            'resp_logradouro' => 'sometimes|string|max:150',
            'resp_bairro' => 'sometimes|string|max:150',
            'resp_cidade' => 'sometimes|string|max:150',
        ]);

        $responsavel->update($request->all());

        return response()->json([
            'message' => 'Responsável atualizado com sucesso',
            'data' => $responsavel
        ]);
    }

    public function destroy($id)
    {
        $responsavel = Responsavel::findOrFail($id);
        $responsavel->delete();

        return response()->json([
            'message' => 'Responsável removido com sucesso'
        ], 204);
    }
}
