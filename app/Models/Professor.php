<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professor';
    protected $primaryKey = 'id_professor';

    protected $fillable = [
        'prof_nome',
        'prof_nascimento',
        'prof_desc',
        'prof_foto'
    ];

    public function grades()
    {
        return $this->hasMany(GradeHorario::class, 'professor_id_professor', 'id_professor');
    }

    public function detalhes()
    {
        return $this->hasMany(DetalhesProfessor::class, 'professor_id_professor', 'id_professor');
    }
}
