<?php

namespace App\Http\Controllers;

use App\Models\Graduacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class GraduacaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $graduacoes = Graduacao::ordenarPorFaixa()
            ->orderBy('gradu_grau')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        return view('view_admin.graduacoes', compact('graduacoes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'gradu_nome_cor' => 'required|string|max:80|regex:/^[\pL\s]+$/u',
            'gradu_grau'     => 'required|integer',
            'gradu_meta'     => 'required|string|max:50',
        ]);
        $jaExiste = Graduacao::where('gradu_nome_cor', $request->gradu_nome_cor)
            ->where('gradu_grau', $request->gradu_grau)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'gradu_nome_cor' => 'Erro, Já existe essa graduação cadastrada.'
            ])->withInput();
        }

        Graduacao::create([
            'gradu_nome_cor' => $request->gradu_nome_cor,
            'gradu_grau'     => $request->gradu_grau,
            'gradu_meta'     => $request->gradu_meta,
            'id_emp_id'      => $user->id_emp_id
        ]);
        return redirect()->route('graduacoes')->with('success', 'Graduação cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $graduacao = Graduacao::where('id_graduacao', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin.graduacoes_edit', compact('graduacao'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $graduacao = Graduacao::where('id_graduacao', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();


        $request->validate([
            'gradu_nome_cor' => 'required|string|max:80|regex:/^[\pL\s]+$/u',
            'gradu_grau'     => 'required|integer',
            'gradu_meta'     => 'required|string|max:50',
        ]);

        $jaExiste = Graduacao::where('gradu_nome_cor', $request->gradu_nome_cor)
            ->where('gradu_grau', $request->gradu_grau)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_graduacao', '!=', $graduacao->id_graduacao) // ignora o próprio registro
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'gradu_nome_cor' => 'Erro, Já existe essa graduação cadastrada.'
            ])->withInput();
        }

        $graduacao->update([
            'gradu_nome_cor' => $request->gradu_nome_cor,
            'gradu_grau'     => $request->gradu_grau,
            'gradu_meta'     => $request->gradu_meta,
        ]);

        return redirect()->route('graduacoes')
            ->with('success', 'Graduação atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $graduacao = Graduacao::where('id_graduacao', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $graduacao->delete();

        return redirect()->route('graduacoes')->with('success', 'Graduação removida com sucesso!');
    }
}
