<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\GradeHorario;
use App\Models\Matricula;
use Illuminate\Http\Request;
use App\Models\Mensalidade;
use App\Models\Modalidade;
use App\Models\PrecoModalidade;
use App\Models\DetalhesMensalidade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class MatriculaController extends Controller
{
    public function index($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $matriculas = Matricula::with(['grade.professor'])
            ->where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $grades = GradeHorario::with('professor')
            ->where('id_emp_id', $user->id_emp_id)
            ->orderBy('grade_turma')
            ->get();

        return view('view_matricula.index', compact('aluno', 'matriculas', 'grades'));
    }

    public function indexSidebar(Request $request)
    {
        $user = Auth::user();

        $query = Aluno::with([
            'responsavel',
            'matriculas' => function ($q) use ($user) {
                $q->where('id_emp_id', $user->id_emp_id)
                    ->where('matri_status', 'Matriculado')
                    ->with([
                        'grade',
                        'mensalidades.detalhes' 
                    ]);
            }
        ])->where('id_emp_id', $user->id_emp_id);

        // FILTRO NOME
        if ($request->filled('nome')) {
            $query->where('aluno_nome', 'like', '%' . $request->nome . '%');
        }

        // FILTRO RESPONSÁVEL
        if ($request->filled('responsavel')) {
            $query->whereHas('responsavel', function ($q) use ($request) {
                $q->where('resp_nome', 'like', '%' . $request->responsavel . '%');
            });
        }

        // FILTRO BOLSISTA
        if ($request->filled('bolsista')) {
            $query->where('aluno_bolsista', $request->bolsista);
        }

        // FILTRO MATRÍCULA
        if ($request->filled('matricula')) {
            if ($request->matricula === 'Matriculado') {
                $query->whereHas('matriculas');
            } elseif ($request->matricula === 'Encerrada') {
                $query->whereDoesntHave('matriculas');
            }
        }
        $totalAlunos = Aluno::where('id_emp_id', $user->id_emp_id)->count();
        $alunos = $query
            ->orderBy('aluno_nome')
            ->paginate(10)
            ->withQueryString();

        return view('view_matricula.index_sidebar', compact('alunos', 'totalAlunos'));
    }

    public function store(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'matri_data'      => 'required|date',
            'matri_desc'      => 'nullable|string|max:150',
            'matri_plano'     => 'required|string|max:40',
            'grade_id_grade'  => 'required|exists:grade_horario,id_grade',
        ]);

        $jaExiste = Matricula::where('aluno_id_aluno', $id)
            ->where('grade_id_grade', $request->grade_id_grade)
            ->where('matri_status', 'Matriculado')
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'grade_id_grade' => 'Erro, aluno já matriculado nessa turma.'
            ])->withInput();
        }
        $users = Auth::user();

        $jaExiste = Matricula::where('aluno_id_aluno', $id)
            ->where('grade_id_grade', $request->grade_id_grade)
            ->where('matri_status', 'Matriculado')
            ->exists();

        if ($jaExiste) {
            return back()->with('error', 'Esse aluno já está matriculado nessa turma.');
        }

        $matricula = Matricula::create([
            'aluno_id_aluno' => $id,
            'matri_status'   => 'Matriculado',
            'matri_data'     => $request->matri_data,
            'matri_plano'    => $request->matri_plano,
            'grade_id_grade' => $request->grade_id_grade,
            'matri_desc'     => $request->matri_desc,
            'id_emp_id' => $users->id_emp_id

        ]);

        $aluno = Aluno::findOrFail($id);

        if (strtolower($aluno->aluno_bolsista) === 'sim') {
            return redirect()
                ->route('matricula', Crypt::encrypt($id))
                ->with('success', 'Matrícula realizada com sucesso! (Aluno bolsista - sem geração de financeiro)');
        }

        $dataMatricula = Carbon::parse($request->matri_data);
        $diaVencimento = $dataMatricula->day;

        $grade = GradeHorario::where('id_grade', $request->grade_id_grade)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $modalidade = Modalidade::where('mod_nome', $grade->grade_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->first();


        if (!$modalidade) {
            return back()->with('error', 'Modalidade não encontrada.');
        }

        $preco = PrecoModalidade::where('preco_plano', $request->matri_plano)
            ->where('modalidade_id', $modalidade->id_modalidade)
            ->first();

        if (!$preco) {
            return back()->with('error', 'Preço não encontrado para esse plano e modalidade.');
        }

        $valorMensalidade = $preco->preco_modalidade;

        $mensalidade = Mensalidade::create([
            'aluno_id_aluno'         => $id,
            'matricula_id_matricula' => $matricula->id_matricula,
            'mensa_dia_venc'         => $diaVencimento,
            'mensa_valor'            => $valorMensalidade,
            'id_emp_id'             => $users->id_emp_id
        ]);

        $quantidadeParcelas = match (strtolower($request->matri_plano)) {
            'trimestral' => 3,
            'semestral'  => 6,
            'anual'      => 12,
            default      => 12,
        };

        $dataBase = Carbon::parse($request->matri_data)->locale('pt_BR');

        for ($i = 1; $i <= $quantidadeParcelas; $i++) {

            $dataVencimento = $dataBase
                ->copy()
                ->addMonthsNoOverflow($i);

            DetalhesMensalidade::create([
                'mensalidade_id_mensalidade' => $mensalidade->id_mensalidade,
                'det_mensa_forma_pagamento'  => 'Pix',
                'det_mensa_mes_vigente'      => $dataVencimento->translatedFormat('F'),
                'det_mensa_data_venc'        => $dataVencimento->format('Y-m-d'),
                'det_mensa_valor'            => number_format($valorMensalidade, 2, '.', ''),
                'det_mensa_data_pagamento'   => null,
                'det_mensa_status'           => 'Em aberto',
                'id_emp_id'                  => $users->id_emp_id
            ]);
        }

        return redirect()
            ->route('matricula', Crypt::encrypt($id))
            ->with('success', 'Matrícula realizada com sucesso!');
    }

    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $matricula = Matricula::with(['aluno', 'grade.professor'])
            ->findOrFail($id);

        return view('view_matricula.show', compact('matricula'));
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $matricula = Matricula::where('id_matricula', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $alunoId = $matricula->aluno_id_aluno;

        $matricula->update([
            'matri_status' => 'Encerrada'
        ]);

        return redirect()
            ->route('matricula', Crypt::encrypt($alunoId))
            ->with('success', 'Matrícula encerrada com sucesso!');
    }
}
