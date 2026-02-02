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
}
