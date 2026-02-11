<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $table = 'aluno';
    protected $primaryKey = 'id_aluno';

    protected $fillable = [
        'responsavel_id_responsavel',
        'aluno_nome',
        'aluno_nascimento',
        'aluno_desc',
        'aluno_foto',
        'aluno_bolsista'
    ];

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class, 'responsavel_id_responsavel', 'id_responsavel');
    }


    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function mensalidades()
    {
        return $this->hasMany(Mensalidade::class, 'aluno_id_aluno', 'id_aluno');
    }
    public function detalhes()
    {
        return $this->hasMany(DetalhesAluno::class, 'aluno_id_aluno', 'id_aluno');
    }
}
