<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\ProcessaFotoTrait;
use App\Models\Graduacao;
use App\Models\DetalhesProfessor;
use App\Models\Modalidade;
use App\Models\GradeHorario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Date;


class ProfessorController extends Controller
{
    use ProcessaFotoTrait;

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Professor::with('detalhes')
            ->where('id_emp_id', $user->id_emp_id);

        if ($request->filled('busca')) {
            $busca = trim($request->input('busca'));
            $query->where('prof_nome', 'like', '%' . $busca . '%');
        }

        if ($request->input('graduacao') === 'sim') {
            $query->whereHas('detalhes');
        } elseif ($request->input('graduacao') === 'nao') {
            $query->whereDoesntHave('detalhes');
        }

        $professores = $query
            ->orderBy('prof_nome')
            ->paginate(10)
            ->withQueryString();

        $graduacoes = Graduacao::select('gradu_nome_cor')
            ->selectRaw('MAX(gradu_grau) as max_grau')
            ->where('id_emp_id', $user->id_emp_id)
            ->groupBy('gradu_nome_cor')
            ->orderByRaw("
            CASE gradu_nome_cor
                WHEN 'Faixa Branca' THEN 1
                WHEN 'Faixa Azul' THEN 2
                WHEN 'Faixa Roxa' THEN 3
                WHEN 'Faixa Marrom' THEN 4
                WHEN 'Faixa Preta' THEN 5
                ELSE 99
            END
        ")
            ->get();

        $detalhes = DetalhesProfessor::all();
        $modalidades = Modalidade::all();

        foreach ($professores as $professor) {
            $professor->grades = DB::table('grade_horario')
                ->where('professor_id_professor', $professor->id_professor)
                ->get();

            $professor->qtd_aluno = DB::table('matricula as m')
                ->join('grade_horario as g', 'm.grade_id_grade', '=', 'g.id_grade')
                ->where('g.professor_id_professor', $professor->id_professor)
                ->where('m.matri_status', 'Matriculado')
                ->where('m.id_emp_id', $user->id_emp_id)
                ->where('g.id_emp_id', $user->id_emp_id)
                ->distinct('m.aluno_id_aluno')
                ->count('m.aluno_id_aluno');

            $professor->alunos = DB::table('aluno as a')
                ->join('matricula as m', 'a.id_aluno', '=', 'm.aluno_id_aluno')
                ->join('grade_horario as g', 'm.grade_id_grade', '=', 'g.id_grade')
                ->leftJoin('responsavel as r', 'a.responsavel_id_responsavel', '=', 'r.id_responsavel')
                ->where('g.professor_id_professor', $professor->id_professor)
                ->where('m.matri_status', 'Matriculado')
                ->select(
                    'a.id_aluno',
                    'a.aluno_nome',
                    'a.aluno_nascimento',
                    'a.aluno_bolsista',
                    'a.aluno_foto',
                    'g.id_grade',
                    'g.grade_modalidade',
                    'g.grade_turma',
                    'g.grade_inicio',
                    'g.grade_fim',
                    'g.grade_dia_semana',
                    'r.id_responsavel',
                    DB::raw("EXISTS (
                    SELECT 1 FROM mensalidade me
                    JOIN detalhes_mensalidade d ON me.id_mensalidade = d.mensalidade_id_mensalidade
                    WHERE me.aluno_id_aluno = a.id_aluno
                    AND d.det_mensa_data_venc < CURDATE()
                    AND d.det_mensa_status != 'Pago'
                ) as atrasado"),
                    DB::raw("EXISTS (
                    SELECT 1 FROM matricula m2
                    WHERE m2.aluno_id_aluno = a.id_aluno
                    AND m2.matri_status = 'Matriculado'
                ) as matriculado")
                )
                ->get();
        }

        $professoresEmpresa = Professor::with('empresas')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        return view(
            'view_admin_user.view_principal.view_professores.index',
            compact('professores', 'professoresEmpresa', 'graduacoes', 'detalhes', 'modalidades')
        );
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $request->merge([
            'prof_telefone' => preg_replace('/\D/', '', $request->prof_telefone),
            'id_emp_id' => $user->id_emp_id
        ]);

        $request->validate([
            'prof_nome' => 'required|string|max:120',
            'prof_nascimento' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(130)->format('Y-m-d'),
            ],
            'prof_telefone' => 'required|string|max:20',
            'prof_desc' => 'required|string',
            'prof_foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'prof_nascimento.required' => 'Informe a data de nascimento do professor.',
            'prof_nascimento.date_format' => 'Informe uma data de nascimento válida.',
            'prof_nascimento.before_or_equal' => 'O professor deve ter pelo menos 16 anos.',
            'prof_nascimento.after_or_equal' => 'A data de nascimento informada não é válida. Verifique se a idade não ultrapassa 130 anos.',
        ]);
        $filename = null;

        if ($request->hasFile('prof_foto')) {
            $filename = $this->processarFoto($request->file('prof_foto'), 'professores');
        }

        Professor::create([
            'prof_nome' => $request->prof_nome,
            'prof_nascimento' => $request->prof_nascimento,
            'prof_telefone' => $request->prof_telefone,
            'prof_desc' => $request->prof_desc,
            'prof_foto' => $filename,
            'id_emp_id' => $user->id_emp_id
        ]);

        return redirect()
            ->route('professores')
            ->with('success', 'Professor cadastrado com sucesso!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();
        return view('view_admin_user.view_principal.view_professores.edit', compact('professor'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->merge([
            'prof_telefone' => preg_replace('/\D/', '', $request->prof_telefone),
        ]);

        $request->validate([
            'prof_nome' => 'required|string|max:120',
            'prof_nascimento' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(130)->format('Y-m-d'),
            ],
            'prof_telefone' => 'required|string|max:20',
            'prof_desc' => 'required|string',
            'prof_foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'prof_nascimento.required' => 'Informe a data de nascimento do professor.',
            'prof_nascimento.date_format' => 'Informe uma data de nascimento válida.',
            'prof_nascimento.before_or_equal' => 'O professor deve ter pelo menos 16 anos.',
            'prof_nascimento.after_or_equal' => 'A data de nascimento informada não é válida. Verifique se a idade não ultrapassa 130 anos.',
        ]);
        // FOTO
        if ($request->hasFile('prof_foto')) {
            $this->removerFoto('professores', $professor->prof_foto);

            $filename = $this->processarFoto($request->file('prof_foto'), 'professores');
            $professor->prof_foto = $filename;
        }

        $professor->update([
            'prof_nome' => $request->prof_nome,
            'prof_nascimento' => $request->prof_nascimento,
            'prof_telefone' => $request->prof_telefone,
            'prof_desc' => $request->prof_desc,
        ]);

        return redirect()->route('professores')
            ->with('success', 'Professor atualizado com sucesso!');
    }
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin_user.view_principal.view_professores.show', compact('professor'));
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        // Verifica se o professor possui alguma grade vinculada
        $possuiGrade = GradeHorario::where('professor_id_professor', $professor->id_professor)
            ->exists();

        if ($possuiGrade) {
            return redirect()
                ->route('professores')
                ->with('error', 'Não é possível excluir este professor, pois ele possui uma ou mais turmas (grades de horário) vinculadas.');
        }

        // Remove graduações
        $professor->detalhes()->delete();

        // Remove foto
        $this->removerFoto('professores', $professor->prof_foto);

        // Remove professor
        $professor->delete();

        return redirect()
            ->route('professores')
            ->with('success', 'Professor removido com sucesso!');
    }
}
