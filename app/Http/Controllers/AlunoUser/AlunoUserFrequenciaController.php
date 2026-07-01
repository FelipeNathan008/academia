<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\FrequenciaAluno;
use Illuminate\Support\Facades\DB;

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

        $detalhes = DetalhesAluno::with('graduacao')
            ->where('aluno_id_aluno', $matricula->aluno->id_aluno)
            ->whereHas('graduacao.modalidade', function ($q) use ($grade) {
                $q->where('mod_nome', $grade->grade_modalidade);
            })
            ->orderByDesc('det_data')
            ->orderByDesc(
                Graduacao::select('gradu_ordem')
                    ->whereColumn('graduacao.id_graduacao', 'detalhes_aluno.id_graduacao')
                    ->limit(1)
            )
            ->first();

        $meta = 0;

        if ($detalhes && $detalhes->graduacao) {
            $meta = $detalhes->graduacao->gradu_meta ?? 0;
        }

        $percentual = $totalAulas > 0
            ? round(($totalPresencasGeral / $totalAulas) * 100)
            : 0;

        $barra = $meta > 0
            ? min(100, round(($totalPresencasGeral / $meta) * 100))
            : 0;

        $anoSelecionado = request()->get('ano', now()->year);

        $anosDisponiveis = FrequenciaAluno::selectRaw('YEAR(freq_data_aula) as ano')
            ->where('matricula_id_matricula', $matricula->id_matricula)
            ->where('id_emp_id', $user->id_emp_id)
            ->distinct()
            ->orderByDesc('ano')
            ->pluck('ano');

        $frequenciaMensal = FrequenciaAluno::selectRaw("
        YEAR(freq_data_aula) as ano,
        MONTH(freq_data_aula) as mes,
        freq_presenca,
        COUNT(*) as total
    ")
            ->where('matricula_id_matricula', $matricula->id_matricula)
            ->where('id_emp_id', $user->id_emp_id)
            ->whereYear('freq_data_aula', $anoSelecionado)
            ->groupBy(
                DB::raw('YEAR(freq_data_aula)'),
                DB::raw('MONTH(freq_data_aula)'),
                'freq_presenca'
            )
            ->orderBy('mes')
            ->get();

        return view('view_aluno_user.frequencia.visualizar', compact(
            'matricula',
            'grade',
            'totalAulas',
            'totalPresencasGeral',
            'totalFaltasGeral',
            'detalhes',
            'meta',
            'percentual',
            'barra',
            'frequenciaMensal',
            'anosDisponiveis',
            'anoSelecionado'
        ));
    }
}
