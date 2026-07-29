<?php

namespace App\Models\Modelos;

use Illuminate\Database\Eloquent\Model;

class ModalidadeMolde extends Model
{
    protected $table = 'modalidade_molde';
    protected $primaryKey = 'id_modalidade_molde';

    protected $fillable = [
        'mod_nome',
        'mod_desc',
    ];

    public function graduacoes()
    {
        return $this->hasMany(GraduacaoMolde::class, 'id_modalidade_molde', 'id_modalidade_molde');
    }
}