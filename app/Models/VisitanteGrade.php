<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitanteGrade extends Model
{
    protected $table = 'visitante_grade';

    protected $primaryKey = 'id_visitante_grade';

    protected $fillable = [
        'visitante_id_visitante',
        'grade_id_grade',
        'visgra_data',
        'visgra_desc',
        'id_emp_id',
    ];

    protected $casts = [
        'visgra_data' => 'date',
    ];

    public function visitante()
    {
        return $this->belongsTo(Visitante::class, 'visitante_id_visitante', 'id_visitante');
    }

    public function grade()
    {
        return $this->belongsTo(GradeHorario::class, 'grade_id_grade', 'id_grade');
    }
}
