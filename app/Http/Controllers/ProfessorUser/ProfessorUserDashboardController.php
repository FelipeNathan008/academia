<?php

namespace App\Http\Controllers\ProfessorUser;

use App\Http\Controllers\Controller;

use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
use App\Models\Mensalidade;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProfessorUserDashboardController extends Controller
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
        $mensalidadesAtrasadas = DetalhesMensalidade::where('id_emp_id', $user->id_emp_id)
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->whereIn('det_mensa_status', [
                'Em aberto',
                'Atrasado',
                'Comprovante enviado',
                'Rejeitado'
            ])
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->count();

        $comprovantesPendentes = DetalhesMensalidade::where(
            'det_mensa_status',
            'Comprovante enviado'
        )
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->count();

        return view('view_professor_user.dashboard.dashboard_professor', compact(
            'user',
            'professor',
            'totalAlunos',
            'totalBolsistas',
            'receitaMensal',
            'receitaMensalPago',
            'mensalidadesAtrasadas',
            'comprovantesPendentes'
        ));
    }

    public function comprovantesPendentes()
    {
        $user = Auth::user();

        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $comprovantes = DetalhesMensalidade::with([
            'mensalidade.matricula.aluno.responsavel'
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->where('det_mensa_status', 'Comprovante enviado')
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->orderByDesc('det_mensa_comprovante_enviado_em')
            ->get();

        return view(
            'view_professor_user.dashboard.comprovantes_pendentes',
            compact('comprovantes')
        );
    }

    public function mensalidadesAtrasadas()
    {
        $user = Auth::user();

        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $mensalidades = Mensalidade::with([
            'matricula.aluno.responsavel',
            'matricula.grade.professor',
            'detalhes',
        ])
            ->whereHas('detalhes', function ($query) use ($user) {
                $query->where('id_emp_id', $user->id_emp_id)
                    ->whereDate('det_mensa_data_venc', '<', Carbon::today())
                    ->whereIn('det_mensa_status', [
                        'Em aberto',
                        'Atrasado',
                        'Comprovante enviado',
                        'Rejeitado'
                    ]);
            })
            ->whereHas('matricula.grade', function ($query) use ($professor) {
                $query->where('professor_id_professor', $professor->id_professor);
            })
            ->get();

        $mensalidadesAtrasadas = DetalhesMensalidade::where('id_emp_id', $user->id_emp_id)
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->whereIn('det_mensa_status', [
                'Em aberto',
                'Atrasado',
                'Comprovante enviado',
                'Rejeitado'
            ])
            ->whereHas('mensalidade.matricula.grade', function ($q) use ($professor) {
                $q->where('professor_id_professor', $professor->id_professor);
            })
            ->count();

        return view(
            'view_professor_user.dashboard.mensalidades_atrasadas',
            compact('mensalidades', 'mensalidadesAtrasadas')
        );
    }
}
