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

    public function graduacoes(Request $request)
    {
        $user = Auth::user();

        $modalidades = DB::table('modalidade')
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('mod_nome')
            ->pluck('mod_nome');

        $modalidadeSelecionada = $request->get('modalidade');
        $faixaSelecionada      = $request->get('faixa');

        $porGraduacao  = collect();
        $alunosDaFaixa = collect();

        if ($modalidadeSelecionada) {

            /*
         * Para cada aluno, considera apenas a graduação com maior gradu_ordem
         * na modalidade selecionada (desempate por det_data desc).
         * Depois agrupa SOMENTE PELA COR (ignora o grau) e soma o total
         * de alunos daquela cor, somando todos os graus.
         */
            $porGraduacao = DB::table('detalhes_aluno as da')
                ->join('graduacao as g', 'g.id_graduacao', '=', 'da.id_graduacao')
                ->join('modalidade as m', 'm.id_modalidade', '=', 'g.id_modalidade')
                ->where('da.id_emp_id', $user->id_emp_id)
                ->where('m.mod_nome', $modalidadeSelecionada)
                ->whereRaw('da.id_graduacao = (
                SELECT da2.id_graduacao
                FROM detalhes_aluno da2
                JOIN graduacao g2  ON g2.id_graduacao   = da2.id_graduacao
                JOIN modalidade m2 ON m2.id_modalidade  = g2.id_modalidade
                WHERE da2.aluno_id_aluno = da.aluno_id_aluno
                  AND da2.id_emp_id      = da.id_emp_id
                  AND m2.mod_nome        = ?
                ORDER BY g2.gradu_ordem DESC, da2.det_data DESC
                LIMIT 1
            )', [$modalidadeSelecionada])
                // agrupa apenas pela cor — soma todos os graus daquela cor
                ->groupBy('g.gradu_nome_cor')
                ->select(
                    DB::raw('MIN(g.id_graduacao) as id_graduacao'),
                    DB::raw('MIN(g.gradu_ordem) as gradu_ordem'),
                    'g.gradu_nome_cor',
                    DB::raw('COUNT(DISTINCT da.aluno_id_aluno) as total')
                )
                ->orderBy('gradu_ordem')
                ->get();

            // Se clicou em uma faixa (cor), carrega todos os alunos dela,
            // independente do grau
            if ($faixaSelecionada) {
                $alunosDaFaixa = DB::table('detalhes_aluno as da')
                    ->join('graduacao as g', 'g.id_graduacao', '=', 'da.id_graduacao')
                    ->join('modalidade as m', 'm.id_modalidade', '=', 'g.id_modalidade')
                    ->join('aluno as a', 'a.id_aluno', '=', 'da.aluno_id_aluno')
                    ->where('da.id_emp_id', $user->id_emp_id)
                    ->where('m.mod_nome', $modalidadeSelecionada)
                    ->whereRaw('LOWER(g.gradu_nome_cor) = ?', [strtolower($faixaSelecionada)])
                    ->whereRaw('da.id_graduacao = (
                    SELECT da2.id_graduacao
                    FROM detalhes_aluno da2
                    JOIN graduacao g2  ON g2.id_graduacao   = da2.id_graduacao
                    JOIN modalidade m2 ON m2.id_modalidade  = g2.id_modalidade
                    WHERE da2.aluno_id_aluno = da.aluno_id_aluno
                      AND da2.id_emp_id      = da.id_emp_id
                      AND m2.mod_nome        = ?
                    ORDER BY g2.gradu_ordem DESC, da2.det_data DESC
                    LIMIT 1
                )', [$modalidadeSelecionada])
                    ->select(
                        'a.id_aluno',
                        'a.aluno_nome',
                        'a.responsavel_id_responsavel',
                        'da.det_data',
                        'g.gradu_nome_cor',
                        'g.gradu_grau',
                        'g.gradu_ordem'
                    )
                    ->orderBy('g.gradu_grau')
                    ->orderBy('a.aluno_nome')
                    ->get();
            }
        }

        return view('view_admin_user.view_principal.view_dashboard.graduacoes', compact(
            'modalidades',
            'modalidadeSelecionada',
            'faixaSelecionada',
            'porGraduacao',
            'alunosDaFaixa'
        ));
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $anoSelecionado = $request->get('ano', Carbon::now()->year);

        $anosDisponiveis = DB::table('matricula')
            ->select(DB::raw("DISTINCT YEAR(matri_data) as ano"))
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('ano', 'desc')
            ->pluck('ano');

        // Matrículas CADASTRADAS por mês (evento de criação, independente do status atual)
        $matriculasPorMes = DB::table('matricula')
            ->select(DB::raw("MONTH(matri_data) as mes"), DB::raw("COUNT(*) as total"))
            ->whereYear('matri_data', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $dados = [];
        for ($m = 1; $m <= 12; $m++) {
            $dados[] = $matriculasPorMes[$m] ?? 0;
        }

        // Matrículas ENCERRADAS por mês (evento de encerramento, pela data real)
        $matriculasPorMesEncerradas = DB::table('matricula')
            ->select(DB::raw("MONTH(matri_data_encerramento) as mes"), DB::raw("COUNT(*) as total"))
            ->whereYear('matri_data_encerramento', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotNull('matri_data_encerramento')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $dadosEncerrados = [];
        for ($m = 1; $m <= 12; $m++) {
            $dadosEncerrados[] = $matriculasPorMesEncerradas[$m] ?? 0;
        }

        // Matrículas PAUSADAS por mês (evento de pausa, pela data real)
        $matriculasPorMesPausadas = DB::table('matricula')
            ->select(DB::raw("MONTH(matri_data_pausa) as mes"), DB::raw("COUNT(*) as total"))
            ->whereYear('matri_data_pausa', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotNull('matri_data_pausa')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $dadosPausados = [];
        for ($m = 1; $m <= 12; $m++) {
            $dadosPausados[] = $matriculasPorMesPausadas[$m] ?? 0;
        }

        // Total de matrículas ativas (foto do momento atual)
        $totalMatriculasAtivas = DB::table('matricula')
            ->where('id_emp_id', $user->id_emp_id)
            ->where('matri_status', 'Matriculado')
            ->count();

        // Alunos com ao menos uma matrícula Matriculado ou Pausada
        $totalAlunosMatriculados = DB::table('aluno')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereIn('id_aluno', function ($query) use ($user) {
                $query->select('aluno_id_aluno')
                    ->from('matricula')
                    ->where('id_emp_id', $user->id_emp_id)
                    ->whereIn('matri_status', ['Matriculado', 'Pausada']);
            })
            ->count();

        // Alunos sem nenhuma matrícula Matriculado ou Pausada
        $totalAlunosNaoMatriculados = DB::table('aluno')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotIn('id_aluno', function ($query) use ($user) {
                $query->select('aluno_id_aluno')
                    ->from('matricula')
                    ->where('id_emp_id', $user->id_emp_id)
                    ->whereIn('matri_status', ['Matriculado', 'Pausada']);
            })
            ->count();

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

        // Atualiza atrasados apenas da empresa
        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->update(['det_mensa_status' => 'Atrasado']);

        $mensalidadesAtrasadas = DetalhesMensalidade::where('det_mensa_status', 'Atrasado')
            ->where('id_emp_id', $user->id_emp_id)
            ->count();

        return view('view_admin_user.dashboard', [
            'graficoLabels'          => $labels,
            'graficoDados'           => $dados,
            'graficoDadosEncerrados' => $dadosEncerrados,
            'graficoDadosPausados'   => $dadosPausados,
            'anosDisponiveis'        => $anosDisponiveis,
            'anoSelecionado'         => $anoSelecionado,
            'totalMatriculasAtivas'  => $totalMatriculasAtivas,
            'totalAlunosMatriculados'    => $totalAlunosMatriculados,
            'totalAlunosNaoMatriculados' => $totalAlunosNaoMatriculados,
            'totalBolsista'          => $totalBolsista,
            'receitaMensal'          => $receitaMensal,
            'receitaMensalPago'      => $receitaMensalPago,
            'mensalidadesAtrasadas'  => $mensalidadesAtrasadas,
        ]);
    }
}
