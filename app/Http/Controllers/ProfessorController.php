<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function index()
    {
        $professores = Professor::with(['grades', 'detalhes'])->get();

        return response()->json([
            'data' => $professores
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'prof_nome' => 'required|string|max:120',
            'prof_nascimento' => 'required|date',
            'prof_desc' => 'required|string|max:150',
            'prof_foto' => 'required|string|max:255',
        ]);

        $professor = Professor::create($request->all());

        return response()->json([
            'message' => 'Professor cadastrado com sucesso',
            'data' => $professor
        ], 201);
    }

    public function show($id)
    {
        $professor = Professor::with(['grades', 'detalhes'])->findOrFail($id);

        return response()->json([
            'data' => $professor
        ]);
    }

    public function update(Request $request, $id)
    {
        $professor = Professor::findOrFail($id);

        $request->validate([
            'prof_nome' => 'sometimes|string|max:120',
            'prof_nascimento' => 'sometimes|date',
            'prof_desc' => 'sometimes|string|max:150',
            'prof_foto' => 'sometimes|string|max:255',
        ]);

        $professor->update($request->all());

        return response()->json([
            'message' => 'Professor atualizado com sucesso',
            'data' => $professor
        ]);
    }

    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        $professor->delete();

        return response()->json([
            'message' => 'Professor removido com sucesso'
        ], 204);
    }
}
