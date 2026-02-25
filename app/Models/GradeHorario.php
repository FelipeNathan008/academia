<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id_professor', 'id_professor');
    }

    public function horarioTreino()
    {
        return $this->belongsTo(HorarioTreino::class, 'horario_treino_id_hora', 'id_hora');
    }
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'grade_id_grade', 'id_grade');
    }
}
