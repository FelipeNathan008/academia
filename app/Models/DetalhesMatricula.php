<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesMatricula extends Model
{
    protected $table = 'detalhes_matricula';
    protected $primaryKey = 'id_det_matricula';

    protected $fillable = [
        'matricula_id_matricula',
        'modalidade_id_modalidade',
        'grade_horario_id_grade'
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id_matricula', 'id_matricula');
    }

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id_modalidade', 'id_modalidade');
    }

    public function grade()
    {
        return $this->belongsTo(GradeHorario::class, 'grade_horario_id_grade', 'id_grade');
    }
}
