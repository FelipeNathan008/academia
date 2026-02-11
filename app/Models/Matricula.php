<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GradeHorario;
use App\Models\Professor;

class Matricula extends Model
{
    protected $table = 'matricula';
    protected $primaryKey = 'id_matricula';

    protected $fillable = [
        'aluno_id_aluno',
        'matri_desc',
        'matri_status',
        'matri_data',
        'matri_plano',
        'matri_turma',
        'matri_professor'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class,'matri_professor','id_professor');
    }

    public function grade()
    {
        return $this->belongsTo(GradeHorario::class,'matri_turma','id_grade');
    }
}
