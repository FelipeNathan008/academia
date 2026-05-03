<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Mensalidade;
use App\Models\DetalhesMensalidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class ProfessorUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $professor = $user->professor;
        if (!$professor) {
            abort(403);
        }

        // TOTAL DE ALUNOS DO PROFESSOR
        $totalAlunos = Aluno::whereHas('matriculas.grade', function ($query) use ($professor) {
            $query->where('professor_id_professor', $professor->id_professor);
        })->count();

        // TOTAL DE BOLSISTAS (SOMENTE ALUNOS DO PROFESSOR)
        $totalBolsistas = Aluno::where('aluno_bolsista', 'sim')
            ->whereHas('matriculas.grade', function ($query) use ($professor) {
                $query->where('professor_id_professor', $professor->id_professor);
            })
            ->count();

        // RECEITA MENSAL (TOTAL)
        $receitaMensal = DetalhesMensalidade::whereMonth('det_mensa_data_venc', Carbon::now()->month)
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->sum('det_mensa_valor');

        // RECEITA MENSAL PAGA
        $receitaMensalPago = DetalhesMensalidade::whereMonth('det_mensa_data_venc', Carbon::now()->month)
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->where('det_mensa_status', 'Pago')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->sum('det_mensa_valor');

        // ATUALIZA ATRASADOS
        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        // MENSALIDADES ATRASADAS
        $mensalidadesAtrasadas = DetalhesMensalidade::where('det_mensa_status', 'Atrasado')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->count();

        return view('view_professor_user.dashboard_professor', compact(
            'user',
            'professor',
            'totalAlunos',
            'totalBolsistas',
            'receitaMensal',
            'receitaMensalPago',
            'mensalidadesAtrasadas'
        ));
    }

    public function alunos()
    {
        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $professor->qtd_aluno = DB::table('matricula as m')
            ->join('grade_horario as g', 'm.grade_id_grade', '=', 'g.id_grade')
            ->where('g.professor_id_professor', $professor->id_professor)
            ->where('m.matri_status', 'Matriculado')
            ->count();
        // Alunos do professor
        $alunos = Aluno::whereHas('matriculas.grade', function ($query) use ($professor) {
            $query->where('professor_id_professor', $professor->id_professor);
        })
            ->with(['matriculas.grade']) // já carrega turma
            ->get();

        return view('view_professor_user.alunos_professor', compact('alunos', 'professor'));
    }

    public function show(string $id)
    {
        $id = decrypt($id);

        $professor = Auth::user()->professor;

        if (!$professor || $professor->id_professor != $id) {
            abort(403);
        }

        return view('view_professor_user.show_professor', compact('professor'));
    }

    public function showAluno(string $id)
    {
        $id = decrypt($id);

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($query) use ($professor) {
            $query->where('professor_id_professor', $professor->id_professor);
        })
            ->with(['responsavel', 'matriculas.grade'])
            ->findOrFail($id);

        return view('view_professor_user.show_aluno', compact('aluno'));
    }

    public function financeiro(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->with(['responsavel', 'matriculas.grade'])
            ->findOrFail($id);

        \App\Models\DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->where('id_emp_id', $user->id_emp_id)
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $mensalidades = Mensalidade::with([
            'detalhes',
            'matricula.grade.professor'
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('matricula', function ($q) use ($id, $professor) {
                $q->where('aluno_id_aluno', $id)
                    ->whereHas('grade', function ($g) use ($professor) {
                        $g->where('professor_id_professor', $professor->id_professor);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('view_professor_user.financeiro_professor', compact('aluno', 'mensalidades'));
    }

    public function darBaixa(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        $detalhe = DetalhesMensalidade::where('id_detalhes_mensalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->firstOrFail();

        $detalhe->update([
            'det_mensa_status' => 'Pago',
            'det_mensa_data_pagamento' => Carbon::now()->format('Y-m-d')
        ]);

        return back()->with('success', 'Parcela baixada!');
    }

    public function desfazerBaixa(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        $detalhe = DetalhesMensalidade::where('id_detalhes_mensalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->firstOrFail();

        $detalhe->update([
            'det_mensa_status' => 'Em aberto',
            'det_mensa_data_pagamento' => null
        ]);

        return back()->with('success', 'Baixa desfeita!');
    }

    public function editarForma(Request $request)
    {
        $request->validate([
            'mensalidade_id' => 'required',
            'nova_forma' => 'required'
        ]);

        $user = Auth::user();
        $professor = $user->professor;

        $mensalidade = Mensalidade::where('id_mensalidade', $request->mensalidade_id)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->firstOrFail();

        DetalhesMensalidade::where('mensalidade_id_mensalidade', $mensalidade->id_mensalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->update([
                'det_mensa_forma_pagamento' => $request->nova_forma
            ]);

        return back()->with('success', 'Forma atualizada!');
    }

    public function agenda()
    {
        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        // Buscar apenas grades do professor logado
        $grades = \App\Models\GradeHorario::with('professor')
            ->where('professor_id_professor', $professor->id_professor)
            ->get();

        // Modalidades únicas
        $modalidades = $grades->pluck('grade_modalidade')->unique();

        return view('view_professor_user.agenda_professor', compact('grades', 'modalidades'));
    }

    public function showAgenda(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $grade = \App\Models\GradeHorario::with([
            'professor',
            'matriculas.aluno'
        ])
            ->where('id_grade', $id)
            ->where('professor_id_professor', $professor->id_professor) // trava acesso
            ->firstOrFail();

        return view('view_professor_user.show_agenda_professor', compact('grade'));
    }
}
