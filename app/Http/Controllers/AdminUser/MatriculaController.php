<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\GradeHorario;
use App\Models\Matricula;
use Illuminate\Http\Request;
use App\Models\Mensalidade;
use App\Models\Modalidade;
use App\Models\PrecoModalidade;
use App\Models\DetalhesMensalidade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Contrato;
use App\Models\DiaVencimento;

class MatriculaController extends Controller
{
    public function index($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $diasVencimento = DiaVencimento::where('id_emp_id', $user->id_emp_id)
            ->orderBy('dia_vencimento')
            ->get();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $matriculas = Matricula::with(['grade.professor'])
            ->where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $grades = GradeHorario::with('professor')
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('grade_turma')
            ->get();

        $precos = PrecoModalidade::with('modalidade')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $contrato = Contrato::with('detalheAtual')
            ->where('empresa_id', $user->id_emp_id)
            ->latest('id_contrato')
            ->first();

        $quantidadeMatriculas = Matricula::where('id_emp_id', $user->id_emp_id)
            ->whereIn('matri_status', ['Matriculado', 'Pausada'])
            ->count();

        $contagemMatriculas = $quantidadeMatriculas;

        $limiteMatriculas = $contrato?->detalheAtual?->det_plano_quantidade;

        $planoFinanceiro = $contrato?->detalheAtual?->det_plano_tipo === 'financeiro';

        $limitePermitido = !$planoFinanceiro && $limiteMatriculas !== null
            ? (int) $limiteMatriculas + 3
            : null;

        $limiteAtingido = $limitePermitido !== null
            && $quantidadeMatriculas >= $limitePermitido;

        return view(
            'view_admin_user.view_principal.view_matricula.index',
            compact(
                'aluno',
                'matriculas',
                'grades',
                'precos',
                'diasVencimento',
                'contrato',
                'quantidadeMatriculas',
                'contagemMatriculas',
                'limiteMatriculas',
                'limitePermitido',
                'limiteAtingido',
                'planoFinanceiro'
            )
        );
    }

    public function pausar(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $matricula = Matricula::where('id_matricula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'matri_motivo'      => 'required|string|max:500',
            'matri_data_evento' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . $matricula->matri_data,
            ],
        ], [
            'matri_motivo.required'             => 'Informe o motivo da pausa.',
            'matri_data_evento.required'        => 'Informe a data da pausa.',
            'matri_data_evento.date'            => 'Informe uma data válida.',
            'matri_data_evento.before_or_equal' => 'A data da pausa não pode ser futura.',
            'matri_data_evento.after_or_equal'  => 'A data da pausa não pode ser anterior à data da matrícula.',
        ]);

        $matricula->update([
            'matri_status'     => 'Pausada',
            'matri_motivo'     => $request->matri_motivo,
            'matri_data_pausa' => $request->matri_data_evento,
        ]);

        return redirect()
            ->route('matricula', Crypt::encrypt($matricula->aluno_id_aluno))
            ->with('success', 'Matrícula pausada com sucesso!');
    }

    public function reativar($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $matricula = Matricula::where('id_matricula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $modalidadeAtual = $matricula->grade->grade_modalidade ?? null;

        if ($modalidadeAtual) {
            $conflito = Matricula::where('aluno_id_aluno', $matricula->aluno_id_aluno)
                ->where('id_emp_id', $user->id_emp_id)
                ->where('matri_status', 'Matriculado')
                ->where('id_matricula', '!=', $matricula->id_matricula)
                ->whereHas('grade', function ($q) use ($modalidadeAtual) {
                    $q->where('grade_modalidade', $modalidadeAtual);
                })
                ->exists();

            if ($conflito) {
                return redirect()
                    ->route('matricula', Crypt::encrypt($matricula->aluno_id_aluno))
                    ->withErrors(['erro' => 'Não é possível reativar: o aluno já possui uma matrícula ativa na modalidade "' . $modalidadeAtual . '". Encerre a outra antes de reativar esta.']);
            }
        }

        $matricula->update([
            'matri_status' => 'Matriculado',
            'matri_motivo' => null,
            // matri_data_pausa é mantida propositalmente como histórico do evento de pausa
            // (o gráfico usa essa data pra contar "quantas pausas ocorreram naquele mês",
            // isso não deve ser apagado numa reativação)
        ]);

        return redirect()
            ->route('matricula', Crypt::encrypt($matricula->aluno_id_aluno))
            ->with('success', 'Matrícula reativada com sucesso!');
    }

    public function encerrarMatricula(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $matricula = Matricula::where('id_matricula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        // se já foi pausada em algum momento, o encerramento não pode ser anterior a essa pausa;
        // senão, não pode ser anterior à própria matrícula
        $dataMinima = $matricula->matri_data_pausa ?? $matricula->matri_data;

        $request->validate([
            'matri_motivo'      => 'required|string|max:500',
            'matri_data_evento' => [
                'required',
                'date',
                'after_or_equal:' . $dataMinima,
            ],
        ], [
            'matri_motivo.required'       => 'Informe o motivo do encerramento.',
            'matri_data_evento.required'  => 'Informe a data do encerramento.',
            'matri_data_evento.date'      => 'Informe uma data válida.',
            'matri_data_evento.after_or_equal' => 'A data do encerramento não pode ser anterior à última pausa/matrícula.',
        ]);

        $matricula->update([
            'matri_status'            => 'Encerrada',
            'matri_motivo'            => $request->matri_motivo,
            'matri_data_encerramento' => $request->matri_data_evento,
        ]);

        return redirect()
            ->route('matricula', Crypt::encrypt($matricula->aluno_id_aluno))
            ->with('success', 'Matrícula encerrada com sucesso!');
    }

    public function store(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $contrato = Contrato::with('detalheAtual')
            ->where('empresa_id', $user->id_emp_id)
            ->latest('id_contrato')
            ->first();

        if ($contrato && $contrato->detalheAtual) {
            $detalhePlano = $contrato->detalheAtual;

            if ($detalhePlano->det_plano_tipo !== 'financeiro') {
                $limiteMatriculas = (int) $detalhePlano->det_plano_quantidade;

                $quantidadeMatriculas = Matricula::where('id_emp_id', $user->id_emp_id)
                    ->whereIn('matri_status', ['Matriculado', 'Pausada'])
                    ->count();

                $limitePermitido = $limiteMatriculas + 3;

                if ($limiteMatriculas > 0 && $quantidadeMatriculas >= $limitePermitido) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'erro' => "O limite permitido de {$limitePermitido} matrículas foi atingido. Não é possível cadastrar uma nova matrícula."
                        ]);
                }
            }
        }

        $diasPermitidos = DiaVencimento::where('id_emp_id', $user->id_emp_id)
            ->pluck('dia_vencimento')
            ->toArray();

        if (empty($diasPermitidos)) {
            return back()
                ->withInput()
                ->withErrors([
                    'erro' => 'Nenhum dia de vencimento está cadastrado. Cadastre pelo menos um dia antes de realizar a matrícula.'
                ]);
        }

        $request->validate([
            'matri_data' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . $aluno->aluno_nascimento,
            ],
            'matri_desc'           => 'required|string|max:150',
            'matri_categoria'      => 'required|string|max:120',
            'matri_plano'          => 'required|string|max:40',
            'matri_dia_vencimento' => ['required', 'integer', 'in:' . implode(',', $diasPermitidos)],
            'grade_id_grade'       => 'required|exists:grade_horario,id_grade',
        ], [
            'matri_data.required'             => 'A data da matrícula é obrigatória.',
            'matri_data.date'                 => 'Informe uma data válida.',
            'matri_data.before_or_equal'      => 'Não é possível informar uma data futura.',
            'matri_data.after_or_equal'       => 'A data não pode ser anterior ao nascimento do aluno.',
            'matri_categoria.required'        => 'Selecione uma categoria.',
            'matri_plano.required'            => 'Selecione um plano.',
        ]);

        // VERIFICA DUPLICIDADE POR MODALIDADE
        $gradeNova = GradeHorario::where('id_grade', $request->grade_id_grade)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $jaExiste = Matricula::where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('matri_status', 'Matriculado')
            ->whereHas('grade', function ($q) use ($gradeNova) {
                $q->where('grade_modalidade', $gradeNova->grade_modalidade);
            })
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'grade_id_grade' => 'Erro: aluno já possui uma matrícula ativa na modalidade "' . $gradeNova->grade_modalidade . '". Encerre ou pause antes de cadastrar uma nova.'
            ])->withInput();
        }

        DB::beginTransaction();

        try {
            $matricula = Matricula::create([
                'aluno_id_aluno'       => $id,
                'matri_status'         => 'Matriculado',
                'matri_data'           => $request->matri_data,
                'matri_plano'          => $request->matri_plano,
                'matri_categoria'      => $request->matri_categoria,
                'matri_dia_vencimento' => $request->matri_dia_vencimento,
                'grade_id_grade'       => $request->grade_id_grade,
                'matri_desc'           => $request->matri_desc,
                'id_emp_id'            => $user->id_emp_id
            ]);

            if (strtolower($aluno->aluno_bolsista) !== 'sim') {
                $diaVencimento = (int) $request->matri_dia_vencimento;

                $grade = GradeHorario::where('id_grade', $request->grade_id_grade)
                    ->where('id_emp_id', $user->id_emp_id)
                    ->firstOrFail();

                $modalidade = Modalidade::where('mod_nome', $grade->grade_modalidade)
                    ->where('id_emp_id', $user->id_emp_id)
                    ->first();

                if (!$modalidade) {
                    throw new \Exception('Modalidade não encontrada.');
                }

                $preco = PrecoModalidade::where('preco_plano', $request->matri_plano)
                    ->where('preco_categoria', $request->matri_categoria)
                    ->where('modalidade_id', $modalidade->id_modalidade)
                    ->where('id_emp_id', $user->id_emp_id)
                    ->first();

                if (!$preco) {
                    throw new \Exception(
                        'Preço não encontrado para essa modalidade, categoria e plano.'
                    );
                }

                $mensalidade = Mensalidade::create([
                    'aluno_id_aluno'         => $id,
                    'matricula_id_matricula' => $matricula->id_matricula,
                    'mensa_dia_venc'         => $diaVencimento,
                    'mensa_valor'            => $preco->preco_modalidade,
                    'id_emp_id'              => $user->id_emp_id
                ]);

                $quantidadeParcelas = match (strtolower($request->matri_plano)) {
                    'trimestral' => 3,
                    'semestral'  => 6,
                    'anual'      => 12,
                    default      => 12,
                };

                /*
 * Entrada
 * A data da entrada deve ser a própria data da matrícula.
 */
                $dataMatricula = Carbon::parse($request->matri_data);

                DetalhesMensalidade::create([
                    'mensalidade_id_mensalidade' => $mensalidade->id_mensalidade,
                    'det_mensa_forma_pagamento'  => 'Pix',
                    'det_mensa_mes_vigente'      => 'Entrada',
                    'det_mensa_data_venc'        => $dataMatricula->toDateString(),
                    'det_mensa_valor'             => number_format($preco->preco_modalidade, 2, '.', ''),
                    'det_mensa_data_pagamento'   => null,
                    'det_mensa_status'            => 'Em aberto',
                    'id_emp_id'                  => $user->id_emp_id
                ]);

                /*
 * Primeiro vencimento
 * A referência é a data da matrícula.
 *
 * Exemplo:
 * Matrícula: 10/09
 * Vencimento: dia 20
 * Primeiro vencimento: 20/09
 *
 * Matrícula: 25/09
 * Vencimento: dia 20
 * Primeiro vencimento: 20/10
 */
                $primeiraData = $dataMatricula->copy()->startOfMonth();

                $primeiraData->setDay(
                    min($diaVencimento, $primeiraData->daysInMonth)
                );

                if ($primeiraData->lessThanOrEqualTo($dataMatricula)) {
                    $primeiraData->addMonthNoOverflow();

                    $primeiraData->setDay(
                        min($diaVencimento, $primeiraData->daysInMonth)
                    );
                }

                for ($i = 0; $i < $quantidadeParcelas; $i++) {
                    $dataVencimento = $primeiraData->copy()->addMonthsNoOverflow($i);

                    $dataVencimento->setDay(
                        min($diaVencimento, $dataVencimento->daysInMonth)
                    );

                    DetalhesMensalidade::create([
                        'mensalidade_id_mensalidade' => $mensalidade->id_mensalidade,
                        'det_mensa_forma_pagamento'  => 'Pix',
                        'det_mensa_mes_vigente'      => $dataVencimento->translatedFormat('F'),
                        'det_mensa_data_venc'        => $dataVencimento->format('Y-m-d'),
                        'det_mensa_valor'             => number_format($preco->preco_modalidade, 2, '.', ''),
                        'det_mensa_data_pagamento'   => null,
                        'det_mensa_status'            => 'Em aberto',
                        'id_emp_id'                  => $user->id_emp_id
                    ]);
                }
            }

            DB::commit();

            if (strtolower($aluno->aluno_bolsista) === 'sim') {
                return redirect()
                    ->route('matricula', Crypt::encrypt($id))
                    ->with('success', 'Matrícula realizada com sucesso! (Aluno bolsista - sem geração de financeiro)');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('matricula', Crypt::encrypt($id))
            ->with('success', 'Matrícula realizada com sucesso!');
    }

    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $matricula = Matricula::with(['aluno', 'grade.professor'])
            ->where('id_matricula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin_user.view_principal.view_matricula.show', compact('matricula'));
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $matricula = Matricula::where('id_matricula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        // Só permite excluir matrícula encerrada
        if ($matricula->matri_status !== 'Encerrada') {
            return redirect()
                ->route('matricula', Crypt::encrypt($matricula->aluno_id_aluno))
                ->withErrors([
                    'erro' => 'Só é possível excluir matrículas encerradas.'
                ]);
        }

        $mensalidadeAtrasada = Mensalidade::where(
            'matricula_id_matricula',
            $matricula->id_matricula
        )
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('detalhes', function ($query) {
                $query->where('det_mensa_status', 'Atrasado');
            })
            ->exists();

        if ($mensalidadeAtrasada) {
            return redirect()
                ->route('matricula', Crypt::encrypt($matricula->aluno_id_aluno))
                ->withErrors([
                    'erro' => 'Não é possível excluir esta matrícula porque ela possui mensalidade(s) atrasada(s). Regularize as pendências financeiras antes de excluir.'
                ]);
        }

        $alunoId = $matricula->aluno_id_aluno;

        $mensalidades = Mensalidade::where(
            'matricula_id_matricula',
            $matricula->id_matricula
        )
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        foreach ($mensalidades as $mensalidade) {
            $mensalidade->detalhes()->delete();
            $mensalidade->delete();
        }

        $matricula->delete();

        return redirect()
            ->route('matricula', Crypt::encrypt($alunoId))
            ->with('success', 'Matrícula removida com sucesso!');
    }
}
