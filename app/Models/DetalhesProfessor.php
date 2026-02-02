<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesProfessor extends Model
{
    protected $table = 'detalhes_professor';
    protected $primaryKey = 'id_det_professor';

    protected $fillable = [
        'professor_id_professor',
        'det_gradu_nome_cor',
        'det_grau',
        'det_modalidade',
        'det_data',
        'det_certificado'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id_professor', 'id_professor');
    }
}
