<?php

namespace App\Http\Controllers;

use App\Models\Graduacao;
use Illuminate\Http\Request;

class GraduacaoController extends Controller
{
    public function index()
    {
        $graduacoes = Graduacao::ordenarPorFaixa()
            ->orderBy('gradu_grau')
            ->get();

        return view('view_admin.graduacoes', compact('graduacoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gradu_nome_cor' => 'required|string|max:80',
            'gradu_grau'     => 'required|integer',
            'gradu_meta'     => 'required|string|max:50',
        ]);

        $graduacao = Graduacao::create($request->all());

        return redirect()->route('graduacoes')->with('success', 'Graduação cadastrada com sucesso!');
    }

    public function show($id)
    {
        $graduacao = Graduacao::findOrFail($id);

        return response()->json([
            'data' => $graduacao
        ]);
    }

    public function edit($id)
    {
        $graduacao = Graduacao::findOrFail($id);
        return view('view_admin.graduacoes_edit', compact('graduacao'));
    }



    public function update(Request $request, $id)
    {
        $graduacao = Graduacao::findOrFail($id);

        $request->validate([
            'gradu_nome_cor' => 'sometimes|string|max:80',
            'gradu_grau'     => 'sometimes|integer',
            'gradu_meta'     => 'required|string|max:50',
        ]);

        $graduacao->update($request->all());

        return redirect()->route('graduacoes')->with('success', 'Graduação atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $graduacao = Graduacao::findOrFail($id);
        $graduacao->delete();

        return redirect()->route('graduacoes')->with('success', 'Graduação atualizada com sucesso!');
    }
}
