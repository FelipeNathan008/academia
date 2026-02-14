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
        'det_mensa_mes_vigente',
        'det_mensa_data_venc',
        'det_mensa_data_pagamento',
        'det_mensa_valor',
        'det_mensa_status'
    ];

    public function mensalidade()
    {
        return $this->belongsTo(Mensalidade::class,'mensalidade_id_mensalidade','id_mensalidade');
    }
}
