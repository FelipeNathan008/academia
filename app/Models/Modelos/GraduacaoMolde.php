<?php

namespace App\Models\Modelos;

use Illuminate\Database\Eloquent\Model;

class GraduacaoMolde extends Model
{
    protected $table = 'graduacao_molde';
    protected $primaryKey = 'id_graduacao_molde';

    protected $fillable = [
        'gradu_nome_cor',
        'gradu_grau',
        'gradu_meta',
        'gradu_ordem',
        'id_modalidade_molde',
    ];

    public function modalidade()
    {
        return $this->belongsTo(ModalidadeMolde::class, 'id_modalidade_molde', 'id_modalidade_molde');
    }

    public function scopeOrdem($query)
    {
        return $query->orderBy('gradu_ordem');
    }
}
