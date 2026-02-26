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

    public function scopeOrdenarPorFaixa($query)
    {
        return $query->orderByRaw("
        CASE LOWER(det_gradu_nome_cor)

            WHEN 'cinza e branca' THEN 1
            WHEN 'cinza' THEN 2
            WHEN 'cinza e preta' THEN 3

            WHEN 'amarela e branca' THEN 4
            WHEN 'amarela' THEN 5
            WHEN 'amarela e preta' THEN 6

            WHEN 'laranja e branca' THEN 7
            WHEN 'laranja' THEN 8
            WHEN 'laranja e preta' THEN 9

            WHEN 'verde e branca' THEN 10
            WHEN 'verde' THEN 11
            WHEN 'verde e preta' THEN 12

            WHEN 'branca' THEN 13
            WHEN 'azul' THEN 14
            WHEN 'roxa' THEN 15
            WHEN 'marrom' THEN 16
            WHEN 'preta' THEN 17

            ELSE 99
        END
    ");
    }

    public function scopeOrdenarPorFaixaInverso($query)
    {
        return $query->orderByRaw("
        CASE LOWER(det_gradu_nome_cor)

            WHEN 'cinza e branca' THEN 1
            WHEN 'cinza' THEN 2
            WHEN 'cinza e preta' THEN 3

            WHEN 'amarela e branca' THEN 4
            WHEN 'amarela' THEN 5
            WHEN 'amarela e preta' THEN 6

            WHEN 'laranja e branca' THEN 7
            WHEN 'laranja' THEN 8
            WHEN 'laranja e preta' THEN 9

            WHEN 'verde e branca' THEN 10
            WHEN 'verde' THEN 11
            WHEN 'verde e preta' THEN 12

            WHEN 'branca' THEN 13
            WHEN 'azul' THEN 14
            WHEN 'roxa' THEN 15
            WHEN 'marrom' THEN 16
            WHEN 'preta' THEN 17

            ELSE 99
        END DESC
    ");
    }

    public function graduacaoMeta()
    {
        return $this->hasOne(Graduacao::class, 'gradu_nome_cor', 'det_gradu_nome_cor')
            ->where('gradu_grau', 0);
    }
}
