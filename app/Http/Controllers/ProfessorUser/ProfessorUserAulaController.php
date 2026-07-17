<?php

namespace App\Http\Controllers\professorUser;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\GradeHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class ProfessorUserAulaController extends Controller
{
    public function index($gradeId)
    {
        try {
            $id = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $grade = GradeHorario::with('professor')
            ->where('id_grade', $id)
            ->where('professor_id_professor', $professor->id_professor)
            ->firstOrFail();

        $aulas = Aula::where('id_grade_horario', $id)
            ->orderBy('aula_inicio', 'desc')
            ->get();

        return view('view_professor_user.aula.index', [
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

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $request->validate([
            'aula_nome_exercicio'    => 'required|string|max:150',
            'aula_caract_exercicio'  => 'required|string|max:255',
            'aula_inicio'            => 'required|date',
            'aula_fim'               => 'required|date|after_or_equal:aula_inicio',
            'aula_link'              => 'nullable|string|max:255',
            'aula_status'            => 'nullable|string|max:20',
            'aula_desc'              => 'required|string|max:255',
        ]);

        $grade = GradeHorario::where('id_grade', $id)
            ->where('professor_id_professor', $professor->id_professor)
            ->firstOrFail();

        Aula::create([
            'id_grade_horario'       => $grade->id_grade,
            'aula_nome_exercicio'    => $request->aula_nome_exercicio,
            'aula_caract_exercicio'  => $request->aula_caract_exercicio,
            'aula_inicio'            => $request->aula_inicio,
            'aula_fim'               => $request->aula_fim,
            'aula_link'              => $request->aula_link,
            'aula_status'            => $request->aula_status ?? 'ativo',
            'aula_desc'              => $request->aula_desc,
            'id_emp_id'              => Auth::user()->id_emp_id,
        ]);

        return redirect()
            ->route('professor-aulas', Crypt::encrypt($grade->id_grade))
            ->with('success', 'Aula cadastrada com sucesso!');
    }

    public function show($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aula = Aula::with('gradeHorario.professor')
            ->whereHas('gradeHorario', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->where('id_aula', $id)
            ->firstOrFail();

        return view('view_professor_user.aula.show', compact('aula'));
    }

    public function edit($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aula = Aula::with('gradeHorario.professor')
            ->whereHas('gradeHorario', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->where('id_aula', $id)
            ->firstOrFail();

        return view('view_professor_user.aula.edit', compact('aula'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aula = Aula::whereHas('gradeHorario', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->where('id_aula', $id)
            ->firstOrFail();

        $request->validate([
            'aula_nome_exercicio'    => 'required|string|max:150',
            'aula_caract_exercicio'  => 'required|string|max:255',
            'aula_inicio'            => 'required|date',
            'aula_fim'               => 'required|date|after_or_equal:aula_inicio',
            'aula_link'              => 'nullable|string|max:255',
            'aula_status'            => 'nullable|string|max:20',
            'aula_desc'              => 'required|string|max:255',
        ]);

        $aula->aula_nome_exercicio   = $request->aula_nome_exercicio;
        $aula->aula_caract_exercicio = $request->aula_caract_exercicio;
        $aula->aula_inicio           = $request->aula_inicio;
        $aula->aula_fim              = $request->aula_fim;
        $aula->aula_link             = $request->aula_link;
        $aula->aula_status           = $request->aula_status ?? $aula->aula_status;
        $aula->aula_desc             = $request->aula_desc;

        $aula->save();

        return redirect()
            ->route('professor-aulas', Crypt::encrypt($aula->id_grade_horario))
            ->with('success', 'Aula atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aula = Aula::whereHas('gradeHorario', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->where('id_aula', $id)
            ->firstOrFail();

        $gradeId = $aula->id_grade_horario;

        $aula->delete();

        return redirect()
            ->route('professor-aulas', Crypt::encrypt($gradeId))
            ->with('success', 'Aula removida com sucesso!');
    }
}
