<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class TurmaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $turmas = Turma::where('id_emp_id', $user->id_emp_id)->get();

        return view('view_admin.turma', compact('turmas'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'turma_nome' => 'required|string|max:100',
        ]);

        $jaExiste = Turma::where('turma_nome', $request->turma_nome)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'turma_nome' => 'Erro, essa turma já existe.'
            ])->withInput();
        }

        Turma::create([
            'turma_nome' => $request->turma_nome,
            'id_emp_id' => $user->id_emp_id
        ]);

        return redirect()->route('turmas')
            ->with('success', 'Turma cadastrada com sucesso!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $turma = Turma::where('id_turma', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin.turma_edit', compact('turma'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $turma = Turma::where('id_turma', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'turma_nome' => 'required|string|max:100',
        ]);

        $jaExiste = Turma::where('turma_nome', $request->turma_nome)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_turma', '!=', $turma->id_turma)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'turma_nome' => 'Erro, essa turma já existe.'
            ])->withInput();
        }

        $turma->update([
            'turma_nome' => $request->turma_nome
        ]);

        return redirect()->route('turmas')
            ->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $turma = Turma::where('id_turma', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $turma->delete();

        return redirect()->route('turmas')
            ->with('success', 'Turma removida com sucesso!');
    }
}