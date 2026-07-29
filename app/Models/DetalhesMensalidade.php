<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'det_mensa_status',
        'id_emp_id',
        'det_mensa_comprovante_path',
        'det_mensa_comprovante_enviado_em',
    ];

    protected $casts = [
        'det_mensa_data_venc' => 'date',
        'det_mensa_data_pagamento' => 'date',
        'det_mensa_comprovante_enviado_em' => 'datetime',
    ];

    public function mensalidade()
    {
        return $this->belongsTo(Mensalidade::class, 'mensalidade_id_mensalidade', 'id_mensalidade');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_emp_id', 'id_empresa');
    }

    public function temComprovante(): bool
    {
        return !empty($this->det_mensa_comprovante_path)
            && Storage::disk('local')->exists($this->det_mensa_comprovante_path);
    }
}
