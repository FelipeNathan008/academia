<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioTreino extends Model
{
    protected $table = 'horario_treino';
    protected $primaryKey = 'id_hora';

    public $timestamps = false; // sua tabela não tem created_at / updated_at

    protected $fillable = [
        'id_hora',
        'hora_inicio',
        'hora_fim',
        'hora_semana',
        'hora_modalidade',
    ];
    public function gradeHorario()
    {
        return $this->hasMany(GradeHorario::class, 'horario_treino_id_hora', 'id_hora');
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
