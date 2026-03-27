<?php

namespace App\Http\Controllers;

use App\Models\GradeHorario;
use App\Models\HorarioTreino;
use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class HorarioTreinoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $horarios = HorarioTreino::with('gradeHorario')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();
        $modalidades = Modalidade::all();

        return view('view_admin.horario_treino', compact('horarios', 'modalidades'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'hora_inicio'     => 'required',
            'hora_fim'        => 'required',
            'hora_semana'     => 'required|array|min:1',
            'hora_modalidade' => 'required|string|max:100',
        ]);

        $dias = collect($request->hora_semana)
            ->map(fn($d) => (int) $d)
            ->sort()
            ->implode(',');

        HorarioTreino::create([
            'hora_semana'     => $dias,
            'hora_inicio'     => $request->hora_inicio,
            'hora_fim'        => $request->hora_fim,
            'hora_modalidade' => $request->hora_modalidade,
            'id_emp_id'       => $user->id_emp_id
        ]);

        return redirect()->route('horario_treino')
            ->with('success', 'Horário cadastrado com sucesso!');
    }


    public function edit($id)
    {
        $user = Auth::user();
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $horario = HorarioTreino::where('id_hora', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $modalidades = Modalidade::all();

        return view('view_admin.horario_treino_edit', compact('horario', 'modalidades'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $horario = HorarioTreino::where('id_hora', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();


        $request->validate([
            'hora_inicio'     => 'required',
            'hora_fim'        => 'required',
            'hora_semana'     => 'required|array|min:1',
            'hora_modalidade' => 'required|string|max:100',
        ]);

        // transforma array [1,2,3] em "1,2,3"
        $dias = collect($request->hora_semana)
            ->map(fn($d) => (int) $d)
            ->sort()
            ->implode(',');

        $horario->update([
            'hora_semana'     => $dias,
            'hora_inicio'     => $request->hora_inicio,
            'hora_fim'        => $request->hora_fim,
            'hora_modalidade' => $request->hora_modalidade,
        ]);

        return redirect()->route('horario_treino')
            ->with('success', 'Horário atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $user = Auth::user();
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $horario = HorarioTreino::where('id_hora', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $horario->delete();

        return redirect()->route('horario_treino')
            ->with('success', 'Horário removido com sucesso!');
    }
}
