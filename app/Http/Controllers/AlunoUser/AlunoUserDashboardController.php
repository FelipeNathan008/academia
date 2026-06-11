<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;

use App\Models\Aluno;
use App\Models\DetalhesMensalidade;
use App\Models\Mensalidade;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\FrequenciaAluno;
use App\Models\Matricula;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;

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

        $alunos = Aluno::where(
            'responsavel_id_responsavel',
            $responsavel->id_responsavel
        )->get();

        $alunoSelecionado = request('aluno_id');
        $matriculaSelecionada = null;
        if (!$alunoSelecionado) {
            $matriculaSelecionada = null;
        }
        if (request('matricula_id')) {

            try {

                $matriculaSelecionada = decrypt(
                    request('matricula_id')
                );
            } catch (\Exception $e) {

                $matriculaSelecionada = null;
            }
        }

        if ($matriculaSelecionada) {

            $matriculaValida = Matricula::where(
                'id_matricula',
                $matriculaSelecionada
            )
                ->where(
                    'aluno_id_aluno',
                    $alunoSelecionado
                )
                ->exists();

            if (!$matriculaValida) {

                $matriculaSelecionada = null;

                $presencas = 0;
                $faltas = 0;
            }
        }
        $matriculas = collect();

        $presencas = 0;
        $faltas = 0;

        $meta = 0;
        $barraMeta = 0;

        if ($alunoSelecionado) {

            $matriculas = Matricula::with('grade')
                ->where('aluno_id_aluno', $alunoSelecionado)
                ->where('matri_status', '!=', 'Encerrada')
                ->get();
        }

        if ($matriculaSelecionada) {

            $matricula = Matricula::with('grade', 'aluno')
                ->find($matriculaSelecionada);

            $presencas = FrequenciaAluno::where(
                'matricula_id_matricula',
                $matriculaSelecionada
            )
                ->where('freq_presenca', 'Presente')
                ->count();

            $faltas = FrequenciaAluno::where(
                'matricula_id_matricula',
                $matriculaSelecionada
            )
                ->where('freq_presenca', 'Falta')
                ->count();

            $detalhes = DetalhesAluno::where(
                'aluno_id_aluno',
                $matricula->aluno_id_aluno
            )
                ->where(
                    'det_modalidade',
                    $matricula->grade->grade_modalidade
                )
                ->ordenarPorFaixaInverso()
                ->orderByDesc('det_grau')
                ->first();

            if ($detalhes) {

                $graduacao = Graduacao::where(
                    'gradu_nome_cor',
                    $detalhes->det_gradu_nome_cor
                )
                    ->where(
                        'gradu_grau',
                        $detalhes->det_grau
                    )
                    ->first();

                $meta = $graduacao->gradu_meta ?? 0;
            }

            $barraMeta = $meta > 0
                ? min(100, round(($presencas / $meta) * 100))
                : 0;
        }

        $totalFrequencias = $presencas + $faltas;

        $percentualPresenca = $totalFrequencias > 0
            ? round(($presencas * 100) / $totalFrequencias, 1)
            : 0;


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
            'mensalidadesAtrasadas',
            'alunos',
            'alunoSelecionado',
            'presencas',
            'faltas',
            'percentualPresenca',
            'matriculas',
            'matriculaSelecionada',
            'meta',
            'barraMeta',
        ));
    }
    public function mensalidadesAtrasadas()
    {
        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $alunosIds = Aluno::where(
            'responsavel_id_responsavel',
            $responsavel->id_responsavel
        )->pluck('id_aluno');

        DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->whereHas('mensalidade.matricula', function ($q) use ($alunosIds) {
                $q->whereIn('aluno_id_aluno', $alunosIds);
            })
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
            ->whereHas('matricula', function ($query) use ($alunosIds) {
                $query->whereIn('aluno_id_aluno', $alunosIds);
            })
            ->get();

        $mensalidadesAtrasadas = DetalhesMensalidade::where('det_mensa_status', 'Atrasado')
            ->whereHas('mensalidade.matricula', function ($q) use ($alunosIds) {
                $q->whereIn('aluno_id_aluno', $alunosIds);
            })
            ->count();

        return view(
            'view_aluno_user.dashboard.mensalidades_atrasadas',
            compact('mensalidades', 'mensalidadesAtrasadas')
        );
    }
}
