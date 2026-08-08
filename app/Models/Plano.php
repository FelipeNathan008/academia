<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $primaryKey = 'id_plano';

    protected $fillable = [
        'plano_nome',
        'plano_tipo',
        'plano_valor',
        'plano_quantidade',
        'plano_desc',
    ];

    protected $casts = [
        'plano_desc' => 'array',
    ];
}
