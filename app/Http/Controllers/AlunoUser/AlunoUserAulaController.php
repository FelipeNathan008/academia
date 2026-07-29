<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\GradeHorario;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class AlunoUserAulaController extends Controller
{
    // LISTAR AULAS DE UMA GRADE (TURMA) DO ALUNO
    public function index(Request $request, $gradeId)
    {
        try {
            $id = Crypt::decrypt($gradeId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        // Garante que a grade pertence a algum aluno do responsável logado
        $grade = GradeHorario::with('professor')
            ->where('id_grade', $id)
            ->whereHas('matriculas.aluno', function ($query) use ($responsavel) {
                $query->where('responsavel_id_responsavel', $responsavel->id_responsavel);
            })
            ->firstOrFail();

        $query = Aula::where('id_grade_horario', $id)
            ->where('aula_status', 'ativo');

        // FILTRO POR DIA
        if ($request->filled('dia')) {
            $dia = $request->dia;

            $query->whereDate('aula_inicio', '<=', $dia)
                ->whereDate('aula_fim', '>=', $dia);
        }

        $totalAulas = Aula::where('id_grade_horario', $id)
            ->where('aula_status', 'ativo')
            ->count();

        $aulas = $query
            ->orderBy('aula_inicio', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('view_aluno_user.aula.index', [
            'grade' => $grade,
            'aulas' => $aulas,
            'totalAulas' => $totalAulas,
            'gradeId' => $gradeId,
        ]);
    }

    public function grades(Request $request)
    {
        $responsavel = Auth::user()->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $query = Matricula::with([
            'aluno',
            'grade.professor'
        ])
            ->where('matri_status', '=', 'Matriculado')
            ->whereHas('aluno', function ($q) use ($responsavel) {
                $q->where('responsavel_id_responsavel', $responsavel->id_responsavel);
            });

        // Para os filtros
        $todasMatriculas = (clone $query)->get();

        if ($request->filled('aluno')) {
            $query->whereHas('aluno', function ($q) use ($request) {
                $q->where('aluno_nome', $request->aluno);
            });
        }

        if ($request->filled('turma')) {
            $query->whereHas('grade', function ($q) use ($request) {
                $q->where('grade_turma', $request->turma);
            });
        }

        if ($request->filled('modalidade')) {
            $query->whereHas('grade', function ($q) use ($request) {
                $q->where('grade_modalidade', $request->modalidade);
            });
        }

        if ($request->filled('professor')) {
            $query->whereHas('grade.professor', function ($q) use ($request) {
                $q->where('prof_nome', $request->professor);
            });
        }

        $totalMatriculas = (clone $query)->count();

        $matriculas = $query
            ->paginate(10)
            ->withQueryString();

        return view('view_aluno_user.aula.grades', [
            'matriculas'      => $matriculas,
            'totalMatriculas' => $totalMatriculas,
            'alunos' => $todasMatriculas->pluck('aluno.aluno_nome')->filter()->unique()->sort()->values(),
            'professores' => $todasMatriculas->pluck('grade.professor.prof_nome')->filter()->unique()->sort()->values(),
            'modalidades' => $todasMatriculas->pluck('grade.grade_modalidade')->filter()->unique()->sort()->values(),
            'turmas' => $todasMatriculas->pluck('grade.grade_turma')->filter()->unique()->sort()->values(),
        ]);
    }

    // SHOW DA AULA
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $aula = Aula::with('gradeHorario.professor')
            ->where('id_aula', $id)
            ->whereHas('gradeHorario.matriculas.aluno', function ($query) use ($responsavel) {
                $query->where('responsavel_id_responsavel', $responsavel->id_responsavel);
            })
            ->firstOrFail();

        return view('view_aluno_user.aula.show', compact('aula'));
    }
}
