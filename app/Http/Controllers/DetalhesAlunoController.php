<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\Modalidade;
use App\Models\Responsavel;
use Illuminate\Http\Request;

class DetalhesAlunoController extends Controller
{
    public function index($id)
    {
        $aluno = Aluno::findOrFail($id);
        $modalidades = Modalidade::all();

        $graduacoes = DetalhesAluno::where('aluno_id_aluno', $id)
            ->orderByRaw("
            CASE
                WHEN LOWER(det_gradu_nome_cor) LIKE '%cinza e branca%' THEN 1
                WHEN LOWER(det_gradu_nome_cor) LIKE '%branca%' THEN 2
                WHEN LOWER(det_gradu_nome_cor) LIKE '%amarela%' THEN 3
                WHEN LOWER(det_gradu_nome_cor) LIKE '%laranja%' THEN 4
                WHEN LOWER(det_gradu_nome_cor) LIKE '%verde%' THEN 5
                WHEN LOWER(det_gradu_nome_cor) LIKE '%azul%' THEN 6
                WHEN LOWER(det_gradu_nome_cor) LIKE '%roxa%' THEN 7
                WHEN LOWER(det_gradu_nome_cor) LIKE '%marrom%' THEN 8
                WHEN LOWER(det_gradu_nome_cor) LIKE '%preta%' THEN 9
                ELSE 99
            END
        ")
            ->orderBy('det_grau')
            ->get();

        $graduacoesTotais = Graduacao::all();
        $responsavel = $aluno->responsavel;

        return view('view_alunos.detalhes_aluno', compact(
            'aluno',
            'graduacoes',
            'graduacoesTotais',
            'modalidades',
            'responsavel'
        ));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'det_gradu_nome_cor' => 'required|string|max:80',
            'det_grau'           => 'required|integer',
            'det_modalidade'     => 'required|string|max:100',
            'det_data'           => 'required|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $dados = [
            'aluno_id_aluno'     => $id,
            'det_gradu_nome_cor' => $request->det_gradu_nome_cor,
            'det_grau'           => $request->det_grau,
            'det_modalidade'     => $request->det_modalidade,
            'det_data'           => $request->det_data,
        ];

        if ($request->hasFile('det_certificado')) {
            $file = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos-certificados'), $filename);
            $dados['det_certificado'] = 'images/alunos-certificados/' . $filename;
        }

        DetalhesAluno::create($dados); // Agora vai salvar o caminho corretamente

        return redirect()->back()->with('success', 'Graduação do aluno cadastrada com sucesso!');
    }


    public function show($id)
    {
        $detalhe = DetalhesAluno::with('aluno')->findOrFail($id);

        return response()->json([
            'data' => $detalhe
        ]);
    }

    public function edit($id)
    {
        $detalhe = DetalhesAluno::findOrFail($id);
        $aluno = $detalhe->aluno;
        $modalidades = Modalidade::all();
        $graduacoesTotais = Graduacao::all();

        return view('view_alunos.detalhes_aluno_edit', compact(
            'detalhe',
            'aluno',
            'modalidades',
            'graduacoesTotais'
        ));
    }

    public function update(Request $request, $id)
    {
        $detalhe = DetalhesAluno::findOrFail($id);

        $request->validate([
            'det_gradu_nome_cor' => 'sometimes|string|max:80',
            'det_grau'           => 'sometimes|integer',
            'det_modalidade'     => 'sometimes|string|max:100',
            'det_data'           => 'sometimes|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $dados = $request->only(['det_gradu_nome_cor', 'det_grau', 'det_modalidade', 'det_data']);

        if ($request->hasFile('det_certificado')) {
            // Remove arquivo antigo
            if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
                unlink(public_path($detalhe->det_certificado));
            }

            $file = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos-certificados'), $filename);
            $dados['det_certificado'] = 'images/alunos-certificados/' . $filename;
        }

        $detalhe->update($dados);

        return redirect()->route('detalhes-aluno.index', $detalhe->aluno_id_aluno)
            ->with('success', 'Graduação do professor atualizada com sucesso!');
    }


    public function destroy($id)
    {
        $detalhe = DetalhesAluno::findOrFail($id);

        // Apaga o arquivo do certificado, se existir
        if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
            unlink(public_path($detalhe->det_certificado));
        }

        $detalhe->delete();

        return redirect()->back()->with('success', 'Graduação removida com sucesso.');
    }
}
