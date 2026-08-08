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
use Carbon\Carbon;
use App\Models\MensalidadeContrato;


class ContratoController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $planos = Plano::orderBy('plano_valor')->get();

        $contrato = Contrato::with('detalheAtual.plano')
            ->where('empresa_id', $user->id_emp_id)
            ->latest('id_contrato')
            ->first();

        return view('view_admin_user.view_contratos.planos',   compact('planos', 'contrato'));
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
                'contrato'
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
            'contrato_dia_vencimento'  => 'required|integer|in:8,15,30',
            'cont_aceite'        => 'required|accepted',
        ]);

        $empresa = Empresa::findOrFail($user->id_emp_id);

        DB::transaction(function () use ($request, $empresa, $plano) {

            $diaVencimento = (int) $request->contrato_dia_vencimento;

            $contrato = Contrato::create([

                'empresa_id' => $empresa->id_empresa,

                'contrato_empresa_nome' => $empresa->emp_nome,
                'contrato_empresa_email' => $empresa->emp_email_responsavel,
                'contrato_empresa_responsavel' => $empresa->emp_nome_responsavel,
                'contrato_empresa_cpf' => $empresa->emp_cpf,
                'contrato_empresa_telefone' => $empresa->emp_telefone_responsavel,
                'contrato_empresa_tipo' => $empresa->emp_tipo,

                'contrato_pix_chave' => $empresa->emp_chave_pix,
                'contrato_pix_recebedor' => $empresa->emp_nome_recebedor,
                'contrato_pix_cidade' => $empresa->emp_cidade,

                'contrato_forma_pagamento' => $request->contrato_forma_pagamento,
                'contrato_dia_vencimento'  => $diaVencimento,

                // Endereço (snapshot do momento da assinatura)
                'contrato_cep'          => $request->cont_cep,
                'contrato_endereco'     => $request->cont_endereco,
                'contrato_numero'       => $request->cont_numero,
                'contrato_complemento'  => $request->cont_complemento,
                'contrato_bairro'       => $request->cont_bairro,
                'contrato_cidade'       => $request->cont_cidade,
                'contrato_estado'       => strtoupper($request->cont_estado),

                'contrato_status' => 'Pendente',
                'contrato_situacao_financeira' => 'Pendente',
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

        $contrato = Contrato::with('detalhes.plano')
            ->where('empresa_id', $user->id_emp_id)
            ->latest('id_contrato')
            ->first();


        return view(
            'view_admin_user.view_contratos.show',
            compact('contrato')
        );
    }
}
