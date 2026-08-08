<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensalidadeContrato extends Model
{
    protected $table = 'mensalidades_contratos';

    protected $primaryKey = 'id_mensalidade_contrato';

    protected $fillable = [
        'det_contrato_id',

        'mensa_contrato_referencia',
        'mensa_contrato_vencimento',
        'mensa_contrato_pagamento',

        'mensa_contrato_valor',
        'mensa_contrato_status',

        'mensa_contrato_comprovante',
        'mensa_contrato_comprovante_enviado_em',
    ];

    protected $casts = [
        'mensa_contrato_vencimento' => 'date',
        'mensa_contrato_pagamento' => 'date',
        'mensa_contrato_comprovante_enviado_em' => 'datetime',
        'mensa_contrato_valor' => 'decimal:2',
    ];

    public function detalheContrato()
    {
        return $this->belongsTo(
            DetalhesContrato::class,
            'det_contrato_id',
            'id_det_contrato'
        );
    }
}