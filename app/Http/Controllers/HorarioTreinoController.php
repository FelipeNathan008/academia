<?php

namespace App\Http\Controllers;

use App\Models\GradeHorario;
use App\Models\HorarioTreino;
use App\Models\Modalidade;
use Illuminate\Http\Request;

class HorarioTreinoController extends Controller
{
    public function index()
    {    
        $horarios = HorarioTreino::with('gradeHorario')->get();
        $modalidades = Modalidade::all();

        return view('view_admin.horario_treino', compact('horarios', 'modalidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hora_inicio'     => 'required',
            'hora_fim'        => 'required',
            'hora_semana'     => 'required|string|max:80',
            'hora_modalidade' => 'required|string|max:100',
        ]);

        HorarioTreino::create($request->all());

        return redirect()->route('horario_treino')
            ->with('success', 'Horário cadastrado com sucesso!');
    }

    public function show($id)
    {
        $horario = HorarioTreino::findOrFail($id);

        return response()->json([
            'data' => $horario
        ]);
    }

    public function edit($id)
    {
        $horario = HorarioTreino::findOrFail($id);
        $modalidades = Modalidade::all();

        return view('view_admin.horario_treino_edit', compact('horario', 'modalidades'));
    }

    public function update(Request $request, $id)
    {
        $horario = HorarioTreino::findOrFail($id);

        $request->validate([
            'hora_inicio'     => 'sometimes|required',
            'hora_fim'        => 'sometimes|required',
            'hora_semana'     => 'sometimes|string|max:80',
            'hora_modalidade' => 'sometimes|string|max:100',
        ]);

        $horario->update($request->all());

        return redirect()->route('horario_treino')
            ->with('success', 'Horário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $horario = HorarioTreino::findOrFail($id);
        $horario->delete();

        return redirect()->route('horario_treino')
            ->with('success', 'Horário removido com sucesso!');
    }
}
