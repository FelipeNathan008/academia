<?php

namespace App\Http\Controllers;

use App\Models\FrequenciaAluno;
use Illuminate\Http\Request;

class FrequenciaAlunoController extends Controller
{
    public function index()
    {
        $frequencias = FrequenciaAluno::with('grade')->get();

        return response()->json([
            'data' => $frequencias
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_horario_id_grade' => 'required|exists:grade_horario,id_grade',
            'freq_alunos'            => 'required|string|max:60',
        ]);

        $frequencia = FrequenciaAluno::create([
            'grade_horario_id_grade' => $request->grade_horario_id_grade,
            'freq_alunos'            => $request->freq_alunos,
        ]);

        return response()->json([
            'message' => 'Frequência registrada com sucesso',
            'data' => $frequencia
        ], 201);
    }

    public function show($id)
    {
        $frequencia = FrequenciaAluno::with('grade')->findOrFail($id);

        return response()->json([
            'data' => $frequencia
        ]);
    }

    public function update(Request $request, $id)
    {
        $frequencia = FrequenciaAluno::findOrFail($id);

        $request->validate([
            'grade_horario_id_grade' => 'sometimes|exists:grade_horario,id_grade',
            'freq_alunos'            => 'sometimes|string|max:60',
        ]);

        $frequencia->update($request->all());

        return response()->json([
            'message' => 'Frequência atualizada com sucesso',
            'data' => $frequencia
        ]);
    }

    public function destroy($id)
    {
        $frequencia = FrequenciaAluno::findOrFail($id);
        $frequencia->delete();

        return response()->json([
            'message' => 'Frequência removida com sucesso'
        ], 204);
    }
}
