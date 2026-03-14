<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesFilial extends Model
{
    protected $table = 'detalhes_filiais';
    protected $primaryKey = 'id_det_filial';

    protected $fillable = [
        'id_filial_id',
        'det_filial_cep',
        'det_filial_logradouro',
        'det_filial_numero',
        'det_filial_complemento',
        'det_filial_bairro',
        'det_filial_cidade',
        'det_filial_uf',
        'det_filial_regiao',
        'det_filial_pais',
        'det_filial_cnpj',
        'det_filial_email',
        'det_filial_telefone',
    ];

    public function filial()
    {
        return $this->belongsTo(Filial::class, 'id_filial_id', 'id_filial');
    }
}
