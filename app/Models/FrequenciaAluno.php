<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FrequenciaAluno extends Model
{
    protected $table = 'frequencia_aluno';
    protected $primaryKey = 'id_frequencia_aluno';

    protected $fillable = [
        'grade_horario_id_grade',
        'matricula_id_matricula',
        'freq_presenca',
        'freq_data_aula',
        'freq_observacao',
        'id_emp_id'
    ];

    public function grade()
    {
        return $this->belongsTo(GradeHorario::class, 'grade_horario_id_grade', 'id_grade');
    }
    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id_matricula', 'id_matricula');
    }
}
