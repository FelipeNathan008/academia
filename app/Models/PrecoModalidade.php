<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecoModalidade extends Model
{
    protected $table = 'preco_modalidade';
    protected $primaryKey = 'id_preco_modalidade';

    protected $fillable = [
        'modalidade_id',
        'preco_modalidade',
        'preco_plano'
    ];

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id', 'id_modalidade');
    }
}
