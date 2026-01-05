<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequenciaAluno extends Model
{
    protected $table = 'frequencia_aluno';
    protected $primaryKey = 'id_frequencia_aluno';

    protected $fillable = [
        'grade_horario_id_grade',
        'freq_alunos'
    ];

    public function grade()
    {
        return $this->belongsTo(GradeHorario::class, 'grade_horario_id_grade', 'id_grade');
    }
}

