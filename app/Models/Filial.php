<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    protected $table = 'filiais';
    protected $primaryKey = 'id_filial';

    protected $fillable = [
        'id_emp_id',
        'filial_nome',
        'filial_apelido',
        'filial_nome_responsavel',
        'filial_email_responsavel',
        'filial_telefone_responsavel',
        'filial_cpf'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_emp_id', 'id_empresa');
    }
}
