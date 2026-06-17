<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Mensalidade;
use App\Models\DetalhesMensalidade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function mensalidadesAtrasadas()
    {
        $user = Auth::user();

        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $mensalidades = Mensalidade::with([
            'matricula.aluno.responsavel',
            'matricula.grade.professor',
            'detalhes',
        ])
            ->whereHas('detalhes', function ($query) {
                $query->where('det_mensa_status', 'Atrasado');
            })
            ->get();

        $mensalidadesAtrasadas = DetalhesMensalidade::where('det_mensa_status', 'Atrasado')
            ->where('id_emp_id', $user->id_emp_id)
            ->count();

        return view('view_admin_user.view_principal.view_dashboard.mensalidades_atrasadas', compact('mensalidades', 'mensalidadesAtrasadas'));
    }



   
    public function index(Request $request)
    {
        $user = Auth::user();

        $anoSelecionado = $request->get('ano', Carbon::now()->year);

        // Buscar anos disponíveis (opcional para o select)
        $anosDisponiveis = DB::table('matricula')
            ->select(DB::raw("DISTINCT YEAR(matri_data) as ano"))
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('ano', 'desc')
            ->pluck('ano');

        // Matrículas por mês filtrando pelo ano
        $matriculasPorMes = DB::table('matricula')
            ->select(
                DB::raw("MONTH(matri_data) as mes"),
                DB::raw("COUNT(*) as total")
            )
            ->whereYear('matri_data', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->groupBy('mes')
            ->pluck('total', 'mes');

        // Monta meses de Jan a Dez (com 0 quando não houver)
        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $dados = [];

        for ($m = 1; $m <= 12; $m++) {
            $dados[] = $matriculasPorMes[$m] ?? 0;
        }

        $matriculasPorMesEncerradas = DB::table('matricula')
            ->select(
                DB::raw("MONTH(matri_data) as mes"),
                DB::raw("COUNT(*) as total")
            )
            ->whereYear('matri_data', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('matri_status', 'Encerrada')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $dadosEncerrados = [];

        for ($m = 1; $m <= 12; $m++) {
            $dadosEncerrados[] = $matriculasPorMesEncerradas[$m] ?? 0;
        }


        //
        $totalMatriculas = DB::table('matricula')
            ->where('id_emp_id', $user->id_emp_id)
            ->where('matri_status', 'matriculado')
            ->count();

        $totalAlunosMatriculados =  DB::table('aluno')->where('id_emp_id', $user->id_emp_id)
            ->whereIn('id_aluno', function ($query) {
                $query->select('aluno_id_aluno')->from('matricula');
            })->count();


        $totalAlunosNaoMatriculados =  DB::table('aluno')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotIn('id_aluno', function ($query) {
                $query->select('aluno_id_aluno')->from('matricula');
            })->count();

        $totalBolsista = DB::table('aluno')
            ->where('id_emp_id', $user->id_emp_id)
            ->where('aluno_bolsista', 'sim')
            ->count();

        $receitaMensal = DetalhesMensalidade::whereMonth('det_mensa_data_venc', Carbon::now()->month)
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->where('id_emp_id', $user->id_emp_id)
            ->sum('det_mensa_valor');

        $receitaMensalPago = DetalhesMensalidade::whereMonth('det_mensa_data_venc', Carbon::now()->month)
            ->where('det_mensa_status', 'Pago')
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->where('id_emp_id', $user->id_emp_id)
            ->sum('det_mensa_valor');

        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $mensalidadesAtrasadas = DetalhesMensalidade::where('det_mensa_status', 'Atrasado')
            ->where('id_emp_id', $user->id_emp_id)
            ->count();

        

        return view('view_admin_user.dashboard', [
            'graficoLabels' => $labels,
            'graficoDados'  => $dados,
            'graficoDadosEncerrados'  => $dadosEncerrados,
            'anosDisponiveis' => $anosDisponiveis,
            'anoSelecionado' => $anoSelecionado,
            'totalMatriculas' => $totalMatriculas,
            'totalAlunosMatriculados' => $totalAlunosMatriculados,
            'totalAlunosNaoMatriculados' => $totalAlunosNaoMatriculados,
            'totalBolsista' => $totalBolsista,
            'receitaMensal' => $receitaMensal,
            'receitaMensalPago' => $receitaMensalPago,
            'mensalidadesAtrasadas' => $mensalidadesAtrasadas,
        ]);
    }
}
