<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\GradeHorario;
use App\Models\Visitante;
use App\Models\VisitanteGrade;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class VisitanteGradeController extends Controller
{
    public function index($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $visitante = Visitante::where('id_visitante', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $gradesVisitante = VisitanteGrade::with('grade.professor')
            ->where('visitante_id_visitante', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->latest('id_visitante_grade')
            ->get();

        $grades = GradeHorario::with('professor')
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('grade_modalidade')
            ->orderBy('grade_turma')
            ->orderBy('grade_inicio')
            ->get();

        return view(
            'view_admin_user.view_principal.view_visitante.grade',
            compact(
                'visitante',
                'gradesVisitante',
                'grades'
            )
        );
    }

    public function store(Request $request, $idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $visitante = Visitante::where('id_visitante', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'visgra_data' => [
                'required',
                'date',
                'before_or_equal:today',
                $visitante->vis_data_nasc
                    ? 'after_or_equal:' . $visitante->vis_data_nasc->format('Y-m-d')
                    : 'nullable',
            ],
            'grade_id_grade' => [
                'required',
                'exists:grade_horario,id_grade',
            ],
            'visgra_desc' => 'nullable|string|max:150',
        ], [
            'visgra_data.required' => 'A data é obrigatória.',
            'visgra_data.date' => 'Informe uma data válida.',
            'visgra_data.before_or_equal' => 'A data não pode ser futura.',
            'visgra_data.after_or_equal' => 'A data não pode ser anterior à data de nascimento do visitante.',
            'grade_id_grade.required' => 'Selecione um horário.',
            'grade_id_grade.exists' => 'O horário selecionado não existe.',
            'visgra_desc.max' => 'A descrição pode ter no máximo 150 caracteres.',
        ]);

        $grade = GradeHorario::where('id_grade', $request->grade_id_grade)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $jaExiste = VisitanteGrade::where('visitante_id_visitante', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('grade', function ($query) use ($grade) {
                $query->where('grade_modalidade', $grade->grade_modalidade);
            })
            ->exists();

        if ($jaExiste) {
            return back()
                ->withInput()
                ->withErrors([
                    'grade_id_grade' => 'O visitante já possui uma grade cadastrada na modalidade "' . $grade->grade_modalidade . '".'
                ]);
        }

        VisitanteGrade::create([
            'visitante_id_visitante' => $id,
            'grade_id_grade' => $grade->id_grade,
            'visgra_data' => $request->visgra_data,
            'visgra_desc' => $request->visgra_desc,
            'id_emp_id' => $user->id_emp_id,
        ]);

        return redirect()
            ->route('visitantes.grade', Crypt::encrypt($id))
            ->with('success', 'Grade de horário vinculada ao visitante com sucesso!');
    }

    public function destroy($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $gradeVisitante = VisitanteGrade::where('id_visitante_grade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $visitanteId = $gradeVisitante->visitante_id_visitante;

        $gradeVisitante->delete();

        return redirect()
            ->route('visitantes.grade', Crypt::encrypt($visitanteId))
            ->with('success', 'Grade de horário removida com sucesso!');
    }
}
