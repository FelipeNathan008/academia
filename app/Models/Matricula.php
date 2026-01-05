<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = 'matricula';
    protected $primaryKey = 'id_matricula';

    protected $fillable = [
        'aluno_id_aluno',
        'matri_desc'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function detalhes()
    {
        return $this->hasMany(DetalhesMatricula::class, 'matricula_id_matricula', 'id_matricula');
    }
}
