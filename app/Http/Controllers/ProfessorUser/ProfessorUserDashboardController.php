<?php

namespace App\Http\Controllers\ProfessorUser;

use App\Http\Controllers\Controller;

use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
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
        $mensalidadesAtrasadas = DetalhesMensalidade::where('det_mensa_status', 'Atrasado')
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
            'mensalidadesAtrasadas'
        ));
    }
}
