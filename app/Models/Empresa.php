<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'emp_nome',
        'emp_apelido',
        'emp_nome_responsavel',
        'emp_email_responsavel',
        'emp_telefone_responsavel',
        'emp_cpf',
        'emp_tipo',
        'emp_foto',
        'emp_chave_pix',
        'emp_nome_recebedor',
        'emp_cidade',
        'emp_degustacao_inicio',
        'emp_degustacao_fim',
        'emp_status',

        'contrato_empresa_nome',
        'contrato_empresa_email',
        'contrato_empresa_responsavel',
        'contrato_empresa_cpf',
        'contrato_empresa_telefone',
        'contrato_empresa_tipo',
    ];

    protected $casts = [
        'emp_degustacao_inicio' => 'date',
        'emp_degustacao_fim' => 'date',
    ];

    public function filiais()
    {
        return $this->hasMany(Filial::class, 'id_emp_id', 'id_empresa');
    }

    public function contrato()
    {
        return $this->hasOne(Contrato::class, 'empresa_id', 'id_empresa');
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'empresa_id', 'id_empresa');
    }
}
