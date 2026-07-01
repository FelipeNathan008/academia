<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DetalhesProfessor extends Model
{
    protected $table = 'detalhes_professor';
    protected $primaryKey = 'id_det_professor';

    protected $fillable = [
        'professor_id_professor',
        'id_graduacao',
        'det_data',
        'det_certificado',
        'id_emp_id'
    ];
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id_professor', 'id_professor');
    }

    public function graduacao()
    {
        return $this->belongsTo(
            Graduacao::class,
            'id_graduacao',
            'id_graduacao'
        );
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
