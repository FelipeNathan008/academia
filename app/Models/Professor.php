<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Professor extends Model
{
    protected $table = 'professor';
    protected $primaryKey = 'id_professor';

    protected $fillable = [
        'prof_nome',
        'prof_nascimento',
        'prof_telefone',
        'prof_desc',
        'prof_foto',
        'id_emp_id'
    ];

    public function grades()
    {
        return $this->hasMany(GradeHorario::class, 'professor_id_professor', 'id_professor');
    }
    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }

    public function empresas()
    {
        return $this->belongsTo(Empresa::class, 'id_emp_id', 'id_empresa');
    }
    public function detalhes()
    {
        return $this->hasMany(DetalhesProfessor::class, 'professor_id_professor', 'id_professor');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'professor_id', 'id_professor');
    }
}
