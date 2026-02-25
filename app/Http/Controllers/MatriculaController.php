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

class MatriculaController extends Controller
{
    public function index($id)
    {
        $aluno = Aluno::findOrFail($id);

        $matriculas = Matricula::with(['grade.professor'])
            ->where('aluno_id_aluno', $id)
            ->get();

        // AGORA BUSCA DIRETO AS GRADES
        $grades = GradeHorario::with('professor')
            ->orderBy('grade_turma')
            ->get();

        return view('view_matricula.index', compact('aluno', 'matriculas', 'grades'));
    }

    public function indexSidebar()
    {
        $alunos = Aluno::with('responsavel', 'matriculas')
            ->orderBy('aluno_nome')
            ->get();

        return view('view_matricula.index_sidebar', compact('alunos'));
    }

    public function store(Request $request, $alunoId)
    {
        $request->validate([
            'matri_data'      => 'required|date',
            'matri_desc'      => 'nullable|string|max:150',
            'matri_plano'     => 'required|string|max:40',
            'grade_id_grade'  => 'required|exists:grade_horario,id_grade',
        ]);

        $matricula = Matricula::create([
            'aluno_id_aluno' => $alunoId,
            'matri_status'   => 'Matriculado',
            'matri_data'     => $request->matri_data,
            'matri_plano'    => $request->matri_plano,
            'grade_id_grade' => $request->grade_id_grade,
            'matri_desc'     => $request->matri_desc,
        ]);

        $aluno = Aluno::findOrFail($alunoId);

        if (strtolower($aluno->aluno_bolsista) === 'sim') {
            return redirect()
                ->route('matricula', $alunoId)
                ->with('success', 'Matrícula realizada com sucesso! (Aluno bolsista - sem geração de financeiro)');
        }

        $dataMatricula = Carbon::parse($request->matri_data);
        $diaVencimento = $dataMatricula->day;

        $grade = GradeHorario::findOrFail($request->grade_id_grade);
        $modalidade = Modalidade::where('mod_nome', $grade->grade_modalidade)->first();

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
            'aluno_id_aluno'         => $alunoId,
            'matricula_id_matricula' => $matricula->id_matricula,
            'mensa_dia_venc'         => $diaVencimento,
            'mensa_valor'            => $valorMensalidade
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
                'det_mensa_status'           => 'Em aberto'
            ]);
        }

        return redirect()
            ->route('matricula', $alunoId)
            ->with('success', 'Matrícula realizada com sucesso!');
    }

    public function show($id)
    {
        $matricula = Matricula::with(['aluno', 'grade.professor'])
            ->findOrFail($id);

        return view('view_matricula.show', compact('matricula'));
    }

    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        $alunoId = $matricula->aluno_id_aluno;

        $matricula->update([
            'matri_status' => 'Encerrada'
        ]);

        return redirect()
            ->route('matricula', $alunoId)
            ->with('success', 'Matrícula encerrada com sucesso!');
    }
}