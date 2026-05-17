<?php

namespace App\Http\Controllers\professorUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\DetalhesAluno;
use App\Models\Mensalidade;
use App\Models\DetalhesMensalidade;
use App\Models\FrequenciaAluno;
use App\Models\GradeHorario;
use App\Models\Graduacao;
use App\Models\Modalidade;
use App\Models\Responsavel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class ProfessorUserResponsavelController extends Controller
{
    public function show(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->with('responsavel')
            ->findOrFail($id);

        $responsavel = $aluno->responsavel;

        return view('view_professor_user.responsavel.show', compact(
            'responsavel',
            'aluno'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $responsavel = Responsavel::where('id_responsavel', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->merge([
            'resp_cpf' => preg_replace('/\D/', '', $request->resp_cpf),
            'resp_cep' => preg_replace('/\D/', '', $request->resp_cep),
        ]);

        $request->validate([
            'resp_nome' => 'required|string|max:120',
            'resp_parentesco' => 'required|string|max:60',
            'resp_cpf' => 'required|string|size:11',
            'resp_telefone' => 'required|string|max:20',
            'resp_email' => 'required|email|max:150',
            'resp_cep' => 'required|digits:8',
            'resp_logradouro' => 'required|string|max:150',
            'resp_numero' => 'nullable|string|max:10',
            'resp_complemento' => 'nullable|string|max:100',
            'resp_bairro' => 'required|string|max:150',
            'resp_cidade' => 'required|string|max:150',
        ]);

        $jaExiste = Responsavel::where('resp_cpf', $request->resp_cpf)
            ->where('id_responsavel', '!=', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'resp_cpf' => 'Erro, Já existe esse CPF cadastrado.'
            ])->withInput();
        }

        $responsavel->update($request->all());

        return back()->with('success', 'Responsável atualizado com sucesso!');
    }
}
