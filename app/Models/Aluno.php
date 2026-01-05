<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $table = 'aluno';
    protected $primaryKey = 'id_aluno';

    protected $fillable = [
        'aluno_nome',
        'aluno_nascimento',
        'aluno_desc',
        'aluno_foto'
    ];

    public function responsaveis()
    {
        return $this->hasMany(Responsavel::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function mensalidades()
    {
        return $this->hasMany(Mensalidade::class, 'aluno_id_aluno', 'id_aluno');
    }
}
