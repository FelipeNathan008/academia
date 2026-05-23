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
        'professor_id',
        'grade_horario_id',
        'aula_posicao_ensino',
        'aula_periodo_inicial',
        'aula_periodo_final',
        'id_emp_id'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id_professor');
    }

    public function gradeHorario()
    {
        return $this->belongsTo(GradeHorario::class, 'grade_horario_id', 'id_grade');
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