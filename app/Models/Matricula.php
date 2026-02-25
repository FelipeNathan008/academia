<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GradeHorario;

class Matricula extends Model
{
    protected $table = 'matricula';
    protected $primaryKey = 'id_matricula';

    protected $fillable = [
        'aluno_id_aluno',
        'grade_id_grade',
        'matri_desc',
        'matri_status',
        'matri_data',
        'matri_plano'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function grade()
    {
        return $this->belongsTo(GradeHorario::class, 'grade_id_grade', 'id_grade');
    }
    public function frequencias()
    {
        return $this->hasMany(
            FrequenciaAluno::class,
            'matricula_id_matricula',
            'id_matricula' // chave primária da matrícula
        );
    }
}
