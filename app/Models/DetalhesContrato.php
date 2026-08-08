<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesContrato extends Model
{
    protected $table = 'detalhes_contratos';

    protected $primaryKey = 'id_det_contrato';

    protected $fillable = [
        'contrato_id',
        'plano_id',

        'det_plano_nome',
        'det_plano_tipo',
        'det_plano_valor',
        'det_plano_quantidade',

        'det_data_vigente',
        'det_data_fim',
    ];

    protected $casts = [
        'det_data_vigente' => 'date',
        'det_data_fim' => 'date',
        'det_plano_valor' => 'decimal:2',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id', 'id_contrato');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id', 'id_plano');
    }

    public function mensalidades()
    {
        return $this->hasMany(
            MensalidadeContrato::class,
            'det_contrato_id',
            'id_det_contrato'
        );
    }
}
