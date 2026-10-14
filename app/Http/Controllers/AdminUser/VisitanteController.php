<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Visitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class VisitanteController extends Controller
{
    // LISTAR VISITANTES
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Visitante::where('id_emp_id', $user->id_emp_id);

        if ($request->filled('nome')) {
            $query->where('vis_nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('letra')) {
            $letra = strtoupper($request->letra);

            if (preg_match('/^[A-Z]$/', $letra)) {
                $query->where('vis_nome', 'like', $letra . '%');
            }
        }

        if ($request->filled('status') && in_array($request->status, ['Ativo', 'Inativo'])) {
            $query->where('vis_status', $request->status);
        }

        $totalVisitantes = Visitante::where('id_emp_id', $user->id_emp_id)->count();

        $visitantes = $query
            ->orderBy('vis_nome', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'view_admin_user.view_principal.view_visitante.index',
            compact(
                'visitantes',
                'totalVisitantes'
            )
        );
    }

    // CADASTRAR VISITANTE
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->merge([
            'vis_telefone' => $request->filled('vis_telefone')
                ? preg_replace('/\D/', '', $request->vis_telefone)
                : null,
        ]);
        
        $request->validate([
            'vis_nome' => 'required|string|max:120',
            'vis_email' => 'nullable|email|max:150',
            'vis_telefone' => 'nullable|string|max:20',
            'vis_data_nasc' => 'nullable|date',
            'vis_status' => 'required|in:Ativo,Inativo',
        ]);

        Visitante::create([
            'id_emp_id' => $user->id_emp_id,
            'vis_nome' => $request->vis_nome,
            'vis_email' => $request->vis_email,
            'vis_telefone' => $request->vis_telefone,
            'vis_data_nasc' => $request->vis_data_nasc,
            'vis_status' => $request->vis_status,
        ]);

        return redirect()
            ->route('visitantes')
            ->with('success', 'Visitante cadastrado com sucesso!');
    }

    // EDITAR VISITANTE
    public function edit($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $visitante = Visitante::where('id_visitante', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view(
            'view_admin_user.view_principal.view_visitante.edit',
            compact('visitante')
        );
    }

    // ATUALIZAR VISITANTE
    public function update(Request $request, $idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $visitante = Visitante::where('id_visitante', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $dados = $request->validate([
            'vis_nome' => 'required|string|max:120',
            'vis_email' => 'nullable|email|max:150',
            'vis_telefone' => 'nullable|string|max:20',
            'vis_data_nasc' => 'nullable|date',
            'vis_status' => 'required|in:Ativo,Inativo',
        ]);

        $dados['vis_nome'] = trim($dados['vis_nome']);

        $dados['vis_telefone'] = !empty($dados['vis_telefone'])
            ? preg_replace('/\D/', '', $dados['vis_telefone'])
            : null;

        $visitante->update($dados);

        return redirect()
            ->route('visitantes')
            ->with('success', 'Visitante atualizado com sucesso!');
    }


    // EXCLUIR VISITANTE
    public function destroy($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $visitante = Visitante::where('id_visitante', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $visitante->delete();

        return redirect()
            ->route('visitantes')
            ->with('success', 'Visitante excluído com sucesso!');
    }
}
