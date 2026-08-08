<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';

    protected $primaryKey = 'id_contrato';

    protected $fillable = [
        'empresa_id',

        'contrato_status',
        'contrato_situacao_financeira',
        'contrato_forma_pagamento',
        'contrato_dia_vencimento',
        'contrato_assinado_em',
        'contrato_encerrado_em',

        // Dados da empresa
        'contrato_empresa_nome',
        'contrato_empresa_email',
        'contrato_empresa_responsavel',
        'contrato_empresa_cpf',
        'contrato_empresa_telefone',
        'contrato_empresa_tipo',

        // Endereço
        'contrato_endereco',
        'contrato_numero',
        'contrato_complemento',
        'contrato_bairro',
        'contrato_cidade',
        'contrato_estado',
        'contrato_cep',

        // PIX
        'contrato_pix_chave',
        'contrato_pix_recebedor',
        'contrato_pix_cidade',
    ];

    protected $casts = [
        'contrato_assinado_em' => 'datetime',
        'contrato_encerrado_em' => 'datetime',
    ];


    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id_empresa');
    }

    public function detalhes()
    {
        return $this->hasMany(DetalhesContrato::class, 'contrato_id', 'id_contrato');
    }

    public function detalheAtual()
    {
        return $this->hasOne(DetalhesContrato::class, 'contrato_id', 'id_contrato')
            ->whereNull('det_data_fim')
            ->latest('det_data_vigente');
    }
}
