<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    protected $table = 'responsavel';
    protected $primaryKey = 'id_responsavel';

    protected $fillable = [
        'aluno_id_aluno',
        'resp_nome',
        'resp_parentesco',
        'resp_cpf',
        'resp_cep',
        'resp_telefone',
        'resp_email',
        'resp_logradouro',
        'resp_numero',
        'resp_complemento',
        'resp_bairro',
        'resp_cidade'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }
}
