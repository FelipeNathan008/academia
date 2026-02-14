<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
    protected $table = 'modalidade';
    protected $primaryKey = 'id_modalidade';

    protected $fillable = [
        'mod_nome',
        'mod_desc'
    ];

    public function valores()
    {
        return $this->hasMany(PrecoModalidade::class, 'modalidade_id', 'id_modalidade');
    }
}
