<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    protected $table = 'visitantes';

    protected $primaryKey = 'id_visitante';

    protected $fillable = [
        'id_emp_id',
        'vis_nome',
        'vis_email',
        'vis_telefone',
        'vis_data_nasc',
        'vis_status',
    ];

    protected $casts = [
        'vis_data_nasc' => 'date',
    ];

    public function grades()
    {
        return $this->hasMany(
            VisitanteGrade::class,
            'visitante_id_visitante',
            'id_visitante'
        );
    }
}
