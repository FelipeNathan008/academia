<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MensalidadeContrato;
use Carbon\Carbon;

class SuperAdminMensalidadeController extends Controller
{
    public function index()
    {
        $mensalidades = MensalidadeContrato::with([
            'detalheContrato.contrato'
        ])
            ->whereDate('mensa_contrato_vencimento', '<', Carbon::today())
            ->whereIn('mensa_contrato_status', [
                'Pendente',
                'Atrasado',
                'Comprovante enviado',
                'Rejeitado'
            ])
            ->orderBy('mensa_contrato_vencimento')
            ->paginate(15)
            ->withQueryString();

        $mensalidadesAtrasadas = MensalidadeContrato::whereDate(
            'mensa_contrato_vencimento',
            '<',
            Carbon::today()
        )
            ->whereIn('mensa_contrato_status', [
                'Pendente',
                'Atrasado',
                'Comprovante enviado',
                'Rejeitado'
            ])
            ->count();

        return view(
            'view_superadmin.dashboard.mensalidades_atrasadas',
            compact('mensalidades', 'mensalidadesAtrasadas')
        );
    }
}