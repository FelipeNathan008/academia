<?php

namespace App\Http\Controllers;

use App\Models\Modalidade;
use App\Models\PrecoModalidade;
use Illuminate\Http\Request;

class PrecoModalidadeController extends Controller
{
    public function index()
    {
        $valores = PrecoModalidade::with('modalidade')->get();
        $modalidades = Modalidade::all();

        return view('view_admin.preco_modalidades', compact('valores', 'modalidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'modalidade_id'    => 'required|exists:modalidade,id_modalidade',
            'preco_modalidade' => 'required|numeric|min:0',
            'preco_plano'      => 'required|string|max:40',
        ]);

        PrecoModalidade::create([
            'modalidade_id'    => $request->modalidade_id,
            'preco_modalidade' => $request->preco_modalidade,
            'preco_plano'      => $request->preco_plano,
        ]);

        return redirect()->route('preco-aula')
            ->with('success', 'Valor cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $valor = PrecoModalidade::findOrFail($id);
        $modalidades = Modalidade::all();

        return view('view_admin.preco_modalidades_edit', compact('valor', 'modalidades'));
    }

    public function update(Request $request, $id)
    {
        $valor = PrecoModalidade::findOrFail($id);

        $request->validate([
            'modalidade_id'    => 'required|exists:modalidade,id_modalidade',
            'preco_modalidade' => 'required|numeric|min:0',
            'preco_plano'      => 'required|string|max:40',
        ]);

        $valor->update([
            'modalidade_id'    => $request->modalidade_id,
            'preco_modalidade' => $request->preco_modalidade,
            'preco_plano'      => $request->preco_plano,
        ]);

        return redirect()->route('preco-aula')
            ->with('success', 'Valor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $valor = PrecoModalidade::findOrFail($id);
        $valor->delete();

        return redirect()->route('preco-aula')
            ->with('success', 'Valor removido com sucesso!');
    }
}
