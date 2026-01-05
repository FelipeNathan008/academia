<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
    protected $table = 'mensalidade';
    protected $primaryKey = 'id_mensalidade';

    protected $fillable = [
        'aluno_id_aluno',
        'mensa_periodo_vigente',
        'mensa_data_venc',
        'mensa_valor',
        'mensa_status'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function detalhes()
    {
        return $this->hasMany(DetalhesMensalidade::class, 'mensalidade_id_mensalidade', 'id_mensalidade');
    }
}
