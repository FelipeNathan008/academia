<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\GradeHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class AulaController extends Controller
{

    public function grades()
    {
        $user = Auth::user();

        $grades = GradeHorario::with('professor')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        return view('view_aula.grades', compact('grades'));
    }
    public function index($gradeId)
    {
        try {
            $id = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $grade = GradeHorario::with('professor')
            ->where('id_grade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $aulas = Aula::where('grade_horario_id', $id)->get();

        return view('view_aula.index', [
            'grade' => $grade,
            'aulas' => $aulas
        ]);
    }

    public function store(Request $request, $gradeId)
    {
        try {
            $id = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $request->validate([
            'aula_posicao_ensino' => 'required|string|max:150',
            'aula_periodo_inicial' => 'required|date',
            'aula_periodo_final' => 'required|date|after_or_equal:aula_periodo_inicial',
        ]);

        $user = Auth::user();

        $grade = GradeHorario::where('id_grade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        Aula::create([
            'professor_id' => $grade->professor_id_professor,
            'grade_horario_id' => $grade->id_grade,
            'aula_posicao_ensino' => $request->aula_posicao_ensino,
            'aula_periodo_inicial' => $request->aula_periodo_inicial,
            'aula_periodo_final' => $request->aula_periodo_final,
            'id_emp_id' => $user->id_emp_id,
        ]);

        return redirect()
            ->route('aulas', Crypt::encrypt($grade->id_grade))
            ->with('success', 'Aula cadastrada com sucesso!');
    }

    public function edit($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aula = Aula::with(['professor', 'gradeHorario'])
            ->where('id_aula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_aulas.edit', compact('aula'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aula = Aula::where('id_aula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'aula_posicao_ensino' => 'required|string|max:150',
            'aula_periodo_inicial' => 'required|date',
            'aula_periodo_final' => 'required|date|after_or_equal:aula_periodo_inicial',
        ]);

        $aula->aula_posicao_ensino = $request->aula_posicao_ensino;
        $aula->aula_periodo_inicial = $request->aula_periodo_inicial;
        $aula->aula_periodo_final = $request->aula_periodo_final;

        $aula->save();

        return redirect()
            ->route('aulas', Crypt::encrypt($aula->grade_horario_id))
            ->with('success', 'Aula atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aula = Aula::where('id_aula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $gradeId = $aula->grade_horario_id;

        $aula->delete();

        return redirect()
            ->route('aulas', Crypt::encrypt($gradeId))
            ->with('success', 'Aula removida com sucesso!');
    }
}
