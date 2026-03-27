<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
class Modalidade extends Model
{
    protected $table = 'modalidade';
    protected $primaryKey = 'id_modalidade';

    protected $fillable = [
        'mod_nome',
        'mod_desc',
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
    public function valores()
    {
        return $this->hasMany(PrecoModalidade::class, 'modalidade_id', 'id_modalidade');
    }
}
