<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\MensalidadeContrato;
use Carbon\Carbon;

class PagamentoContratoService
{

    public function atualizarStatusContrato(Contrato $contrato): void
    {
        $contrato->load('detalhes.mensalidades');

        foreach ($contrato->detalhes as $detalhe) {

            foreach ($detalhe->mensalidades as $mensalidade) {

                if (
                    $mensalidade->mensa_contrato_status === 'Pendente' &&
                    $mensalidade->mensa_contrato_vencimento &&
                    Carbon::parse($mensalidade->mensa_contrato_vencimento)
                    ->startOfDay()
                    ->lt(Carbon::today())
                ) {

                    $mensalidade->update([
                        'mensa_contrato_status' => 'Atrasado'
                    ]);

                    if ($mensalidade->mensa_contrato_referencia === 'Entrada') {
                        $contrato->update([
                            'contrato_status' => 'Inadimplente'
                        ]);
                    }
                }
            }
        }
    }

    public function atualizarStatusAposPagamento(MensalidadeContrato $mensalidade): void
    {
        $contrato = $mensalidade->detalheContrato->contrato;

        if ($mensalidade->mensa_contrato_referencia !== 'Entrada') {
            return;
        }

        if ($mensalidade->mensa_contrato_status === 'Pago') {

            $contrato->update([
                'contrato_status' => 'Ativo'
            ]);
        }

        if ($mensalidade->mensa_contrato_status === 'Atrasado') {

            $contrato->update([
                'contrato_status' => 'Inadimplente'
            ]);
        }
    }

    public function atualizarStatusEMensagem(Contrato $contrato): ?array
    {
        $contrato->load('detalhes.mensalidades');

        foreach ($contrato->detalhes as $detalhe) {
            foreach ($detalhe->mensalidades as $mensalidade) {
                if (
                    $mensalidade->mensa_contrato_status === 'Pendente' &&
                    $mensalidade->mensa_contrato_vencimento &&
                    Carbon::parse($mensalidade->mensa_contrato_vencimento)
                    ->startOfDay()
                    ->lt(Carbon::today())
                ) {

                    $mensalidade->update([
                        'mensa_contrato_status' => 'Atrasado'
                    ]);

                    if ($mensalidade->mensa_contrato_referencia === 'Entrada') {
                        $contrato->update([
                            'contrato_status' => 'Inadimplente'
                        ]);
                    }
                }
            }
        }

        $mensalidades = $contrato->detalhes
            ->flatMap->mensalidades
            ->sortBy('mensa_contrato_vencimento');

        $hoje = Carbon::today();

        foreach ($mensalidades as $mensalidade) {

            $vencimento = $mensalidade->mensa_contrato_vencimento
                ? Carbon::parse($mensalidade->mensa_contrato_vencimento)->startOfDay()
                : null;


            // Entrada pendente
            if (
                $mensalidade->mensa_contrato_referencia === 'Entrada' &&
                $mensalidade->mensa_contrato_status === 'Pendente'
            ) {
                return [
                    'texto' => 'Aguardando pagamento da entrada. Efetue o pagamento hoje para liberar a ativação do contrato.',
                    'cor' => 'yellow'
                ];
            }


            // Mensalidade atrasada
            if ($mensalidade->mensa_contrato_status === 'Atrasado') {
                return [
                    'texto' => 'Você possui mensalidades em atraso.',
                    'cor' => 'red'
                ];
            }


            // Comprovante rejeitado
            if ($mensalidade->mensa_contrato_status === 'Rejeitado') {
                return [
                    'texto' => 'Há um comprovante rejeitado. Envie um novo comprovante para continuar.',
                    'cor' => 'red'
                ];
            }


            // Comprovante aguardando análise
            if ($mensalidade->mensa_contrato_status === 'Comprovante enviado') {
                return [
                    'texto' => 'Existe um comprovante aguardando conferência.',
                    'cor' => 'blue'
                ];
            }


            // Próximo pagamento
            if (
                $mensalidade->mensa_contrato_status === 'Pendente' &&
                $mensalidade->mensa_contrato_referencia !== 'Entrada' &&
                $vencimento &&
                $vencimento->gte($hoje) &&
                $hoje->diffInDays($vencimento) <= 3
            ) {
                return [
                    'texto' => 'Há uma mensalidade disponível para pagamento.',
                    'cor' => 'yellow'
                ];
            }
        }

        return null;
    }
}
