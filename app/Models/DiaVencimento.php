<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DiaVencimento extends Model
{
    protected $table = 'dias_vencimento';
    protected $primaryKey = 'id_dia_vencimento';

    public $timestamps = true;

    protected $fillable = [
        'dia_vencimento',
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