<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Turma extends Model
{
    protected $table = 'turma';
    protected $primaryKey = 'id_turma';

    protected $fillable = [
        'turma_nome',
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
}