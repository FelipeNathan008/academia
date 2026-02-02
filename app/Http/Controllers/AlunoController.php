<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoController extends Controller
{
    public function index()
    {
        $alunos = Aluno::all();
        $graduacoes = Graduacao::all();
        $detalhes = DetalhesAluno::all();
        $modalidades = Modalidade::all();

        return view('view_alunos.index', compact('alunos', 'graduacoes', 'detalhes', 'modalidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_nascimento' => 'required|date',
            'aluno_desc' => 'required|string',
            'aluno_foto' => 'required|image|max:2048',
        ]);

        $aluno = new Aluno();
        $aluno->aluno_nome = $request->aluno_nome;
        $aluno->aluno_nascimento = $request->aluno_nascimento;
        $aluno->aluno_desc = $request->aluno_desc;

        if ($request->hasFile('aluno_foto')) {
            $file = $request->file('aluno_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos'), $filename);
            $aluno->aluno_foto = $filename;
        }

        $aluno->save();

        return redirect()->route('responsaveis.index', $aluno->id_aluno);
    }

    public function edit($id)
    {
        $aluno = Aluno::findOrFail($id);
        return view('view_alunos.edit', compact('aluno'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_nascimento' => 'required|date',
            'aluno_desc' => 'required|string',
            'aluno_foto' => 'nullable|image|max:2048',
        ]);

        $aluno = Aluno::findOrFail($id);
        $aluno->aluno_nome = $request->aluno_nome;
        $aluno->aluno_nascimento = $request->aluno_nascimento;
        $aluno->aluno_desc = $request->aluno_desc;

        if ($request->hasFile('aluno_foto')) {
            // Deletar foto antiga opcional
            if ($aluno->aluno_foto && file_exists(public_path('images/alunos/' . $aluno->aluno_foto))) {
                unlink(public_path('images/alunos/' . $aluno->aluno_foto));
            }

            $file = $request->file('aluno_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos'), $filename);
            $aluno->aluno_foto = $filename;
        }

        $aluno->save();

        return redirect()->route('alunos')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);

        $aluno->responsaveis()->delete();

        if ($aluno->aluno_foto && file_exists(public_path('images/alunos/' . $aluno->aluno_foto))) {
            unlink(public_path('images/alunos/' . $aluno->aluno_foto));
        }
        $aluno->delete();

        return redirect()->route('alunos')
            ->with('success', 'Aluno e responsáveis removidos com sucesso!');
    }
}
