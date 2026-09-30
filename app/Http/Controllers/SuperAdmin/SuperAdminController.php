<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\MensalidadeContrato;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Contrato;
use App\Services\PagamentoContratoService;

class SuperAdminController extends Controller
{
    public function index(
        PagamentoContratoService $pagamentoContratoService
    ) {
        $contratos = Contrato::whereHas(
            'detalhes.mensalidades',
            function ($query) {
                $query->where(
                    'mensa_contrato_status',
                    'Pendente'
                )
                    ->whereDate(
                        'mensa_contrato_vencimento',
                        '<',
                        Carbon::today()
                    );
            }
        )->get();

        foreach ($contratos as $contrato) {
            $pagamentoContratoService->atualizarStatusContrato($contrato);
        }

        $totalEmpresas = Empresa::whereNot('emp_tipo', 'PS')->count();

        $totalFiliais = Filial::count();

        $mensalidadesAtrasadas = MensalidadeContrato::whereDate('mensa_contrato_vencimento', '<', Carbon::today())
            ->whereIn('mensa_contrato_status', ['Pendente', 'Atrasado', 'Comprovante enviado', 'Rejeitado'])
            ->count();

        $comprovantesPendentes = MensalidadeContrato::where(
            'mensa_contrato_status',
            'Comprovante enviado'
        )->count();

        return view('view_superadmin.dashboard.index', compact(
            'totalEmpresas',
            'totalFiliais',
            'mensalidadesAtrasadas',
            'comprovantesPendentes'
        ));
    }

    public function comprovantesPendentes()
    {
        $comprovantes = MensalidadeContrato::with([
            'detalheContrato.contrato'
        ])
            ->where('mensa_contrato_status', 'Comprovante enviado')
            ->orderByDesc('mensa_contrato_comprovante_enviado_em')
            ->get();

        return view(
            'view_superadmin.dashboard.comprovantes_pendentes',
            compact('comprovantes')
        );
    }

    public function darBaixa($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $mensalidade = MensalidadeContrato::findOrFail($id);

        $mensalidade->update([
            'mensa_contrato_status' => 'Pago',
            'mensa_contrato_pagamento' => Carbon::today(),
        ]);

        $pagamentoService = new PagamentoContratoService();

        $pagamentoService->atualizarStatusAposPagamento(
            $mensalidade
        );

        return back()->with(
            'success',
            'Mensalidade baixada com sucesso.'
        );
    }


    public function aprovarComprovante($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $mensalidade = MensalidadeContrato::findOrFail($id);

        if ($mensalidade->mensa_contrato_status !== 'Comprovante enviado') {
            return back()->with(
                'error',
                'Este comprovante não está aguardando aprovação.'
            );
        }

        if ($mensalidade->mensa_contrato_referencia === 'Entrada') {
            $mensalidade->detalheContrato->contrato->update([
                'contrato_status' => 'Ativo',
            ]);
        }
        $mensalidade->update([
            'mensa_contrato_status' => 'Pago',
            'mensa_contrato_pagamento' => Carbon::today(),
        ]);


        return back()->with(
            'success',
            'Comprovante aprovado com sucesso.'
        );
    }

    public function rejeitarComprovante($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $mensalidade = MensalidadeContrato::findOrFail($id);

        if ($mensalidade->mensa_contrato_status !== 'Comprovante enviado') {
            return back()->with(
                'error',
                'Este comprovante não está aguardando aprovação.'
            );
        }

        $mensalidade->update([
            'mensa_contrato_status' => 'Rejeitado',
            'mensa_contrato_pagamento' => null,
        ]);

        return back()->with(
            'success',
            'Comprovante rejeitado com sucesso.'
        );
    }

    public function verComprovante($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $mensalidade = MensalidadeContrato::findOrFail($id);

        if (
            !$mensalidade->mensa_contrato_comprovante ||
            !Storage::disk('local')->exists(
                $mensalidade->mensa_contrato_comprovante
            )
        ) {
            abort(404);
        }

        return response()->file(
            Storage::disk('local')->path(
                $mensalidade->mensa_contrato_comprovante
            )
        );
    }
}
