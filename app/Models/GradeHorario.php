<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeHorario extends Model
{
    protected $table = 'grade_horario';
    protected $primaryKey = 'id_grade';

    protected $fillable = [
        'professor_id_professor',
        'grade_modalidade',
        'grade_dia_semana',
        'grade_inicio',
        'grade_fim',
        'grade_desc'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id_professor', 'id_professor');
    }
}
