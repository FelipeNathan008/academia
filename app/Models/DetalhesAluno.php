<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesAluno extends Model
{
    protected $table = 'detalhes_aluno';
    protected $primaryKey = 'id_det_aluno';

    protected $fillable = [
        'aluno_id_aluno',
        'det_gradu_nome_cor',
        'det_grau',
        'det_modalidade',
        'det_data',
        'det_certificado'
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id_aluno', 'id_aluno');
    }
}
