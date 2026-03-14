<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'emp_nome',
        'emp_apelido',
        'emp_nome_responsavel',
        'emp_email_responsavel',
        'emp_telefone_responsavel',
        'emp_cpf',
        'emp_tipo',
        'emp_foto'
    ];

    public function filiais()
    {
        return $this->hasMany(Filial::class,'id_emp_id','id_empresa');
    }
}