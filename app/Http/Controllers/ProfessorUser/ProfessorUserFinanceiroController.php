<?php

namespace App\Http\Controllers\professorUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Mensalidade;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfessorUserFinanceiroController extends Controller
{
    public function index(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->with(['responsavel', 'matriculas.grade'])
            ->findOrFail($id);

        \App\Models\DetalhesMensalidade::where('det_mensa_status', 'Em aberto')
            ->whereDate('det_mensa_data_venc', '<', Carbon::today())
            ->where('id_emp_id', $user->id_emp_id)
            ->update([
                'det_mensa_status' => 'Atrasado'
            ]);

        $mensalidades = Mensalidade::with([
            'detalhes',
            'matricula.grade.professor'
        ])
            ->where('id_emp_id', $user->id_emp_id)
            ->whereHas('matricula', function ($q) use ($id, $professor) {
                $q->where('aluno_id_aluno', $id)
                    ->whereHas('grade', function ($g) use ($professor) {
                        $g->where('professor_id_professor', $professor->id_professor);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('view_professor_user.financeiro.index', compact('aluno', 'mensalidades'));
    }
}
