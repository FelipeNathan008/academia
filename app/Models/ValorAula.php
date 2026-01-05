<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValorAula extends Model
{
    protected $table = 'valor_aula';
    protected $primaryKey = 'id_valor_aula';

    protected $fillable = [
        'modalidade_id',
        'valor_aula'
    ];

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id', 'id_modalidade');
    }
}
