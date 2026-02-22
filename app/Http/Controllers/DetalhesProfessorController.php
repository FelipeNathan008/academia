<?php

namespace App\Http\Controllers;

use App\Models\DetalhesProfessor;
use App\Models\Graduacao;
use App\Models\Modalidade;
use App\Models\Professor;
use Illuminate\Http\Request;

class DetalhesProfessorController extends Controller
{
    public function index($id)
    {
        $professor = Professor::findOrFail($id);
        $modalidades = Modalidade::all();
        $graduacoes = DetalhesProfessor::where('professor_id_professor', $id)->ordenarPorFaixa()
            ->orderBy('det_grau')
            ->get();;
        $graduacoesTotais = Graduacao::ordenarPorFaixa()
            ->orderBy('gradu_grau')
            ->get();
        return view('view_professores.detalhes_professor', compact(
            'professor',
            'graduacoes',
            'graduacoesTotais',
            'modalidades'
        ));
    }


    public function store(Request $request, $id)
    {
        $request->validate([
            'det_gradu_nome_cor' => 'required|string|max:80',
            'det_grau'           => 'required|integer',
            'det_modalidade'     => 'required|string|max:50',
            'det_data'           => 'required|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // agora opcional
        ]);

        $dados = $request->all();
        $dados['professor_id_professor'] = $id;

        if ($request->hasFile('det_certificado')) {
            $file = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores-certificados'), $filename);
            $dados['det_certificado'] = 'images/professores-certificados/' . $filename;
        }

        DetalhesProfessor::create($dados);


        return redirect()->back()->with('success', 'Graduação do professor cadastrada!');
    }

    public function show($id)
    {
        $detalhe = DetalhesProfessor::with('professor')->findOrFail($id);

        return response()->json([
            'data' => $detalhe
        ]);
    }

    public function edit($id)
    {
        $detalhe = DetalhesProfessor::findOrFail($id);
        $professor = $detalhe->professor;
        $modalidades = Modalidade::all();
        $graduacoesTotais = Graduacao::all();

        return view('view_professores.detalhes_professor_edit', compact(
            'detalhe',
            'professor',
            'modalidades',
            'graduacoesTotais'
        ));
    }

    public function update(Request $request, $id)
    {
        $detalhe = DetalhesProfessor::findOrFail($id);

        $request->validate([
            'det_gradu_nome_cor' => 'required|string|max:80',
            'det_grau'           => 'required|integer',
            'det_modalidade'     => 'required|string|max:50',
            'det_data'           => 'required|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);


        $dados = $request->all();

        if ($request->hasFile('det_certificado')) {
            if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
                unlink(public_path($detalhe->det_certificado));
            }

            $file = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores-certificados'), $filename);
            $dados['det_certificado'] = 'images/professores-certificados/' . $filename;
        }

        $detalhe->update($dados);
        return redirect()->route('detalhes-professor.index', $detalhe->professor_id_professor)
            ->with('success', 'Graduação do professor atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $detalhe = DetalhesProfessor::findOrFail($id);

        // Apaga o arquivo do certificado, se existir
        if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
            unlink(public_path($detalhe->det_certificado));
        }

        $detalhe->delete();

        return redirect()->back()->with('success', 'Graduação removida com sucesso.');
    }
}
