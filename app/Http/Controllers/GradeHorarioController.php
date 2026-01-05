<?php

namespace App\Http\Controllers;

use App\Models\GradeHorario;
use Illuminate\Http\Request;

class GradeHorarioController extends Controller
{
    public function index()
    {
        $grades = GradeHorario::with('professor')->get();

        return response()->json([
            'data' => $grades
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'professor_id_professor' => 'required|exists:professor,id_professor',
            'grade_modalidade'       => 'required|integer',
            'grade_dia_semana'       => 'required|string|max:80',
            'grade_inicio'           => 'required|date_format:H:i',
            'grade_fim'              => 'required|date_format:H:i',
            'grade_desc'             => 'required|string|max:150',
        ]);

        $grade = GradeHorario::create($request->all());

        return response()->json([
            'message' => 'Grade de horário cadastrada com sucesso',
            'data' => $grade
        ], 201);
    }

    public function show($id)
    {
        $grade = GradeHorario::with('professor')->findOrFail($id);

        return response()->json([
            'data' => $grade
        ]);
    }

    public function update(Request $request, $id)
    {
        $grade = GradeHorario::findOrFail($id);

        $request->validate([
            'professor_id_professor' => 'sometimes|exists:professor,id_professor',
            'grade_modalidade'       => 'sometimes|integer',
            'grade_dia_semana'       => 'sometimes|string|max:80',
            'grade_inicio'           => 'sometimes|date_format:H:i',
            'grade_fim'              => 'sometimes|date_format:H:i',
            'grade_desc'             => 'sometimes|string|max:150',
        ]);

        $grade->update($request->all());

        return response()->json([
            'message' => 'Grade de horário atualizada com sucesso',
            'data' => $grade
        ]);
    }

    public function destroy($id)
    {
        $grade = GradeHorario::findOrFail($id);
        $grade->delete();

        return response()->json([
            'message' => 'Grade de horário removida com sucesso'
        ], 204);
    }
}
