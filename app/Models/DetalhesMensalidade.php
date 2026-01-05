<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesMensalidade extends Model
{
    protected $table = 'detalhes_mensalidade';
    protected $primaryKey = 'id_detalhes_mensalidade';

    protected $fillable = [
        'mensalidade_id_mensalidade',
        'det_mensa_forma_pagamento',
        'det_mensa_per_vig_pago'
    ];

    public function mensalidade()
    {
        return $this->belongsTo(Mensalidade::class, 'mensalidade_id_mensalidade', 'id_mensalidade');
    }
}
