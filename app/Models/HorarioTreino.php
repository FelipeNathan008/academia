<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
class HorarioTreino extends Model
{
    protected $table = 'horario_treino';
    protected $primaryKey = 'id_hora';

    protected $fillable = [
        'id_hora',
        'hora_inicio',
        'hora_fim',
        'hora_semana',
        'hora_modalidade',
        'id_emp_id'
    ];
    public function gradeHorario()
    {
        return $this->hasMany(GradeHorario::class, 'horario_treino_id_hora', 'id_hora');
    }

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }
    public function diasSemanaTexto()
    {
        $mapa = [
            1 => 'Domingo',
            2 => 'Segunda-feira',
            3 => 'Terça-feira',
            4 => 'Quarta-feira',
            5 => 'Quinta-feira',
            6 => 'Sexta-feira',
            7 => 'Sábado',
        ];

        return collect(explode(',', $this->hora_semana))
            ->map(fn($d) => $mapa[(int) $d] ?? null)
            ->filter()
            ->implode(', ');
    }
}
