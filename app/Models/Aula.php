<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Aula extends Model
{
    protected $table = 'aula';
    protected $primaryKey = 'id_aula';

    protected $fillable = [
        'id_grade_horario',
        'aula_nome_exercicio',
        'aula_caract_exercicio',
        'aula_inicio',
        'aula_fim',
        'aula_link',
        'aula_status',
        'aula_desc',
        'id_emp_id',
        'id_filial_id',
    ];

    protected $casts = [
        'aula_inicio' => 'date:Y-m-d',
        'aula_fim'    => 'date:Y-m-d',
    ];

    public function gradeHorario()
    {
        return $this->belongsTo(GradeHorario::class, 'id_grade_horario', 'id_grade');
    }

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }
}