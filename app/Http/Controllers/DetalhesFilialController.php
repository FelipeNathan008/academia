<?php

namespace App\Http\Controllers;

use App\Models\DetalhesFilial;
use App\Models\Filial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class DetalhesFilialController extends Controller
{
    public function index($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $detalhes = DetalhesFilial::where('id_filial_id', $id)->get();
        $filial = Filial::findOrFail($id);
        $idFilial = $id;

        $temDetalhe = $detalhes->isNotEmpty();

        return view('view_controle.detalhes_filial', compact(
            'detalhes',
            'idFilial',
            'filial',
            'temDetalhe'
        ));
    }

    public function store(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $request->merge(['id_filial_id' => $id]);

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

        return redirect()->route('detalhes-filial.index', Crypt::encrypt($id))
            ->with('success', 'Detalhes cadastrados!');
    }


    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $detalhe = DetalhesFilial::findOrFail($id);
        $filial = $detalhe->filial;

        return view('view_controle.detalhes_filial_edit', compact('detalhe', 'filial'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

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

        return redirect()->route(
            'detalhes-filial.index',
            Crypt::encrypt($detalhe->id_filial_id)
        )->with('success', 'Detalhes atualizados!');
    }
    public function destroy($id)
    {
        $detalhe = DetalhesFilial::findOrFail($id);
        $detalhe->delete();

        return redirect()->back()->with('success', 'Detalhes da filial removidos com sucesso!');
    }
}
