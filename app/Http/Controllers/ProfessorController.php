<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Graduacao;
use App\Models\DetalhesProfessor;
use App\Models\Modalidade;

class ProfessorController extends Controller
{
    public function index()
    {
        $professores = Professor::all();
        $graduacoes = Graduacao::all();
        $detalhes = DetalhesProfessor::all();
            $modalidades = Modalidade::all();

        return view('view_professores.index', compact('professores', 'graduacoes', 'detalhes','modalidades'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'prof_telefone' => preg_replace('/\D/', '', $request->prof_telefone),
        ]);
        $request->validate([
            'prof_nome' => 'required|string|max:120',
            'prof_nascimento' => 'required|date',
            'prof_telefone' => 'required|string|max:20',
            'prof_desc' => 'required|string|max:150',
            'prof_foto' => 'nullable|image|max:2048',
        ]);

        $professor = new Professor();
        $professor->prof_nome = $request->prof_nome;
        $professor->prof_nascimento = $request->prof_nascimento;
        $professor->prof_telefone = $request->prof_telefone;
        $professor->prof_desc = $request->prof_desc;

        if ($request->hasFile('prof_foto')) {
            $file = $request->file('prof_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores'), $filename);
            $professor->prof_foto = $filename;
        }

        $professor->save();

        return redirect()->route('professores')->with('success', 'Professor cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $professor = Professor::findOrFail($id);
        return view('view_professores.edit', compact('professor'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'prof_telefone' => preg_replace('/\D/', '', $request->prof_telefone),
        ]);
        $request->validate([
            'prof_nome' => 'required|string|max:120',
            'prof_nascimento' => 'required|date',
            'prof_telefone' => 'required|string|max:20',
            'prof_desc' => 'required|string|max:150',
            'prof_foto' => 'nullable|image|max:2048',
        ]);

        $professor = Professor::findOrFail($id);

        $professor->prof_nome = $request->prof_nome;
        $professor->prof_nascimento = $request->prof_nascimento;
        $professor->prof_telefone = $request->prof_telefone;
        $professor->prof_desc = $request->prof_desc;

        if ($request->hasFile('prof_foto')) {
            // Deletar foto antiga opcional
            if ($professor->prof_foto && file_exists(public_path('images/professores/' . $professor->prof_foto))) {
                unlink(public_path('images/professores/' . $professor->prof_foto));
            }

            $file = $request->file('prof_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores'), $filename);
            $professor->prof_foto = $filename;
        }

        $professor->save();

        return redirect()->route('professores')->with('success', 'Professor atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        $professor->detalhes()->delete();
        
        if ($professor->prof_foto && file_exists(public_path('images/professores/' . $professor->prof_foto))) {
            unlink(public_path('images/professores/' . $professor->prof_foto));
        }

        $professor->delete();

        return redirect()->route('professores')
            ->with('success', 'Professor e graduações removidos com sucesso!');
    }
}
