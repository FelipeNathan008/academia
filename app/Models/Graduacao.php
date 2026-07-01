<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Graduacao extends Model
{
    protected $table = 'graduacao';
    protected $primaryKey = 'id_graduacao';

    protected $fillable = [
        'gradu_nome_cor',
        'gradu_grau',
        'gradu_meta',
        'gradu_ordem',
        'id_modalidade',
        'id_emp_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'id_modalidade', 'id_modalidade');
    }

    public function scopeOrdem($query)
    {
        return $query->orderBy('gradu_ordem');
    }

    public function detalhesAluno()
    {
        return $this->hasMany(DetalhesAluno::class, 'id_graduacao', 'id_graduacao');
    }

    public function detalhesProfessor()
    {
        return $this->hasMany(DetalhesProfessor::class, 'id_graduacao', 'id_graduacao');
    }
}
