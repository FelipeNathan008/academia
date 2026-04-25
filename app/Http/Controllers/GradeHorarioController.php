<?php

namespace App\Http\Controllers;

use App\Models\GradeHorario;
use App\Models\HorarioTreino;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class GradeHorarioController extends Controller
{
    public function index(Request $request)
    {


        $user = Auth::user();

        $grades = GradeHorario::with(['professor', 'horarioTreino'])
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $professores = Professor::all();
        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();
        $horariosTreino = HorarioTreino::whereDoesntHave('gradeHorario')->get();
        $turmas = Turma::where('id_emp_id', $user->id_emp_id)->get();

        return view('view_grade_horarios.index', compact(
            'grades',
            'professores',
            'horariosTreino',
            'turmas',
            'modalidades'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'professor_id_professor' => 'required|exists:professor,id_professor',
            'horario_treino_id_hora' => 'required|exists:horario_treino,id_hora',
            'grade_modalidade'       => 'required|string|max:80',
            'grade_dia_semana'       => 'required|string|max:80',
            'grade_inicio'           => 'required|date_format:H:i:s',
            'grade_fim'              => 'required|date_format:H:i:s',
            'grade_turma'            => 'required|string|max:60',
            'grade_desc'             => 'required|string',
        ]);
        $jaExiste = GradeHorario::where('professor_id_professor', $request->professor_id_professor)
            ->where('horario_treino_id_hora', $request->horario_treino_id_hora)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'horario_treino_id_hora' => 'Erro, Já existe essa grade de horário cadastrada para este professor.'
            ])->withInput();
        }

        $horario = HorarioTreino::findOrFail($request->horario_treino_id_hora);

        $jaExiste = GradeHorario::where('id_grade', $request->id_grade)
            ->where('professor_id_professor', $request->professor_id_professor)
            ->where('horario_treino_id_hora', $request->horario_treino_id_hora)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'horario_treino_id_hora' => 'Erro, Já existe essa grade de horário cadastrada.'
            ])->withInput();
        }
        GradeHorario::create([
            'professor_id_professor' => $request->professor_id_professor,
            'horario_treino_id_hora' => $request->horario_treino_id_hora,
            'grade_modalidade'       => $request->grade_modalidade,
            'grade_dia_semana'       => $request->grade_dia_semana,
            'grade_inicio'           => $request->grade_inicio,
            'grade_fim'              => $request->grade_fim,
            'grade_turma'            => $request->grade_turma,
            'grade_desc'             => $request->grade_desc,
            'id_emp_id'              => $user->id_emp_id
        ]);

        return redirect()
            ->route('grade_horarios')
            ->with('success', 'Grade de horário cadastrada com sucesso!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $grade = GradeHorario::where('id_grade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $professores = Professor::all();
        $horariosTreino = HorarioTreino::whereDoesntHave('gradeHorario')
            ->orWhere('id_hora', $grade->horario_treino_id_hora)
            ->get();
        $turmas = Turma::where('id_emp_id', $user->id_emp_id)->get();
        return view('view_grade_horarios.edit', compact(
            'grade',
            'professores',
            'horariosTreino',
            'turmas'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $grade = GradeHorario::where('id_grade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'professor_id_professor' => 'required|exists:professor,id_professor',
            'horario_treino_id_hora' => 'required|exists:horario_treino,id_hora',
            'grade_modalidade'       => 'required|string|max:80',
            'grade_dia_semana'       => 'required|string|max:80',
            'grade_inicio'           => 'required|date_format:H:i:s',
            'grade_fim'              => 'required|date_format:H:i:s',
            'grade_turma'            => 'required|string|max:60',
            'grade_desc'             => 'required|string',
        ]);

        $jaExiste = GradeHorario::where('professor_id_professor', $request->professor_id_professor)
            ->where('horario_treino_id_hora', $request->horario_treino_id_hora)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_grade', '!=', $grade->id_grade)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'horario_treino_id_hora' => 'Erro, Já existe essa grade de horário cadastrada para este professor.'
            ])->withInput();
        }

        $grade->update($request->all());

        return redirect()
            ->route('grade_horarios')
            ->with('success', 'Grade de horário atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $grade = GradeHorario::where('id_grade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $grade->delete();

        return redirect()
            ->route('grade_horarios')
            ->with('success', 'Grade de horário excluída com sucesso!');
    }
}
