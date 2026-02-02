<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::with(['aluno', 'detalhes'])->get();

        return response()->json([
            'data' => $matriculas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_id_aluno' => 'required|exists:aluno,id_aluno',
            'matri_desc' => 'required|string',
        ]);

        $matricula = Matricula::create($request->all());

        return response()->json([
            'message' => 'Matrícula criada com sucesso',
            'data' => $matricula
        ], 201);
    }

    public function show($id)
    {
        $matricula = Matricula::with(['aluno', 'detalhes'])->findOrFail($id);

        return response()->json([
            'data' => $matricula
        ]);
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $request->validate([
            'aluno_id_aluno' => 'sometimes|exists:aluno,id_aluno',
            'matri_desc' => 'sometimes|string',
        ]);

        $matricula->update($request->all());

        return response()->json([
            'message' => 'Matrícula atualizada com sucesso',
            'data' => $matricula
        ]);
    }

    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        $matricula->delete();

        return response()->json([
            'message' => 'Matrícula removida com sucesso'
        ], 204);
    }
}
