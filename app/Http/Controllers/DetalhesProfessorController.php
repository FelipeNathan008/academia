<?php

namespace App\Http\Controllers;

use App\Models\DetalhesProfessor;
use Illuminate\Http\Request;

class DetalhesProfessorController extends Controller
{
    public function index()
    {
        $detalhes = DetalhesProfessor::with('professor')->get();

        return response()->json([
            'data' => $detalhes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'professor_id_professor' => 'required|exists:professor,id_professor',
            'det_gradu_nome_cor'     => 'required|string|max:80',
            'det_grau'               => 'required|integer',
            'det_modalidade'         => 'required|string|max:100',
        ]);

        $detalhe = DetalhesProfessor::create([
            'professor_id_professor' => $request->professor_id_professor,
            'det_gradu_nome_cor'     => $request->det_gradu_nome_cor,
            'det_grau'               => $request->det_grau,
            'det_modalidade'         => $request->det_modalidade,
        ]);

        return response()->json([
            'message' => 'Detalhe do professor criado com sucesso',
            'data' => $detalhe
        ], 201);
    }

    public function show($id)
    {
        $detalhe = DetalhesProfessor::with('professor')->findOrFail($id);

        return response()->json([
            'data' => $detalhe
        ]);
    }

    public function update(Request $request, $id)
    {
        $detalhe = DetalhesProfessor::findOrFail($id);

        $request->validate([
            'professor_id_professor' => 'sometimes|exists:professor,id_professor',
            'det_gradu_nome_cor'     => 'sometimes|string|max:80',
            'det_grau'               => 'sometimes|integer',
            'det_modalidade'         => 'sometimes|string|max:100',
        ]);

        $detalhe->update($request->all());

        return response()->json([
            'message' => 'Detalhe do professor atualizado com sucesso',
            'data' => $detalhe
        ]);
    }

    public function destroy($id)
    {
        $detalhe = DetalhesProfessor::findOrFail($id);
        $detalhe->delete();

        return response()->json([
            'message' => 'Detalhe do professor removido com sucesso'
        ], 204);
    }
}
