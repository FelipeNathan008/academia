<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ModalidadeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Modalidade::where('id_emp_id', $user->id_emp_id);

        if ($request->filled('busca')) {
            $busca = trim($request->input('busca'));

            $query->where(function ($q) use ($busca) {
                $q->where('mod_nome', 'like', '%' . $busca . '%')
                    ->orWhere('mod_desc', 'like', '%' . $busca . '%');
            });
        }

        $modalidades = $query
            ->orderBy('mod_nome')
            ->paginate(10)
            ->withQueryString();

        return view('view_admin_user.view_admin.view_modalidades.index', compact('modalidades'));
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
                'mod_nome' => 'Erro, já existe essa modalidade cadastrada.'
            ])->withInput();
        }

        Modalidade::create([
            'mod_nome' => $request->mod_nome,
            'mod_desc' => $request->mod_desc,
            'id_emp_id' => $user->id_emp_id,
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

        return view('view_admin_user.view_admin.view_modalidades.edit', compact('modalidade'));
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
            ->where('id_modalidade', '!=', $modalidade->id_modalidade)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'mod_nome' => 'Erro, já existe essa modalidade cadastrada.'
            ])->withInput();
        }

        $modalidade->update([
            'mod_nome' => $request->mod_nome,
            'mod_desc' => $request->mod_desc,
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

        try {
            $modalidade->delete();
        } catch (QueryException $e) {
            return redirect()->route('modalidades')
                ->withErrors([
                    'exclusao' => 'Não foi possível excluir esta modalidade porque existem registros vinculados a ela. Remova ou atualize os vínculos antes de tentar novamente.'
                ]);
        }

        return redirect()->route('modalidades')
            ->with('success', 'Modalidade removida com sucesso!');
    }
}
