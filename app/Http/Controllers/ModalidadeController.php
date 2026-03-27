<?php

namespace App\Http\Controllers;

use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class ModalidadeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();

        return view('view_admin.modalidades', compact('modalidades'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'mod_nome' => 'required|string|max:100',
            'mod_desc' => 'required|string|max:255',
        ]);

        $jaExiste = Modalidade::where('mod_nome', $request->mod_nome)
            ->where('mod_desc', $request->mod_desc)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'mod_nome' => 'Erro, Já existe essa modalidade cadastrada.'
            ])->withInput();
        }

        Modalidade::create([
            'mod_nome'  => $request->mod_nome,
            'mod_desc'  => $request->mod_desc,
            'id_emp_id' => $user->id_emp_id
        ]);
        return redirect()->route('modalidades')
            ->with('success', 'Modalidade cadastrada com sucesso!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $user = Auth::user();
        $modalidade = Modalidade::where('id_modalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin.modalidades_edit', compact('modalidade'));
    }


    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $user = Auth::user();

        $modalidade = Modalidade::where('id_modalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'mod_nome' => 'required|string|max:100',
            'mod_desc' => 'required|string|max:255',
        ]);

        $jaExiste = Modalidade::where('mod_nome', $request->mod_nome)
            ->where('mod_desc', $request->mod_desc)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_modalidade', '!=', $modalidade->id_modalidade) // ignora o próprio registro
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'mod_nome' => 'Erro, Já existe essa modalidade cadastrada.'
            ])->withInput();
        }

        $modalidade->update([
            'mod_nome'  => $request->mod_nome,
            'mod_desc'  => $request->mod_desc,
        ]);

        return redirect()->route('modalidades')
            ->with('success', 'Modalidade atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $modalidade = Modalidade::where('id_modalidade', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $modalidade->delete();

        return redirect()->route('modalidades')
            ->with('success', 'Modalidade removida com sucesso!');
    }
}
