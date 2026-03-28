<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Mensalidade;
use App\Models\DetalhesAluno;
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

        return view('view_dashboard.mensalidades_atrasadas', compact('mensalidades', 'mensalidadesAtrasadas'));
    }



    public function graduacoes()
    {
        $user = Auth::user();
        $modalidadeFiltro = request()->query('modalidade');
        $faixaFiltro = request()->query('faixa');

        $alunos = Aluno::with(['responsavel', 'detalhes'])
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $modalidades = DetalhesAluno::select('det_modalidade')
            ->where('id_emp_id', $user->id_emp_id)
            ->distinct()
            ->pluck('det_modalidade');

        $faixas = DetalhesAluno::select('det_gradu_nome_cor')
            ->where('id_emp_id', $user->id_emp_id)
            ->distinct()
            ->pluck('det_gradu_nome_cor');

        $alunosFiltrados = collect();

        foreach ($alunos as $aluno) {

            $graduacoes = $aluno->detalhes
                ->sortBy(function ($d) {
                    return match (strtolower($d->det_gradu_nome_cor)) {

                        'cinza e branca' => 1,
                        'cinza' => 2,
                        'cinza e preta' => 3,

                        'amarela e branca' => 4,
                        'amarela' => 5,
                        'amarela e preta' => 6,

                        'laranja e branca' => 7,
                        'laranja' => 8,
                        'laranja e preta' => 9,

                        'verde e branca' => 10,
                        'verde' => 11,
                        'verde e preta' => 12,

                        'branca' => 13,
                        'azul' => 14,
                        'roxa' => 15,
                        'marrom' => 16,
                        'preta' => 17,

                        default => 99,
                    };
                })
                ->groupBy('det_modalidade')
                ->map(function ($grupo) {
                    return $grupo->last();
                });

            // filtro por modalidade
            if ($modalidadeFiltro) {
                $graduacoes = $graduacoes->filter(function ($valor, $chave) use ($modalidadeFiltro) {
                    return $chave == $modalidadeFiltro;
                });
            }

            // filtro por faixa
            if ($faixaFiltro) {
                $graduacoes = $graduacoes->filter(function ($graduacao) use ($faixaFiltro) {
                    return strtolower($graduacao->det_gradu_nome_cor) == strtolower($faixaFiltro);
                });
            }

            if (($modalidadeFiltro || $faixaFiltro) && $graduacoes->isNotEmpty()) {
                $aluno->graduacoes = $graduacoes;
                $alunosFiltrados->push($aluno);
            } elseif (!$modalidadeFiltro && !$faixaFiltro) {
                $aluno->graduacoes = $graduacoes;
                $alunosFiltrados->push($aluno);
            }
        }

        return view('view_dashboard.graduacoes', [
            'alunos' => $alunosFiltrados,
            'modalidades' => $modalidades,
            'faixas' => $faixas,
            'modalidadeFiltro' => $modalidadeFiltro,
            'faixaFiltro' => $faixaFiltro
        ]);
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

        $modalidadeSelecionada = $request->get('modalidade');

        // Buscar todas as modalidades existentes
        $modalidades = DetalhesAluno::select('det_modalidade')
            ->where('id_emp_id', $user->id_emp_id)
            ->distinct()
            ->pluck('det_modalidade');

        $queryDetalhes = DetalhesAluno::where('id_emp_id', $user->id_emp_id);

        if ($modalidadeSelecionada) {
            $queryDetalhes->where('det_modalidade', $modalidadeSelecionada);
        }

        $subQuery = $queryDetalhes->select(
            'aluno_id_aluno',
            DB::raw("MAX(
            CASE
                WHEN LOWER(det_gradu_nome_cor) = 'cinza e branca' THEN 1
                WHEN LOWER(det_gradu_nome_cor) = 'cinza' THEN 2
                WHEN LOWER(det_gradu_nome_cor) = 'cinza e preta' THEN 3
                WHEN LOWER(det_gradu_nome_cor) = 'amarela e branca' THEN 4
                WHEN LOWER(det_gradu_nome_cor) = 'amarela' THEN 5
                WHEN LOWER(det_gradu_nome_cor) = 'amarela e preta' THEN 6
                WHEN LOWER(det_gradu_nome_cor) = 'laranja e branca' THEN 7
                WHEN LOWER(det_gradu_nome_cor) = 'laranja' THEN 8
                WHEN LOWER(det_gradu_nome_cor) = 'laranja e preta' THEN 9
                WHEN LOWER(det_gradu_nome_cor) = 'verde e branca' THEN 10
                WHEN LOWER(det_gradu_nome_cor) = 'verde' THEN 11
                WHEN LOWER(det_gradu_nome_cor) = 'verde e preta' THEN 12
                WHEN LOWER(det_gradu_nome_cor) = 'branca' THEN 13
                WHEN LOWER(det_gradu_nome_cor) = 'azul' THEN 14
                WHEN LOWER(det_gradu_nome_cor) = 'roxa' THEN 15
                WHEN LOWER(det_gradu_nome_cor) = 'marrom' THEN 16
                WHEN LOWER(det_gradu_nome_cor) = 'preta' THEN 17
                ELSE 0
            END
        ) as ordem_max
    ")
        )
            ->where('id_emp_id', $user->id_emp_id)
            ->groupBy('aluno_id_aluno');

        $graduacoes = DB::query()
            ->fromSub($subQuery, 'sub')
            ->select('ordem_max', DB::raw('COUNT(*) as total'))
            ->groupBy('ordem_max')
            ->pluck('total', 'ordem_max');

        return view('dashboard', [
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

            'modalidades' => $modalidades,
            'modalidadeSelecionada' => $modalidadeSelecionada,

            'graduacaoCinzaBranca'   => $graduacoes[1] ?? 0,
            'graduacaoCinza'         => $graduacoes[2] ?? 0,
            'graduacaoCinzaPreta'    => $graduacoes[3] ?? 0,

            'graduacaoAmarelaBranca' => $graduacoes[4] ?? 0,
            'graduacaoAmarela'       => $graduacoes[5] ?? 0,
            'graduacaoAmarelaPreta'  => $graduacoes[6] ?? 0,

            'graduacaoLaranjaBranca' => $graduacoes[7] ?? 0,
            'graduacaoLaranja'       => $graduacoes[8] ?? 0,
            'graduacaoLaranjaPreta'  => $graduacoes[9] ?? 0,

            'graduacaoVerdeBranca'   => $graduacoes[10] ?? 0,
            'graduacaoVerde'         => $graduacoes[11] ?? 0,
            'graduacaoVerdePreta'    => $graduacoes[12] ?? 0,

            'graduacaoBranca' => $graduacoes[13] ?? 0,
            'graduacaoAzul'   => $graduacoes[14] ?? 0,
            'graduacaoRoxa'   => $graduacoes[15] ?? 0,
            'graduacaoMarrom' => $graduacoes[16] ?? 0,
            'graduacaoPreta'  => $graduacoes[17] ?? 0,
        ]);
    }
}
