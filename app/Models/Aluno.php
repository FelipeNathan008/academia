<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Aluno extends Model
{
    protected $table = 'aluno';
    protected $primaryKey = 'id_aluno';

    protected $fillable = [
        'responsavel_id_responsavel',
        'aluno_nome',
        'aluno_parentesco',
        'aluno_nascimento',
        'aluno_desc',
        'aluno_foto',
        'aluno_bolsista',
        'id_emp_id'
    ];

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class, 'responsavel_id_responsavel', 'id_responsavel');
    }

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'aluno_id_aluno', 'id_aluno');
    }

    public function mensalidades()
    {
        return $this->hasMany(Mensalidade::class, 'aluno_id_aluno', 'id_aluno');
    }
    public function detalhes()
    {
        return $this->hasMany(DetalhesAluno::class, 'aluno_id_aluno', 'id_aluno' )
            ->join(
                'graduacao',
                'graduacao.id_graduacao',
                '=',
                'detalhes_aluno.id_graduacao'
            )
            ->orderByDesc('graduacao.gradu_ordem')
            ->select('detalhes_aluno.*');
    }
}
