<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Plano;
use App\Models\Empresa;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use App\Models\DetalhesContrato;
use App\Models\MensalidadeContrato;
use Carbon\Carbon;
use App\Services\PagamentoContratoService;

class ContratoController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $planos = Plano::orderBy('plano_valor')->get();

        $contrato = Contrato::with([
            'detalheAtual.plano',
            'detalhes.mensalidades'
        ])
            ->where('empresa_id', $user->id_emp_id)
            ->latest('id_contrato')
            ->first();

        $alertaFinanceiro = null;

        if ($contrato) {

            $pagamentoService = new PagamentoContratoService();

            $alertaFinanceiro = $pagamentoService
                ->atualizarStatusEMensagem($contrato);
        }

        $contagemMatriculas = DB::table('matricula')
            ->where('id_emp_id', $user->id_emp_id)
            ->whereIn('matri_status', ['Matriculado', 'Pausada'])
            ->count();

        return view('view_admin_user.view_contratos.planos', [
            'planos' => $planos,
            'contrato' => $contrato,
            'contagemMatriculas' => $contagemMatriculas,
            'alertaFinanceiro' => $alertaFinanceiro,
        ]);
    }

    private function verificarLimiteMatriculas(Plano $plano, int $idEmpresa): void
    {
        // Plano financeiro não possui limite de matrículas
        if ($plano->plano_tipo === 'financeiro') {
            return;
        }

        $contagemMatriculas = DB::table('matricula')
            ->where('id_emp_id', $idEmpresa)
            ->whereIn('matri_status', ['Matriculado', 'Pausada'])
            ->count();

        if ($contagemMatriculas > $plano->plano_quantidade) {
            abort(
                redirect()
                    ->route('admin.planos')
                    ->with(
                        'error',
                        "Não é possível contratar o plano \"{$plano->plano_nome}\". "
                            . "Sua academia possui {$contagemMatriculas} matrículas "
                            . "e o plano permite no máximo {$plano->plano_quantidade}."
                    )
            );
        }
    }

    private function verificarContratoPodeAlterar(Contrato $contrato): void
    {
        if ($contrato->possuiMensalidadeAtrasada()) {

            abort(
                redirect()
                    ->route('admin.planos')
                    ->with(
                        'error',
                        'Não é possível alterar o plano enquanto existir mensalidade do contrato em atraso.'
                    )
            );
        }
    }

    private function verificarPlanoPodeAlterar(Plano $plano, int $idEmpresa): void
    {
        if ($plano->plano_tipo === 'financeiro') {
            abort(
                redirect()
                    ->route('admin.planos')
                    ->with(
                        'error',
                        'Pacotes adicionais não podem ser utilizados para alteração do plano principal.'
                    )
            );
        }

        $contagemMatriculas = DB::table('matricula')
            ->where('id_emp_id', $idEmpresa)
            ->whereIn('matri_status', ['Matriculado', 'Pausada'])
            ->count();

        // Bloqueia SOMENTE quando as matrículas ultrapassam o limite
        if ($contagemMatriculas > $plano->plano_quantidade) {
            abort(
                redirect()
                    ->route('admin.planos')
                    ->with(
                        'error',
                        "Não é possível alterar para o plano \"{$plano->plano_nome}\". "
                            . "Sua academia possui {$contagemMatriculas} matrículas "
                            . "e o plano permite no máximo {$plano->plano_quantidade}."
                    )
            );
        }
    }

    public function alterarPlano($planoId)
    {
        try {
            $id = Crypt::decrypt($planoId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $plano = Plano::findOrFail($id);

        $contrato = Contrato::with('detalheAtual')
            ->where('empresa_id', $user->id_emp_id)
            ->latest('id_contrato')
            ->first();

        if (!$contrato) {
            return redirect()
                ->route('admin.planos')
                ->with('error', 'Sua empresa ainda não possui um contrato.');
        }

        if (!$contrato->detalheAtual) {
            return redirect()
                ->route('admin.planos')
                ->with('error', 'Não foi possível identificar o plano atual do contrato.');
        }

        $this->verificarContratoPodeAlterar($contrato);

        $this->verificarPlanoPodeAlterar(
            $plano,
            $user->id_emp_id
        );

        return view(
            'view_admin_user.view_contratos.alterar-plano',
            compact(
                'plano',
                'contrato'
            )
        );
    }

    public function atualizarPlano(Request $request, $planoId)
    {
        try {
            $id = Crypt::decrypt($planoId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $plano = Plano::findOrFail($id);

        $contrato = Contrato::with('detalheAtual')
            ->where('empresa_id', $user->id_emp_id)
            ->latest('id_contrato')
            ->first();

        if (!$contrato) {
            return redirect()
                ->route('admin.planos')
                ->with('error', 'Sua empresa ainda não possui um contrato.');
        }

        if (!$contrato->detalheAtual) {
            return redirect()
                ->route('admin.planos')
                ->with('error', 'Não foi possível identificar o plano atual.');
        }

        $this->verificarContratoPodeAlterar($contrato);

        $this->verificarPlanoPodeAlterar(
            $plano,
            $user->id_emp_id
        );

        $request->validate([
            'aceite_alteracao' => 'required|accepted',
        ]);

        DB::transaction(function () use (
            $contrato,
            $plano
        ) {

            $detalheAtual = DetalhesContrato::where(
                'contrato_id',
                $contrato->id_contrato
            )
                ->whereNull('det_data_fim')
                ->latest('det_data_vigente')
                ->lockForUpdate()
                ->first();

            if (!$detalheAtual) {
                abort(
                    redirect()
                        ->route('admin.planos')
                        ->with(
                            'error',
                            'Não foi possível localizar o plano atual.'
                        )
                );
            }


            $novoDetalhe = DetalhesContrato::create([
                'contrato_id' => $contrato->id_contrato,
                'plano_id'    => $plano->id_plano,

                'det_plano_nome'       => $plano->plano_nome,
                'det_plano_tipo'       => $plano->plano_tipo,
                'det_plano_valor'      => $plano->plano_valor,
                'det_plano_quantidade' => $plano->plano_quantidade,

                'det_data_vigente' => now(),
                'det_data_fim'     => null,
            ]);

            // Encerra o plano atual
            $detalheAtual->update([
                'det_data_fim' => now(),
            ]);

            MensalidadeContrato::where(
                'det_contrato_id',
                $detalheAtual->id_det_contrato
            )
                ->whereDate(
                    'mensa_contrato_vencimento',
                    '>',
                    Carbon::today()
                )
                ->whereIn(
                    'mensa_contrato_status',
                    ['Pendente', 'Em aberto']
                )
                ->update([
                    'det_contrato_id'     => $novoDetalhe->id_det_contrato,
                    'mensa_contrato_valor' => $plano->plano_valor,
                ]);
        });

        return redirect()
            ->route('admin.planos')
            ->with(
                'success',
                "Plano alterado com sucesso para {$plano->plano_nome}. As mensalidades futuras foram atualizadas."
            );
    }


    public function create($planoId)
    {
        try {
            $id = Crypt::decrypt($planoId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $plano = Plano::findOrFail($id);

        $this->verificarLimiteMatriculas(
            $plano,
            $user->id_emp_id
        );

        $empresaPS = Empresa::where('emp_status', 'PS')->first();

        $empresa = Empresa::with([
            'contrato.detalhes' => function ($q) {
                $q->latest('det_data_vigente');
            }
        ])
            ->findOrFail($user->id_emp_id);

        $contrato = $empresa->contrato;

        return view(
            'view_admin_user.view_contratos.create',
            compact(
                'plano',
                'empresa',
                'contrato',
                'empresaPS'
            )
        );
    }

    public function store(Request $request, $planoId)
    {
        try {
            $id = Crypt::decrypt($planoId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $plano = Plano::where('id_plano', $id)->firstOrFail();
        $empresa = Empresa::findOrFail($user->id_emp_id);

        $this->verificarLimiteMatriculas(
            $plano,
            $empresa->id_empresa
        );

        $request->validate([
            'cont_nome'          => 'required|string|max:150',
            'cont_email'         => 'required|email|max:150',
            'cont_cpf_cnpj'      => 'required|string|max:20',
            'cont_telefone'      => 'required|string|max:20',
            'cont_endereco'      => 'required|string|max:255',
            'cont_numero'        => 'required|string|max:20',
            'cont_complemento'   => 'nullable|string|max:100',
            'cont_bairro'        => 'required|string|max:120',
            'cont_cidade'        => 'required|string|max:120',
            'cont_estado'        => 'required|string|max:2',
            'cont_cep'           => 'required|string|max:10',
            'contrato_forma_pagamento' => 'required|string|max:50',
            'contrato_dia_vencimento'  => 'required|integer|in:8,20,30',
            'cont_aceite'        => 'required|accepted',
        ]);


        DB::transaction(function () use ($request, $empresa, $plano) {

            $this->verificarLimiteMatriculas(
                $plano,
                $empresa->id_empresa
            );

            $diaVencimento = (int) $request->contrato_dia_vencimento;
            $empresaPS = Empresa::where('emp_status', 'PS')->first();

            $contrato = Contrato::create([

                'empresa_id' => $empresa->id_empresa,

                'contrato_empresa_nome' => $empresa->emp_nome,
                'contrato_empresa_email' => $empresa->emp_email_responsavel,
                'contrato_empresa_responsavel' => $empresa->emp_nome_responsavel,
                'contrato_empresa_cpf' => $empresa->emp_cpf,
                'contrato_empresa_telefone' => $empresa->emp_telefone_responsavel,
                'contrato_empresa_tipo' => $empresa->emp_tipo,

                'contrato_pix_chave' => $empresaPS->emp_chave_pix,
                'contrato_pix_recebedor' => $empresaPS->emp_nome_recebedor,
                'contrato_pix_cidade' => $empresaPS->emp_cidade,

                'contrato_forma_pagamento' => $request->contrato_forma_pagamento,
                'contrato_dia_vencimento'  => $diaVencimento,

                'contrato_cep'          => $request->cont_cep,
                'contrato_endereco'     => $request->cont_endereco,
                'contrato_numero'       => $request->cont_numero,
                'contrato_complemento'  => $request->cont_complemento,
                'contrato_bairro'       => $request->cont_bairro,
                'contrato_cidade'       => $request->cont_cidade,
                'contrato_estado'       => strtoupper($request->cont_estado),

                'contrato_status' => 'Pendente',
                'contrato_assinado_em' => now(),
            ]);

            $detalhe = DetalhesContrato::create([
                'contrato_id' => $contrato->id_contrato,
                'plano_id'    => $plano->id_plano,

                'det_plano_nome'       => $plano->plano_nome,
                'det_plano_tipo'       => $plano->plano_tipo,
                'det_plano_valor'      => $plano->plano_valor,
                'det_plano_quantidade' => $plano->plano_quantidade,

                'det_data_vigente' => now(),
                'det_data_fim'     => null,
            ]);

            $this->gerarMensalidades($detalhe, $diaVencimento, $plano->plano_valor);

            $empresa->update([
                'emp_status' => 'Cliente',
            ]);
        });

        return redirect()
            ->route('admin.planos')
            ->with('success', 'Contrato enviado com sucesso.');
    }

    private function gerarMensalidades(DetalhesContrato $detalhe, int $diaVencimento, float $valor): void
    {
        $hoje = Carbon::today();

        // Entrada
        MensalidadeContrato::create([
            'det_contrato_id' => $detalhe->id_det_contrato,
            'mensa_contrato_referencia' => 'Entrada',
            'mensa_contrato_vencimento' => $hoje->toDateString(),
            'mensa_contrato_valor' => $valor,
            'mensa_contrato_status' => 'Pendente',
        ]);

        // Primeiro vencimento
        $primeiraData = Carbon::create(
            $hoje->year,
            $hoje->month,
            1
        );

        $primeiraData->setDay(
            min($diaVencimento, $primeiraData->daysInMonth)
        );

        if ($primeiraData->lessThanOrEqualTo($hoje)) {

            $primeiraData->addMonthNoOverflow();

            $primeiraData->setDay(
                min($diaVencimento, $primeiraData->daysInMonth)
            );
        }

        for ($i = 0; $i < 12; $i++) {

            $vencimento = $primeiraData->copy()->addMonthsNoOverflow($i);

            $vencimento->setDay(
                min($diaVencimento, $vencimento->daysInMonth)
            );

            MensalidadeContrato::create([
                'det_contrato_id' => $detalhe->id_det_contrato,
                'mensa_contrato_referencia' => $vencimento->format('m/Y'),
                'mensa_contrato_vencimento' => $vencimento->toDateString(),
                'mensa_contrato_valor' => $valor,
                'mensa_contrato_status' => 'Pendente',
            ]);
        }
    }

    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $contrato = Contrato::with([
            'detalhes.plano',
            'detalhes.mensalidades'
        ])
            ->where('id_contrato', $id)
            ->where('empresa_id', $user->id_emp_id)
            ->firstOrFail();

        $pagamentoService = new PagamentoContratoService();
        $pagamentoService->atualizarStatusContrato($contrato);

        $contrato->load('detalhes.plano', 'detalhes.mensalidades');

        return view(
            'view_admin_user.view_contratos.show',
            compact('contrato')
        );
    }
}
