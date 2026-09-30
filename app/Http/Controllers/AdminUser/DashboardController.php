<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Mensalidade;
use App\Models\Contrato;
use App\Models\DetalhesMensalidade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PagamentoContratoService;

class DashboardController extends Controller
{
    public function mensalidadesAtrasadas()
    {
        $user = Auth::user();

        DetalhesMensalidade::where('id_emp_id', $user->id_emp_id)
            ->where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
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
            ->get();

        $mensalidadesAtrasadas = DetalhesMensalidade::where('id_emp_id', $user->id_emp_id)
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->whereIn('det_mensa_status', [
                'Em aberto',
                'Atrasado',
                'Comprovante enviado',
                'Rejeitado'
            ])
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
        $empresa = Empresa::findOrFail($user->id_emp_id);
        $contrato = Contrato::with('detalheAtual.plano')
            ->where('empresa_id', $empresa->id_empresa)
            ->latest('id_contrato')
            ->first();

        $alertaFinanceiro = null;

        if ($contrato) {

            $pagamentoService = new PagamentoContratoService();

            $alertaFinanceiro = $pagamentoService
                ->atualizarStatusEMensagem($contrato);
        }

        $anoSelecionado = $request->get('ano', Carbon::now()->year);

        $anosDisponiveis = DB::table('matricula')
            ->select(DB::raw("DISTINCT YEAR(matri_data) as ano"))
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('ano', 'desc')
            ->pluck('ano');

        // Matrículas CADASTRADAS por mês
        $matriculasPorMes = DB::table('matricula')
            ->select(
                DB::raw("MONTH(matri_data) as mes"),
                DB::raw("COUNT(*) as total")
            )
            ->whereYear('matri_data', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        $dados = [];

        for ($m = 1; $m <= 12; $m++) {
            $dados[] = $matriculasPorMes[$m] ?? 0;
        }

        // Matrículas ENCERRADAS por mês
        $matriculasPorMesEncerradas = DB::table('matricula')
            ->select(
                DB::raw("MONTH(matri_data_encerramento) as mes"),
                DB::raw("COUNT(*) as total")
            )
            ->whereYear('matri_data_encerramento', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotNull('matri_data_encerramento')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $dadosEncerrados = [];

        for ($m = 1; $m <= 12; $m++) {
            $dadosEncerrados[] = $matriculasPorMesEncerradas[$m] ?? 0;
        }

        // Matrículas PAUSADAS por mês
        $matriculasPorMesPausadas = DB::table('matricula')
            ->select(
                DB::raw("MONTH(matri_data_pausa) as mes"),
                DB::raw("COUNT(*) as total")
            )
            ->whereYear('matri_data_pausa', $anoSelecionado)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereNotNull('matri_data_pausa')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $dadosPausados = [];

        for ($m = 1; $m <= 12; $m++) {
            $dadosPausados[] = $matriculasPorMesPausadas[$m] ?? 0;
        }

        // Total de matrículas ativas
        $totalMatriculasAtivas = DB::table('matricula')
            ->where('id_emp_id', $user->id_emp_id)
            ->where('matri_status', 'Matriculado')
            ->count();

        // Total de matrículas ativas e pausadas
        $contagemMatriculas = DB::table('matricula')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereIn('matri_status', ['Matriculado', 'Pausada'])
            ->count();

        // Alunos com ao menos uma matrícula ativa ou pausada
        $totalAlunosMatriculados = DB::table('aluno')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereIn('id_aluno', function ($query) use ($user) {
                $query->select('aluno_id_aluno')
                    ->from('matricula')
                    ->where('id_emp_id', $user->id_emp_id)
                    ->whereIn('matri_status', ['Matriculado', 'Pausada']);
            })
            ->count();

        // Alunos sem nenhuma matrícula ativa ou pausada
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

        $receitaMensal = DetalhesMensalidade::whereMonth(
            'det_mensa_data_venc',
            Carbon::now()->month
        )
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->where('id_emp_id', $user->id_emp_id)
            ->sum('det_mensa_valor');

        $receitaMensalPago = DetalhesMensalidade::whereMonth(
            'det_mensa_data_venc',
            Carbon::now()->month
        )
            ->where('det_mensa_status', 'Pago')
            ->whereYear('det_mensa_data_venc', Carbon::now()->year)
            ->where('id_emp_id', $user->id_emp_id)
            ->sum('det_mensa_valor');

        // Atualiza atrasados das mensalidades normais
        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $mensalidadesAtrasadas = DetalhesMensalidade::where(
            'id_emp_id',
            $user->id_emp_id
        )
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->whereIn('det_mensa_status', [
                'Em aberto',
                'Atrasado',
                'Comprovante enviado',
                'Rejeitado'
            ])
            ->count();

        $comprovantesPendentes = DetalhesMensalidade::where(
            'det_mensa_status',
            'Comprovante enviado'
        )
            ->where('id_emp_id', $user->id_emp_id)
            ->count();

        return view('view_admin_user.dashboard', [
            'empresa'                    => $empresa,
            'contrato'                   => $contrato,
            'alertaFinanceiro'           => $alertaFinanceiro,
            'graficoLabels'              => $labels,
            'graficoDados'               => $dados,
            'graficoDadosEncerrados'     => $dadosEncerrados,
            'graficoDadosPausados'       => $dadosPausados,
            'anosDisponiveis'            => $anosDisponiveis,
            'anoSelecionado'             => $anoSelecionado,
            'totalMatriculasAtivas'      => $totalMatriculasAtivas,
            'contagemMatriculas'         => $contagemMatriculas,
            'totalAlunosMatriculados'    => $totalAlunosMatriculados,
            'totalAlunosNaoMatriculados' => $totalAlunosNaoMatriculados,
            'totalBolsista'              => $totalBolsista,
            'receitaMensal'              => $receitaMensal,
            'receitaMensalPago'          => $receitaMensalPago,
            'mensalidadesAtrasadas'      => $mensalidadesAtrasadas,
            'comprovantesPendentes'      => $comprovantesPendentes,
        ]);
    }

    public function mapaFinanceiro(Request $request)
    {
        $user = Auth::user();

        $mes = (int) $request->get('mes', Carbon::now()->month);
        $ano = (int) $request->get('ano', Carbon::now()->year);

        if ($mes < 1 || $mes > 12) {
            $mes = Carbon::now()->month;
        }

        if ($ano < 2000 || $ano > 2100) {
            $ano = Carbon::now()->year;
        }

        $inicioMes = Carbon::create($ano, $mes, 1)->startOfMonth();
        $fimMes = $inicioMes->copy()->endOfMonth();

        $statusPendentes = [
            'Em aberto',
            'Atrasado',
            'Comprovante enviado',
            'Rejeitado',
        ];

        $detalhes = DetalhesMensalidade::with([
            'mensalidade.matricula.aluno',
            'mensalidade.matricula.grade.professor',
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->whereBetween('det_mensa_data_venc', [
                $inicioMes->toDateString(),
                $fimMes->toDateString()
            ])
            ->whereIn('det_mensa_status', $statusPendentes)
            ->whereHas('mensalidade.matricula', function ($query) {
                $query->where('matri_status', '!=', 'Encerrada');
            })
            ->orderBy('det_mensa_data_venc')
            ->get();

        $recebimentosPorDia = $detalhes
            ->groupBy(function ($detalhe) {
                return Carbon::parse($detalhe->det_mensa_data_venc)->day;
            })
            ->map(function ($itens) {
                $alunos = $itens
                    ->map(function ($detalhe) {
                        return optional($detalhe->mensalidade)->aluno_id_aluno;
                    })
                    ->filter()
                    ->unique();

                return [
                    'quantidade_alunos' => $alunos->count(),
                    'valor' => $itens->sum(function ($detalhe) {
                        return (float) $detalhe->det_mensa_valor;
                    }),
                    'detalhes' => $itens,
                ];
            });

        $totalAReceber = $detalhes->sum(function ($detalhe) {
            return (float) $detalhe->det_mensa_valor;
        });

        $totalAlunos = $detalhes
            ->map(function ($detalhe) {
                return optional($detalhe->mensalidade)->aluno_id_aluno;
            })
            ->filter()
            ->unique()
            ->count();

        $diasComRecebimentos = $recebimentosPorDia->count();

        $hoje = Carbon::today();

        if ($mes === $hoje->month && $ano === $hoje->year) {
            $proximo = $recebimentosPorDia
                ->filter(function ($dados, $dia) use ($hoje) {
                    $data = Carbon::create($hoje->year, $hoje->month, $dia);
                    return $data->gte($hoje);
                })
                ->sortKeys()
                ->first();

            $diaProximo = $recebimentosPorDia
                ->filter(function ($dados, $dia) use ($hoje) {
                    $data = Carbon::create($hoje->year, $hoje->month, $dia);
                    return $data->gte($hoje);
                })
                ->sortKeys()
                ->keys()
                ->first();
        } else {
            $diaProximo = $recebimentosPorDia
                ->sortKeys()
                ->keys()
                ->first();

            $proximo = $diaProximo
                ? $recebimentosPorDia[$diaProximo]
                : null;
        }

        $dataProximo = $diaProximo
            ? Carbon::create($ano, $mes, $diaProximo)
            : null;

        $nomeMes = $inicioMes->translatedFormat('F');

        $dias = collect();

        for ($dia = 1; $dia <= $inicioMes->daysInMonth; $dia++) {
            $dados = $recebimentosPorDia->get($dia, [
                'quantidade_alunos' => 0,
                'valor' => 0,
                'detalhes' => collect(),
            ]);

            $dias->push([
                'dia' => $dia,
                'data' => Carbon::create($ano, $mes, $dia),
                'quantidade_alunos' => $dados['quantidade_alunos'],
                'valor' => $dados['valor'],
                'detalhes' => $dados['detalhes'],
            ]);
        }

        return view(
            'view_admin_user.view_principal.view_dashboard.mapa_financeiro',
            compact(
                'mes',
                'ano',
                'nomeMes',
                'inicioMes',
                'fimMes',
                'dias',
                'totalAReceber',
                'totalAlunos',
                'diasComRecebimentos',
                'proximo',
                'dataProximo'
            )
        );
    }

    public function listaMapaFinanceiro(Request $request)
    {
        $user = Auth::user();

        $mes = (int) $request->get('mes');
        $ano = (int) $request->get('ano');
        $dia = (int) $request->get('dia');

        // Validações
        if ($mes < 1 || $mes > 12) {
            abort(404);
        }

        if ($ano < 2000 || $ano > 2100) {
            abort(404);
        }

        if ($dia < 1 || $dia > 31) {
            abort(404);
        }

        $dataVencimento = Carbon::create($ano, $mes, $dia);

        // Garante que o dia realmente existe naquele mês
        if ($dataVencimento->month !== $mes || $dataVencimento->year !== $ano) {
            abort(404);
        }

        $detalhes = DetalhesMensalidade::with([
            'mensalidade.matricula.aluno',
            'mensalidade.matricula.grade.professor',
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->whereDate('det_mensa_data_venc', $dataVencimento->toDateString())
            ->whereIn('det_mensa_status', [
                'Em aberto',
                'Atrasado',
                'Comprovante enviado',
                'Rejeitado',
            ])
            ->whereHas('mensalidade.matricula', function ($query) {
                $query->where('matri_status', '!=', 'Encerrada');
            })
            ->orderBy('det_mensa_data_venc')
            ->get();

        $nomeMes = $dataVencimento->translatedFormat('F');

        return view(
            'view_admin_user.view_principal.view_dashboard.lista_mapa_financeiro',
            compact(
                'detalhes',
                'dataVencimento',
                'mes',
                'ano',
                'dia',
                'nomeMes'
            )
        );
    }

    public function comprovantesPendentes()
    {
        $user = Auth::user();

        $comprovantes = DetalhesMensalidade::with([
            'mensalidade.matricula.aluno'
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->where('det_mensa_status', 'Comprovante enviado')
            ->orderByDesc('det_mensa_comprovante_enviado_em')
            ->get();

        return view(
            'view_admin_user.view_principal.view_dashboard.comprovantes_pendentes',
            compact('comprovantes')
        );
    }
}
