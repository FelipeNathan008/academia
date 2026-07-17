<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\GradeHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class AulaController extends Controller
{
    public function grades(Request $request)
    {
        $user = Auth::user();

        $grades = GradeHorario::with('professor')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $professorFiltroNome = null;

        if ($request->filled('professor')) {
            try {
                $professorId = Crypt::decrypt($request->query('professor'));

                $professorFiltro = $grades->pluck('professor')
                    ->filter()
                    ->firstWhere('id_professor', $professorId);

                $professorFiltroNome = $professorFiltro->prof_nome ?? null;
            } catch (DecryptException $e) {
                // Link inválido/adulterado: ignora o filtro silenciosamente
                $professorFiltroNome = null;
            }
        }

        return view('view_admin_user.view_principal.view_aula.grades', compact('grades', 'professorFiltroNome'));
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

        $aulas = Aula::where('id_grade_horario', $id)->get();

        return view('view_admin_user.view_principal.view_aula.index', [
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
            'aula_nome_exercicio'    => 'required|string|max:150',
            'aula_caract_exercicio'  => 'required|string|max:255',
            'aula_inicio'            => 'required|date',
            'aula_fim'               => 'required|date|after_or_equal:aula_inicio',
            'aula_link'              => 'nullable|string|max:255',
            'aula_status'            => 'nullable|string|max:20',
            'aula_desc'              => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $grade = GradeHorario::where('id_grade', $id)
            ->where('id_emp_id', $user->id_emp_id)
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
            'id_emp_id'              => $user->id_emp_id,
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

        $aula = Aula::with('gradeHorario.professor')
            ->where('id_aula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin_user.view_principal.view_aula.edit', compact('aula'));
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
            ->route('aulas', Crypt::encrypt($aula->id_grade_horario))
            ->with('success', 'Aula atualizada com sucesso!');
    }
    public function show($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aula = Aula::with('gradeHorario.professor')
            ->where('id_aula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin_user.view_principal.view_aula.show', compact('aula'));
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

        $gradeId = $aula->id_grade_horario;

        $aula->delete();

        return redirect()
            ->route('aulas', Crypt::encrypt($gradeId))
            ->with('success', 'Aula removida com sucesso!');
    }
}
