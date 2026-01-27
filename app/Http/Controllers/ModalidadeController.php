<?php

namespace App\Http\Controllers;

use App\Models\Modalidade;
use Illuminate\Http\Request;

class ModalidadeController extends Controller
{
    public function index()
    {
        $modalidades = Modalidade::all();

        return view('view_admin.modalidades', compact('modalidades'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'mod_nome' => 'required|string|max:100',
            'mod_desc' => 'required|string',
        ]);

        Modalidade::create($request->all());

        return redirect()->route('modalidades')
            ->with('success', 'Modalidade cadastrada com sucesso!');
    }

    public function show($id)
    {
        $modalidade = Modalidade::findOrFail($id);

        return response()->json([
            'data' => $modalidade
        ]);
    }

    public function edit($id)
    {
        $modalidade = Modalidade::findOrFail($id);

        return view('view_admin.modalidades_edit', compact('modalidade'));
    }


    public function update(Request $request, $id)
    {
        $modalidade = Modalidade::findOrFail($id);

        $request->validate([
            'mod_nome' => 'sometimes|string|max:100',
            'mod_desc' => 'sometimes|string',
        ]);

        $modalidade->update($request->all());

        return redirect()->route('modalidades')
            ->with('success', 'Modalidade atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $modalidade = Modalidade::findOrFail($id);
        $modalidade->delete();

        return redirect()->route('modalidades')
            ->with('success', 'Modalidade removida com sucesso!');
    }
}
