<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GradeHorario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        'matri_plano',
        'id_emp_id'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }
    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }

    public function mensalidades()
    {
        return $this->hasMany(
            Mensalidade::class,
            'matricula_id_matricula', 
            'id_matricula' 
        );
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
