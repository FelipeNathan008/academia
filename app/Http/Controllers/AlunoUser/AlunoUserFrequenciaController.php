<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AlunoUserFrequenciaController extends Controller
{
    public function visualizar($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $matricula = Matricula::with([
            'aluno.responsavel',
            'grade.professor',
            'frequencias'
        ])
            ->where('id_matricula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('aluno', function ($query) use ($responsavel) {
                $query->where('responsavel_id_responsavel', $responsavel->id_responsavel);
            })
            ->firstOrFail();

        $grade = $matricula->grade;

        if (!$grade) {
            abort(404);
        }

        $totalAulas = $matricula->frequencias->count();
        $totalPresencasGeral = $matricula->frequencias->where('freq_presenca', 'Presente')->count();
        $totalFaltasGeral = $matricula->frequencias->where('freq_presenca', 'Falta')->count();

        $detalhes = DetalhesAluno::where('aluno_id_aluno', $matricula->aluno->id_aluno)
            ->where('det_modalidade', $grade->grade_modalidade)
            ->ordenarPorFaixaInverso()
            ->orderByDesc('det_grau')
            ->first();

        $meta = 0;

        if ($detalhes) {
            $metaGraduacao = Graduacao::where('gradu_nome_cor', $detalhes->det_gradu_nome_cor)
                ->where('gradu_grau', $detalhes->det_grau)
                ->first();

            $meta = $metaGraduacao->gradu_meta ?? 0;
        }

        $percentual = $totalAulas > 0
            ? round(($totalPresencasGeral / $totalAulas) * 100)
            : 0;

        $barra = $meta > 0
            ? min(100, round(($totalPresencasGeral / $meta) * 100))
            : 0;

        return view('view_aluno_user.frequencia.visualizar', compact(
            'matricula',
            'grade',
            'totalAulas',
            'totalPresencasGeral',
            'totalFaltasGeral',
            'detalhes',
            'meta',
            'percentual',
            'barra'
        ));
    }
}
