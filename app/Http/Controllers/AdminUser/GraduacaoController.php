<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Graduacao;
use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class GraduacaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $graduacoes = Graduacao::orderBy('gradu_ordem')
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $modalidades = Modalidade::orderBy('mod_nome')->get();

        return view('view_admin_user.view_admin.view_graduacoes.index', compact('graduacoes', 'modalidades'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'gradu_nome_cor' => 'required|string|max:80',
            'gradu_grau' => 'required|integer',
            'gradu_meta' => 'required|string|max:50',
            'gradu_ordem' => 'required|string|max:60',
            'id_modalidade' => 'required|exists:modalidade,id_modalidade',
        ]);

        $ordemExiste = Graduacao::where('gradu_ordem', $request->gradu_ordem)
            ->where('id_modalidade', $request->id_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($ordemExiste) {
            return back()->withErrors([
                'gradu_ordem' => 'Já existe uma graduação com essa ordem nesta modalidade.'
            ])->withInput();
        }
        $jaExiste = Graduacao::where('gradu_nome_cor', $request->gradu_nome_cor)
            ->where('gradu_grau', $request->gradu_grau)
            ->where('id_modalidade', $request->id_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'gradu_nome_cor' => 'Erro, Já existe essa graduação cadastrada.'
            ])->withInput();
        }

        Graduacao::create([
            'gradu_nome_cor' => $request->gradu_nome_cor,
            'gradu_grau' => $request->gradu_grau,
            'gradu_meta' => $request->gradu_meta,
            'gradu_ordem' => $request->gradu_ordem,
            'id_modalidade' => $request->id_modalidade,
            'id_emp_id' => $user->id_emp_id
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
        $modalidades = Modalidade::orderBy('mod_nome')->get();

        return view(
            'view_admin_user.view_admin.view_graduacoes.edit',
            compact('graduacao', 'modalidades')
        );
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
            'gradu_nome_cor' => 'required|string|max:80',
            'gradu_grau' => 'required|integer',
            'gradu_meta' => 'required|string|max:50',
            'gradu_ordem' => 'required|string|max:60',
            'id_modalidade' => 'required|exists:modalidade,id_modalidade',
        ]);

        $ordemExiste = Graduacao::where('gradu_ordem', $request->gradu_ordem)
            ->where('id_modalidade', $request->id_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_graduacao', '!=', $graduacao->id_graduacao)
            ->exists();

        if ($ordemExiste) {
            return back()->withErrors([
                'gradu_ordem' => 'Já existe uma graduação com essa ordem nesta modalidade.'
            ])->withInput();
        }

        $jaExiste = Graduacao::where('gradu_nome_cor', $request->gradu_nome_cor)
            ->where('gradu_grau', $request->gradu_grau)
            ->where('id_modalidade', $request->id_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_graduacao', '!=', $graduacao->id_graduacao)
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
            'gradu_ordem'    => $request->gradu_ordem,
            'id_modalidade'  => $request->id_modalidade,
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

        // Bloqueia exclusão se houver alunos com esta graduação
        if ($graduacao->detalhesAluno()->exists()) {
            return redirect()->route('graduacoes')
                ->withErrors(['erro' => 'Não é possível excluir esta graduação pois existem alunos/professores vinculados a ela.']);
        }

        $graduacao->delete();

        return redirect()->route('graduacoes')->with('success', 'Graduação removida com sucesso!');
    }
}
