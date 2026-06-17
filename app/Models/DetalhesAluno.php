<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DetalhesAluno extends Model
{
    protected $table = 'detalhes_aluno';
    protected $primaryKey = 'id_det_aluno';

    protected $fillable = [
        'aluno_id_aluno',
        'id_graduacao',
        'det_data',
        'det_certificado',
        'id_emp_id'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function graduacao()
    {
        return $this->belongsTo(Graduacao::class, 'id_graduacao', 'id_graduacao');
    }

    public function modalidade()
    {
        return $this->belongsTo(
            Modalidade::class,
            'id_modalidade',
            'id_modalidade'
        );
    }

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where(
                    $builder->getModel()->getTable() . '.id_emp_id',
                    Auth::user()->id_emp_id
                );
            }
        });
    }
}
