<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduacao extends Model
{
    protected $table = 'graduacao';
    protected $primaryKey = 'id_graduacao';

    protected $fillable = [
        'gradu_nome_cor',
        'gradu_grau',
        'gradu_meta'
    ];

    public function scopeOrdenarPorFaixa($query)
    {
        return $query->orderByRaw("
        CASE LOWER(gradu_nome_cor)

            WHEN 'cinza e branca' THEN 1
            WHEN 'cinza' THEN 2
            WHEN 'cinza e preta' THEN 3

            WHEN 'amarela e branca' THEN 4
            WHEN 'amarela' THEN 5
            WHEN 'amarela e preta' THEN 6

            WHEN 'laranja e branca' THEN 7
            WHEN 'laranja' THEN 8
            WHEN 'laranja e preta' THEN 9

            WHEN 'verde e branca' THEN 10
            WHEN 'verde' THEN 11
            WHEN 'verde e preta' THEN 12

            WHEN 'branca' THEN 13
            WHEN 'azul' THEN 14
            WHEN 'roxa' THEN 15
            WHEN 'marrom' THEN 16
            WHEN 'preta' THEN 17

            ELSE 99
        END
    ");
    }
}
