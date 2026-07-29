<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Graduacao;
use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use App\Models\Modelos\ModalidadeMolde;
use App\Models\Modelos\GraduacaoMolde;

class GraduacaoController extends Controller
{

    public function moldeIndex()
    {
        $modalidadesUsuario = Modalidade::orderBy('mod_nome')->get();

        if ($modalidadesUsuario->isEmpty()) {
            return redirect()->route('graduacoes')
                ->withErrors(['erro' => 'Você precisa cadastrar uma modalidade antes de aplicar um molde de graduação.']);
        }

        $modalidadesMolde = ModalidadeMolde::orderBy('mod_nome')->get();

        return view('view_admin_user.view_admin.view_graduacoes.molde_index', compact('modalidadesMolde'));
    }

    public function moldeShow($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $modalidadeMolde = ModalidadeMolde::with(['graduacoes' => function ($q) {
            $q->orderBy('gradu_ordem');
        }])->findOrFail($id);

        $modalidades = Modalidade::orderBy('mod_nome')->get();

        return view(
            'view_admin_user.view_admin.view_graduacoes.molde_show',
            compact('modalidadeMolde', 'modalidades')
        );
    }

    public function moldeAplicar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_modalidade' => 'required|exists:modalidade,id_modalidade',
            'graduacoes'    => 'required|array|min:1',
        ]);

        // Mantém apenas as graduações marcadas
        $graduacoes = collect($request->graduacoes)
            ->filter(function ($item) {
                return isset($item['ativo']) && $item['ativo'] == 1;
            })
            ->values();

        if ($graduacoes->isEmpty()) {
            return back()
                ->withErrors(['erro' => 'Selecione ao menos uma graduação para aplicar.'])
                ->withInput();
        }

        $modalidade = Modalidade::where('id_modalidade', $request->id_modalidade)
            ->firstOrFail();

        $criadas = 0;
        $ignoradas = 0;

        foreach ($graduacoes as $item) {

            // Validação dos campos
            if (
                empty($item['gradu_nome_cor']) ||
                $item['gradu_grau'] === null ||
                $item['gradu_ordem'] === null ||
                $item['gradu_meta'] === null
            ) {
                continue;
            }

            // Verifica duplicidade
            $duplicada = Graduacao::where('id_modalidade', $modalidade->id_modalidade)
                ->where('id_emp_id', $user->id_emp_id)
                ->where(function ($q) use ($item) {
                    $q->where('gradu_ordem', $item['gradu_ordem'])
                        ->orWhere(function ($q2) use ($item) {
                            $q2->where('gradu_nome_cor', $item['gradu_nome_cor'])
                                ->where('gradu_grau', $item['gradu_grau']);
                        });
                })
                ->exists();

            if ($duplicada) {
                $ignoradas++;
                continue;
            }

            Graduacao::create([
                'gradu_nome_cor' => trim($item['gradu_nome_cor']),
                'gradu_grau'     => $item['gradu_grau'],
                'gradu_meta'     => $item['gradu_meta'],
                'gradu_ordem'    => $item['gradu_ordem'],
                'id_modalidade'  => $modalidade->id_modalidade,
                'id_emp_id'      => $user->id_emp_id,
            ]);

            $criadas++;
        }

        $msg = "{$criadas} graduação(ões) aplicada(s) com sucesso.";

        if ($ignoradas > 0) {
            $msg .= " {$ignoradas} graduação(ões) já existiam e foram ignoradas.";
        }

        return redirect()
            ->route('graduacoes')
            ->with('success', $msg);
    }

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
            'gradu_grau' => 0,
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
            'gradu_grau'     => 0,
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
