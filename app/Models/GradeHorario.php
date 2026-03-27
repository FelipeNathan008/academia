<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GradeHorario extends Model
{
    protected $table = 'grade_horario';
    protected $primaryKey = 'id_grade';

    protected $fillable = [
        'professor_id_professor',
        'horario_treino_id_hora',
        'grade_modalidade',
        'grade_dia_semana',
        'grade_inicio',
        'grade_fim',
        'grade_turma',
        'grade_desc',
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
    public function horarioTreino()
    {
        return $this->belongsTo(HorarioTreino::class, 'horario_treino_id_hora', 'id_hora');
    }
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'grade_id_grade', 'id_grade')
            ->where('matri_status', 'Matriculado');
    }
}
