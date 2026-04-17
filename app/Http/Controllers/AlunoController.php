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
        $user = Auth::user();

        $responsavel = Responsavel::where('id_responsavel', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->with('alunos')
            ->firstOrFail();

        return view('view_alunos.index', [
            'responsavel' => $responsavel,
            'alunos' => $responsavel->alunos
        ]);
    }


    // CADASTRAR ALUNO PARA UM RESPONSÁVEL
    public function store(Request $request, $responsavelId)
    {

        try {
            $id = Crypt::decrypt($responsavelId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_parentesco' => 'required|string|max:60',
            'aluno_nascimento' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(6)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],
            'aluno_bolsista' => 'required|in:sim,nao',
            'aluno_desc' => 'required|string',
            'aluno_foto' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        $responsavel = Responsavel::where('id_responsavel', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $filename = null;
        if ($request->hasFile('aluno_foto')) {
            $file = $request->file('aluno_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos'), $filename);
        }
        $users = Auth::user();

        Aluno::create([
            'responsavel_id_responsavel' => $responsavel->id_responsavel,
            'aluno_nome' => $request->aluno_nome,
            'aluno_parentesco' => $request->aluno_parentesco,
            'aluno_nascimento' => $request->aluno_nascimento,
            'aluno_bolsista' => $request->aluno_bolsista,
            'aluno_desc' => $request->aluno_desc,
            'aluno_foto' => $filename,

            'id_emp_id' => $users->id_emp_id,
        ]);

        return redirect()
            ->route('alunos', Crypt::encrypt($responsavel->id_responsavel))
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
            ->where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_alunos.edit', [
            'aluno' => $aluno,
            'responsavel' => $aluno->responsavel
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_nascimento' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(6)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],
            'aluno_parentesco' => 'required|string|max:60',
            //'aluno_bolsista' => 'required|in:sim,nao',
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
        $aluno->aluno_parentesco = $request->aluno_parentesco;
        $aluno->aluno_nascimento = $request->aluno_nascimento;
        //$aluno->aluno_bolsista = $request->aluno_bolsista;
        $aluno->aluno_desc = $request->aluno_desc;

        $aluno->save();

        return redirect()
            ->route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel))
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $aluno = Aluno::where('id_aluno', $id)->firstOrFail();

        return view('view_alunos.show', compact('aluno'));
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $responsavelId = $aluno->responsavel_id_responsavel;

        if ($aluno->aluno_foto && file_exists(public_path('images/alunos/' . $aluno->aluno_foto))) {
            unlink(public_path('images/alunos/' . $aluno->aluno_foto));
        }

        $aluno->delete();

        return redirect()
            ->route('alunos', Crypt::encrypt($responsavelId))->with('success', 'Aluno removido com sucesso!');
    }
}
