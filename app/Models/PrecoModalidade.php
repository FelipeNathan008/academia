<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
class PrecoModalidade extends Model
{
    protected $table = 'preco_modalidade';
    protected $primaryKey = 'id_preco_modalidade';

    protected $fillable = [
        'modalidade_id',
        'preco_modalidade',
        'preco_plano',
        'id_emp_id'
    ];

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id', 'id_modalidade');
    }

    
    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }
}
