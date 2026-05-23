<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;

use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AlunoUserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        // TOTAL DE ALUNOS DO RESPONSÁVEL
        $totalAlunos = Aluno::where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->count();

        // TOTAL DE BOLSISTAS
        $totalBolsistas = Aluno::where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->where('aluno_bolsista', 'sim')
            ->count();

        // IDs DOS ALUNOS
        $alunosIds = Aluno::where('responsavel_id_responsavel', $responsavel->id_responsavel)
            ->pluck('id_aluno');

        // TOTAL MENSAL
        $receitaMensal = DetalhesMensalidade::whereMonth('det_mensa_data_venc', Carbon::now()->month)
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->whereHas('mensalidade.matricula', function ($q) use ($alunosIds) {
                $q->whereIn('aluno_id_aluno', $alunosIds);
            })
            ->sum('det_mensa_valor');

        // TOTAL PAGO
        $receitaMensalPago = DetalhesMensalidade::whereMonth('det_mensa_data_venc', Carbon::now()->month)
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->where('det_mensa_status', 'Pago')
            ->whereHas('mensalidade.matricula', function ($q) use ($alunosIds) {
                $q->whereIn('aluno_id_aluno', $alunosIds);
            })
            ->sum('det_mensa_valor');

        // ATUALIZA ATRASADOS
        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->whereHas('mensalidade.matricula', function ($q) use ($alunosIds) {
                $q->whereIn('aluno_id_aluno', $alunosIds);
            })
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        // TOTAL ATRASADO
        $mensalidadesAtrasadas = DetalhesMensalidade::where('det_mensa_status', 'Atrasado')
            ->whereHas('mensalidade.matricula', function ($q) use ($alunosIds) {
                $q->whereIn('aluno_id_aluno', $alunosIds);
            })
            ->count();

        return view('view_aluno_user.dashboard.dashboard_aluno', compact(
            'user',
            'responsavel',
            'totalAlunos',
            'totalBolsistas',
            'receitaMensal',
            'receitaMensalPago',
            'mensalidadesAtrasadas'
        ));
    }
}