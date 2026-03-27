<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DetalhesProfessor extends Model
{
    protected $table = 'detalhes_professor';
    protected $primaryKey = 'id_det_professor';

    protected $fillable = [
        'professor_id_professor',
        'det_gradu_nome_cor',
        'det_grau',
        'det_modalidade',
        'det_data',
        'det_certificado',
        'id_emp_id'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id_professor', 'id_professor');
    }

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }

    public function scopeOrdenarPorFaixa($query)
    {
        return $query->orderByRaw("
        CASE LOWER(det_gradu_nome_cor)

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
