<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequenciaAluno extends Model
{
    protected $table = 'frequencia_aluno';
    protected $primaryKey = 'id_frequencia_aluno';

    protected $fillable = [
        'grade_horario_id_grade',
        'matricula_id_matricula',
        'freq_presenca',
        'freq_data_aula',
        'freq_observacao'
    ];

    public function grade()
    {
        return $this->belongsTo(GradeHorario::class, 'grade_horario_id_grade', 'id_grade');
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id_matricula', 'id_matricula');
    }
}