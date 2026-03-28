<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Graduacao;
use App\Models\DetalhesProfessor;
use App\Models\Modalidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class ProfessorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $professores = Professor::where('id_emp_id', $user->id_emp_id)->get();

        $graduacoes = Graduacao::select('gradu_nome_cor')
            ->selectRaw('MAX(gradu_grau) as max_grau')
            ->groupBy('gradu_nome_cor')
            ->where('id_emp_id', $user->id_emp_id)
            ->orderByRaw("
            CASE gradu_nome_cor
                WHEN 'Faixa Branca' THEN 1
                WHEN 'Faixa Azul' THEN 2
                WHEN 'Faixa Roxa' THEN 3
                WHEN 'Faixa Marrom' THEN 4
                WHEN 'Faixa Preta' THEN 5
                ELSE 99
            END
        ")
            ->get();

        $detalhes = DetalhesProfessor::all();
        $modalidades = Modalidade::all();

        $alunos
        return view(
            'view_professores.index',
            compact('professores', 'graduacoes', 'detalhes', 'modalidades')
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->merge([
            'prof_telefone' => preg_replace('/\D/', '', $request->prof_telefone),
            'id_emp_id' => $user->id_emp_id
        ]);

        $request->validate([
            'prof_nome' => 'required|string|max:120',
            'prof_nascimento' => 'required|date',
            'prof_telefone' => 'required|string|max:20',
            'prof_desc' => 'required|string',
            'prof_foto' => 'nullable|image|max:2048',
        ]);

        // SÓ DEPOIS faz upload
        $filename = null;

        if ($request->hasFile('prof_foto')) {
            $file = $request->file('prof_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores'), $filename);
        }

        Professor::create([
            'prof_nome' => $request->prof_nome,
            'prof_nascimento' => $request->prof_nascimento,
            'prof_telefone' => $request->prof_telefone,
            'prof_desc' => $request->prof_desc,
            'prof_foto' => $filename,
            'id_emp_id' => $user->id_emp_id
        ]);

        return redirect()
            ->route('professores')
            ->with('success', 'Professor cadastrado com sucesso!');
    }
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();
        return view('view_professores.edit', compact('professor'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->merge([
            'prof_telefone' => preg_replace('/\D/', '', $request->prof_telefone),
        ]);

        $request->validate([
            'prof_nome' => 'required|string|max:120',
            'prof_nascimento' => 'required|date',
            'prof_telefone' => 'required|string|max:20',
            'prof_desc' => 'required|string',
            'prof_foto' => 'nullable|image|max:2048',
        ]);

      
        // FOTO
        if ($request->hasFile('prof_foto')) {

            if ($professor->prof_foto && file_exists(public_path('images/professores/' . $professor->prof_foto))) {
                unlink(public_path('images/professores/' . $professor->prof_foto));
            }

            $file = $request->file('prof_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores'), $filename);

            $professor->prof_foto = $filename;
        }

        $professor->update([
            'prof_nome' => $request->prof_nome,
            'prof_nascimento' => $request->prof_nascimento,
            'prof_telefone' => $request->prof_telefone,
            'prof_desc' => $request->prof_desc,
        ]);

        return redirect()->route('professores')
            ->with('success', 'Professor atualizado com sucesso!');
    }


    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();
        $professor->detalhes()->delete();

        if ($professor->prof_foto && file_exists(public_path('images/professores/' . $professor->prof_foto))) {
            unlink(public_path('images/professores/' . $professor->prof_foto));
        }

        $professor->delete();

        return redirect()->route('professores')
            ->with('success', 'Professor e graduações removidos com sucesso!');
    }
}
