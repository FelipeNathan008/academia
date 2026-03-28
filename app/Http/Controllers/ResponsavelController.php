<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ResponsavelController extends Controller
{
    // LISTAR RESPONSÁVEIS
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Responsavel::where('id_emp_id', $user->id_emp_id);

        // FILTRO POR NOME
        if ($request->filled('nome')) {
            $query->where('resp_nome', 'like', '%' . $request->nome . '%');
        }

        $responsaveis = $query->paginate(10)->withQueryString();

        return view('view_responsavel.index', compact('responsaveis'));
    }

    // CADASTRAR RESPONSÁVEL
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->merge([
            'resp_cpf' => preg_replace('/\D/', '', $request->resp_cpf),
            'resp_cep' => preg_replace('/\D/', '', $request->resp_cep),
            'resp_telefone' => preg_replace('/\D/', '', $request->resp_telefone),

            'id_emp_id' => $user->id_emp_id
        ]);

        $request->validate([
            'resp_nome' => 'required|string|max:120',
            'resp_parentesco' => 'required|string|max:60',
            'resp_cpf' => 'required|string|size:11',
            'resp_telefone' => 'required|string|max:20',
            'resp_email' => 'required|email|max:150',
            'resp_cep' => 'required|digits:8',
            'resp_logradouro' => 'required|string|max:150',
            'resp_numero' => 'nullable|string|max:10',
            'resp_complemento' => 'nullable|string|max:100',
            'resp_bairro' => 'required|string|max:150',
            'resp_cidade' => 'required|string|max:150',
        ]);

        $jaExiste = Responsavel::where('resp_cpf', $request->resp_cpf)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'resp_cpf' => 'Erro, Já existe esse CPF cadastrado.'
            ])->withInput();
        }

        Responsavel::create([
            ...$request->all(),
            'id_emp_id' => $user->id_emp_id
        ]);

        return redirect()
            ->route('responsaveis')
            ->with('success', 'Responsável cadastrado com sucesso!');
    }

    // EDITAR RESPONSÁVEL
    public function edit($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $responsavel = Responsavel::where('id_responsavel', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_responsavel.edit', compact('responsavel'));
    }

    // ATUALIZAR RESPONSÁVEL
    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $responsavel = Responsavel::where('id_responsavel', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->merge([
            'resp_cpf' => preg_replace('/\D/', '', $request->resp_cpf),
            'resp_cep' => preg_replace('/\D/', '', $request->resp_cep),
        ]);

        $request->validate([
            'resp_nome' => 'required|string|max:120',
            'resp_parentesco' => 'required|string|max:60',
            'resp_cpf' => 'required|string|size:11',
            'resp_telefone' => 'required|string|max:20',
            'resp_email' => 'required|email|max:150',
            'resp_cep' => 'required|digits:8',
            'resp_logradouro' => 'required|string|max:150',
            'resp_numero' => 'nullable|string|max:10',
            'resp_complemento' => 'nullable|string|max:100',
            'resp_bairro' => 'required|string|max:150',
            'resp_cidade' => 'required|string|max:150',
        ]);

        $jaExiste = Responsavel::where('resp_cpf', $request->resp_cpf)
            ->where('id_responsavel', '!=', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'resp_cpf' => 'Erro, Já existe esse CPF cadastrado.'
            ])->withInput();
        }

        $responsavel->update($request->all());

        return redirect()
            ->route('responsaveis')
            ->with('success', 'Responsável atualizado com sucesso!');
    }

    // EXCLUIR RESPONSÁVEL
    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $user = Auth::user();

        $responsavel = Responsavel::where('id_responsavel', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $responsavel->alunos()->delete();
        $responsavel->delete();

        return redirect()
            ->route('responsaveis')
            ->with('success', 'Responsável e alunos removidos!');
    }
}
