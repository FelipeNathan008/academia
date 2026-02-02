<?php

namespace App\Http\Controllers;

use App\Models\Graduacao;
use Illuminate\Http\Request;

class GraduacaoController extends Controller
{
    public function index()
    {
        $graduacoes = Graduacao::orderByRaw("
            CASE
                WHEN LOWER(gradu_nome_cor) LIKE '%cinza e branca%' THEN 1
                WHEN LOWER(gradu_nome_cor) LIKE '%branca%' THEN 2
                WHEN LOWER(gradu_nome_cor) LIKE '%amarela%' THEN 3
                WHEN LOWER(gradu_nome_cor) LIKE '%laranja%' THEN 4
                WHEN LOWER(gradu_nome_cor) LIKE '%verde%' THEN 5
                WHEN LOWER(gradu_nome_cor) LIKE '%azul%' THEN 6
                WHEN LOWER(gradu_nome_cor) LIKE '%roxa%' THEN 7
                WHEN LOWER(gradu_nome_cor) LIKE '%marrom%' THEN 8
                WHEN LOWER(gradu_nome_cor) LIKE '%preta%' THEN 9
                ELSE 99
            END")->orderBy('gradu_grau')->get();

        return view('view_admin.graduacoes', compact('graduacoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gradu_nome_cor' => 'required|string|max:80',
            'gradu_grau'     => 'required|integer',
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
