<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoController extends Controller
{
    public function index()
    {
        $alunos = Aluno::with(['responsaveis', 'matriculas'])->get();

        return response()->json([
            'data' => $alunos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_nascimento' => 'required|date',
            'aluno_desc' => 'required|string|max:120',
            'aluno_foto' => 'required|image|max:2048',
        ]);

        $path = $request->file('aluno_foto')->store('alunos', 'public');

        $aluno = Aluno::create([
            'aluno_nome' => $request->aluno_nome,
            'aluno_nascimento' => $request->aluno_nascimento,
            'aluno_desc' => $request->aluno_desc,
            'aluno_foto' => $path,
        ]);

        return response()->json([
            'message' => 'Aluno criado com sucesso',
            'data' => $aluno
        ], 201);
    }

    public function show($id)
    {
        $aluno = Aluno::with(['responsaveis', 'matriculas', 'mensalidades'])
            ->findOrFail($id);

        return response()->json([
            'data' => $aluno
        ]);
    }

    public function update(Request $request, $id)
    {
        $aluno = Aluno::findOrFail($id);

        $request->validate([
            'aluno_nome' => 'sometimes|string|max:120',
            'aluno_nascimento' => 'sometimes|date',
            'aluno_desc' => 'sometimes|string|max:120',
            'aluno_foto' => 'sometimes|image|max:2048',
        ]);

        if ($request->hasFile('aluno_foto')) {
            if ($aluno->aluno_foto) {
                Storage::disk('public')->delete($aluno->aluno_foto);
            }

            $aluno->aluno_foto = $request->file('aluno_foto')->store('alunos', 'public');
        }

        $aluno->update($request->except('aluno_foto'));

        return response()->json([
            'message' => 'Aluno atualizado com sucesso',
            'data' => $aluno
        ]);
    }

    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);

        if ($aluno->aluno_foto) {
            Storage::disk('public')->delete($aluno->aluno_foto);
        }

        $aluno->delete();

        return response()->json([
            'message' => 'Aluno removido com sucesso'
        ], 204);
    }
}
