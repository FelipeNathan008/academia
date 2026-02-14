<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\GradeHorario;
use App\Models\Matricula;
use App\Models\Professor;
use App\Models\Responsavel;
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

        $matriculas = Matricula::with(['professor', 'grade'])
            ->where('aluno_id_aluno', $id)
            ->get();

        $professores = Professor::whereIn(
            'id_professor',
            GradeHorario::pluck('professor_id_professor')
        )
            ->orderBy('prof_nome')
            ->get();

        return view('view_matricula.index', compact('aluno', 'matriculas', 'professores'));
    }

    public function indexSidebar()
    {
        $alunos = Aluno::with('responsavel')
            ->orderBy('aluno_nome')
            ->get();

        return view('view_matricula.index_sidebar', compact('alunos'));
    }


    public function getTurmasPorProfessor($professorId)
    {
        $turmas = GradeHorario::where('professor_id_professor', $professorId)
            ->orderBy('grade_turma')
            ->get([
                'id_grade',
                'grade_turma',
                'grade_dia_semana',
                'grade_inicio',
                'grade_fim',
                'grade_modalidade'
            ]);

        return response()->json($turmas);
    }


    public function store(Request $request, $alunoId)
    {
        $request->validate([
            'matri_data'     => 'required|date',
            'matri_desc'     => 'nullable|string|max:150',
            'matri_plano'    => 'required|string|max:40',
            'professor_id'   => 'required|exists:professor,id_professor',
            'matri_turma'    => 'required|exists:grade_horario,id_grade',
        ]);

        // Criar matrícula
        $matricula = Matricula::create([
            'aluno_id_aluno'  => $alunoId,
            'matri_status'    => 'Matriculado',
            'matri_data'      => $request->matri_data,
            'matri_plano'     => $request->matri_plano,
            'matri_professor' => $request->professor_id,
            'matri_turma'     => $request->matri_turma,
            'matri_desc'      => $request->matri_desc,
        ]);

        // Definir dia de vencimento (baseado na data da matrícula)
        $dataMatricula = Carbon::parse($request->matri_data);
        $diaOriginal = $dataMatricula->day;

        // Se for acima de 28, mantém salvo, mas o ajuste real
        // acontecerá na geração das parcelas
        $diaVencimento = $diaOriginal;

        // Buscar turma
        $grade = GradeHorario::findOrFail($request->matri_turma);

        // pegar o nome da modalidade (texto)
        $nomeModalidade = $grade->grade_modalidade;

        // buscar o ID da modalidade
        $modalidade = Modalidade::where('mod_nome', $nomeModalidade)->first();

        if (!$modalidade) {
            return back()->with('error', 'Modalidade não encontrada.');
        }

        // buscar preço usando plano + modalidade_id
        $preco = PrecoModalidade::where('preco_plano', $request->matri_plano)
            ->where('modalidade_id', $modalidade->id_modalidade)
            ->first();

        if (!$preco) {
            return back()->with('error', 'Preço não encontrado para esse plano e modalidade.');
        }

        $valorMensalidade = $preco->preco_modalidade;


        $mensalidade = Mensalidade::create([
            'aluno_id_aluno' => $alunoId,
            'matricula_id_matricula'  => $matricula->id_matricula,
            'mensa_dia_venc' => $diaVencimento,
            'mensa_valor'    => $valorMensalidade
        ]);

        $quantidadeParcelas = 1;

        switch (strtolower($request->matri_plano)) {
            case 'trimestral':
                $quantidadeParcelas = 3;
                break;

            case 'semestral':
                $quantidadeParcelas = 6;
                break;

            case 'anual':
                $quantidadeParcelas = 12;
                break;

            default: // mensal
                $quantidadeParcelas = 12;
                break;
        }

        //  Valor da parcela (NÃO divide mais)
        $valorParcela = $valorMensalidade;

        // Data base
        $dataBase = Carbon::parse($request->matri_data)->locale('pt_BR');

        //  Criar detalhes
        for ($i = 1; $i <= $quantidadeParcelas; $i++) {

            $dataVencimento = $dataBase
                ->copy()
                ->addMonthsNoOverflow($i)
                ->locale('pt_BR');


            DetalhesMensalidade::create([
                'mensalidade_id_mensalidade' => $mensalidade->id_mensalidade,
                'det_mensa_forma_pagamento'  => 'Pix',
                'det_mensa_mes_vigente'      => $dataVencimento->translatedFormat('F'),
                'det_mensa_data_venc'        => $dataVencimento->format('Y-m-d'),
                'det_mensa_valor'            => number_format($valorParcela, 2, '.', ''),
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
        $matricula = Matricula::with(['aluno', 'professor', 'grade'])
            ->findOrFail($id);

        return view('view_matricula.show', compact('matricula'));
    }



    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $request->validate([
            'aluno_id_aluno' => 'sometimes|exists:aluno,id_aluno',
            'matri_desc' => 'sometimes|string',
        ]);

        $matricula->update($request->all());

        return response()->json([
            'message' => 'Matrícula atualizada com sucesso',
            'data' => $matricula
        ]);
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
