<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Responsavel;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    // LISTAR ALUNOS DE UM RESPONSÁVEL
    public function index($responsavelId)
    {
        $responsavel = Responsavel::with('alunos')->findOrFail($responsavelId);

        return view('view_alunos.index', [
            'responsavel' => $responsavel,
            'alunos' => $responsavel->alunos
        ]);
    }

    // CADASTRAR ALUNO PARA UM RESPONSÁVEL
    public function store(Request $request, $responsavelId)
    {
        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_nascimento' => 'required|date',
            'aluno_bolsista' => 'required|in:sim,nao',
            'aluno_desc' => 'required|string',
            'aluno_foto' => 'required|image|max:2048',
        ]);

        $filename = null;
        if ($request->hasFile('aluno_foto')) {
            $file = $request->file('aluno_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos'), $filename);
        }

        Aluno::create([
            'responsavel_id_responsavel' => $responsavelId,
            'aluno_nome' => $request->aluno_nome,
            'aluno_nascimento' => $request->aluno_nascimento,
            'aluno_bolsista' => $request->aluno_bolsista,
            'aluno_desc' => $request->aluno_desc,
            'aluno_foto' => $filename
        ]);

        return redirect()
            ->route('alunos', $responsavelId)
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $aluno = Aluno::findOrFail($id);
        $responsavel = $aluno->responsavel;


        return view('view_alunos.edit', compact('aluno', 'responsavel'));
    }

    public function update(Request $request, $id)
    {
        $aluno = Aluno::findOrFail($id);

        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_nascimento' => 'required|date',
            'aluno_bolsista' => 'required|in:sim,nao',
            'aluno_desc' => 'required|string',
            'aluno_foto' => 'nullable|image|max:2048',
        ]);

        // FOTO
        if ($request->hasFile('aluno_foto')) {
            if ($aluno->aluno_foto && file_exists(public_path('images/alunos/' . $aluno->aluno_foto))) {
                unlink(public_path('images/alunos/' . $aluno->aluno_foto));
            }

            $file = $request->file('aluno_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos'), $filename);

            $aluno->aluno_foto = $filename;
        }

        // ATUALIZAÇÃO DOS DADOS (SEM only)
        $aluno->aluno_nome = $request->aluno_nome;
        $aluno->aluno_nascimento = $request->aluno_nascimento;
        $aluno->aluno_bolsista = $request->aluno_bolsista;
        $aluno->aluno_desc = $request->aluno_desc;

        $aluno->save();

        return redirect()
            ->route('alunos', $aluno->responsavel_id_responsavel)
            ->with('success', 'Aluno atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);
        $responsavelId = $aluno->responsavel_id_responsavel;

        if ($aluno->aluno_foto && file_exists(public_path('images/alunos/' . $aluno->aluno_foto))) {
            unlink(public_path('images/alunos/' . $aluno->aluno_foto));
        }

        $aluno->delete();

        return redirect()
            ->route('alunos', $aluno->responsavel_id_responsavel)->with('success', 'Aluno removido com sucesso!');
    }
}
