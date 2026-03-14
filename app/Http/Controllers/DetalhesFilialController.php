<?php

namespace App\Http\Controllers;

use App\Models\DetalhesFilial;
use App\Models\Filial;
use Illuminate\Http\Request;

class DetalhesFilialController extends Controller
{
    public function index($id)
    {
        $detalhes = DetalhesFilial::where('id_filial_id', $id)->get();
        $filiais = Filial::all(); // necessário para selects no cadastro
        $idFilial = $id; // variável que vamos enviar
        return view('view_controle.detalhes_filial', compact('detalhes', 'filiais', 'idFilial'));
    }

    public function store(Request $request, $id)
    {
        $request->merge(['id_filial_id' => $id]); // garante que o detalhe fique vinculado
        $request->validate([
            'id_filial_id' => 'required|exists:filiais,id_filial|unique:detalhes_filiais,id_filial_id',
            'det_filial_cep' => 'required|string|max:15',
            'det_filial_logradouro' => 'required|string',
            'det_filial_numero' => 'required|string',
            'det_filial_complemento' => 'nullable|string',
            'det_filial_bairro' => 'required|string',
            'det_filial_cidade' => 'required|string',
            'det_filial_uf' => 'required|string|max:2',
            'det_filial_regiao' => 'required|string',
            'det_filial_pais' => 'required|string',
            'det_filial_cnpj' => 'nullable|string|max:20',
            'det_filial_email' => 'required|email',
            'det_filial_telefone' => 'required|string|max:20',
        ]);

        DetalhesFilial::create($request->all());

        return redirect()->route('detalhes-filial.index', $id)
            ->with('success', 'Detalhes da filial cadastrados com sucesso!');
    }


    public function edit($id)
    {
        $detalhe = DetalhesFilial::findOrFail($id);
        $filial = $detalhe->filial;

        return view('view_controle.detalhes_filial_edit', compact('detalhe', 'filial'));
    }

    public function update(Request $request, $id)
    {
        $detalhe = DetalhesFilial::findOrFail($id);

        $request->validate([
            'det_filial_cep' => 'required|string|max:15',
            'det_filial_logradouro' => 'required|string',
            'det_filial_numero' => 'required|string',
            'det_filial_complemento' => 'nullable|string',
            'det_filial_bairro' => 'required|string',
            'det_filial_cidade' => 'required|string',
            'det_filial_uf' => 'required|string|max:2',
            'det_filial_regiao' => 'required|string',
            'det_filial_pais' => 'required|string',
            'det_filial_cnpj' => 'nullable|string|max:20',
            'det_filial_email' => 'required|email',
            'det_filial_telefone' => 'required|string|max:20',
        ]);

        $detalhe->update($request->all());

        return redirect()->route('detalhes-filial.index', $detalhe->id_filial_id)
            ->with('success', 'Detalhes da filial atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $detalhe = DetalhesFilial::findOrFail($id);
        $detalhe->delete();

        return redirect()->back()->with('success', 'Detalhes da filial removidos com sucesso!');
    }
}
