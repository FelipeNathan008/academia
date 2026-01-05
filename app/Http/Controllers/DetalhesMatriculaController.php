<?php

namespace App\Http\Controllers;

use App\Models\DetalhesMatricula;
use Illuminate\Http\Request;

class DetalhesMatriculaController extends Controller
{
    public function index()
    {
        $detalhes = DetalhesMatricula::with([
            'matricula',
            'modalidade',
            'grade'
        ])->get();

        return response()->json([
            'data' => $detalhes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula_id_matricula'   => 'required|exists:matricula,id_matricula',
            'modalidade_id_modalidade' => 'required|exists:modalidade,id_modalidade',
            'grade_horario_id_grade'   => 'required|exists:grade_horario,id_grade',
        ]);

        $detalhe = DetalhesMatricula::create([
            'matricula_id_matricula'   => $request->matricula_id_matricula,
            'modalidade_id_modalidade' => $request->modalidade_id_modalidade,
            'grade_horario_id_grade'   => $request->grade_horario_id_grade,
        ]);

        return response()->json([
            'message' => 'Detalhe da matrícula criado com sucesso',
            'data' => $detalhe
        ], 201);
    }

    public function show($id)
    {
        $detalhe = DetalhesMatricula::with([
            'matricula',
            'modalidade',
            'grade'
        ])->findOrFail($id);

        return response()->json([
            'data' => $detalhe
        ]);
    }

    public function update(Request $request, $id)
    {
        $detalhe = DetalhesMatricula::findOrFail($id);

        $request->validate([
            'matricula_id_matricula'   => 'sometimes|exists:matricula,id_matricula',
            'modalidade_id_modalidade' => 'sometimes|exists:modalidade,id_modalidade',
            'grade_horario_id_grade'   => 'sometimes|exists:grade_horario,id_grade',
        ]);

        $detalhe->update($request->all());

        return response()->json([
            'message' => 'Detalhe da matrícula atualizado com sucesso',
            'data' => $detalhe
        ]);
    }

    public function destroy($id)
    {
        $detalhe = DetalhesMatricula::findOrFail($id);
        $detalhe->delete();

        return response()->json([
            'message' => 'Detalhe da matrícula removido com sucesso'
        ], 204);
    }
}
