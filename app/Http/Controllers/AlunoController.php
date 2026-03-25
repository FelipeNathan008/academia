<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class AlunoController extends Controller
{
    public function index($responsavelId)
    {
        try {
            $id = Crypt::decrypt($responsavelId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $responsavel = Responsavel::with('alunos')->findOrFail($id);

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
        $users = Auth::user();

        Aluno::create([
            'responsavel_id_responsavel' => $responsavelId,
            'aluno_nome' => $request->aluno_nome,
            'aluno_nascimento' => $request->aluno_nascimento,
            'aluno_bolsista' => $request->aluno_bolsista,
            'aluno_desc' => $request->aluno_desc,
            'aluno_foto' => $filename,

            'id_emp_id' => $users->id_emp_id,
        ]);

        return redirect()
            ->route('alunos', Crypt::encrypt($responsavelId))
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function edit($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::with('responsavel')
            ->where('id_emp_id', $user->id_emp_id)
            ->findOrFail($id);

        return view('view_alunos.edit', [
            'aluno' => $aluno,
            'responsavel' => $aluno->responsavel
        ]);
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
            ->route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel))
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
            ->route('alunos', Crypt::encrypt($responsavelId))->with('success', 'Aluno removido com sucesso!');
    }
}
