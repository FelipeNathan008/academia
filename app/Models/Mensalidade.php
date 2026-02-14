<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
    protected $table = 'mensalidade';
    protected $primaryKey = 'id_mensalidade';

    public $timestamps = true;

    protected $fillable = [
        'aluno_id_aluno',
        'matricula_id_matricula',
        'mensa_dia_venc',
        'mensa_valor'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function detalhes()
    {
        return $this->hasMany(DetalhesMensalidade::class,'mensalidade_id_mensalidade','id_mensalidade');
    }
    public function matricula()
    {
        return $this->belongsTo(Matricula::class,'matricula_id_matricula','id_matricula');
    }

}
