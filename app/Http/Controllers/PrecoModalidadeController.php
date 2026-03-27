<?php

namespace App\Http\Controllers;

use App\Models\Modalidade;
use App\Models\PrecoModalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;


class PrecoModalidadeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $valores = PrecoModalidade::where('id_emp_id', $user->id_emp_id)->get();
        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();

        return view('view_admin.preco_modalidades', compact('valores', 'modalidades'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'modalidade_id'    => 'required|exists:modalidade,id_modalidade',
            'preco_modalidade' => 'required|numeric|min:0',
            'preco_plano'      => 'required|string|max:40',
        ]);
        $jaExiste = PrecoModalidade::where('modalidade_id', $request->modalidade_id)
            ->where('preco_plano', $request->preco_plano)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'mod_nome' => 'Erro, Já existe essa modalidade cadastrada.'
            ])->withInput();
        }
        PrecoModalidade::create([
            'modalidade_id'    => $request->modalidade_id,
            'preco_modalidade' => $request->preco_modalidade,
            'preco_plano'      => $request->preco_plano,
            'id_emp_id' => $user->id_emp_id,
        ]);

        return redirect()->route('preco-aula')
            ->with('success', 'Valor cadastrado com sucesso!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $user = Auth::user();

        $valor = PrecoModalidade::where('id_preco_modalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)
            ->get();

        return view('view_admin.preco_modalidades_edit', compact('valor', 'modalidades'));
    }

    public function update(Request $request, $id)
    {

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $valor = PrecoModalidade::where('id_preco_modalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'modalidade_id'    => 'required|exists:modalidade,id_modalidade',
            'preco_modalidade' => 'required|numeric|min:0',
            'preco_plano'      => 'required|string|max:40',
        ]);

        $jaExiste = PrecoModalidade::where('modalidade_id', $request->modalidade_id)
            ->where('preco_plano', $request->preco_plano)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_preco_modalidade', '!=', $valor->id) // ignora o próprio registro
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'modalidade_id' => 'Erro, Já existe valor cadastrado para esta modalidade neste plano.'
            ])->withInput();
        }

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
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $valor = PrecoModalidade::where('id_preco_modalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();
        $valor->delete();

        return redirect()->route('preco-aula')
            ->with('success', 'Valor removido com sucesso!');
    }
}
