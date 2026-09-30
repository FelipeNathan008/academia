<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Rules\CpfValido;

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

        // FILTRO PELA PRIMEIRA LETRA DO NOME
        if ($request->filled('letra')) {
            $letra = strtoupper($request->letra);

            if (preg_match('/^[A-Z]$/', $letra)) {
                $query->where('resp_nome', 'like', $letra . '%');
            }
        }

        $totalResponsaveis = Responsavel::where('id_emp_id', $user->id_emp_id)->count();

        $responsaveis = $query
            ->withCount('alunos')
            ->orderBy('resp_nome', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'view_admin_user.view_principal.view_responsavel.index',
            compact(
                'responsaveis',
                'totalResponsaveis'
            )
        );
    }

    // CADASTRAR RESPONSÁVEL
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->merge([
            'resp_cpf' => $request->filled('resp_cpf')
                ? preg_replace('/\D/', '', $request->resp_cpf)
                : null,
            'resp_cep' => $request->filled('resp_cep')
                ? preg_replace('/\D/', '', $request->resp_cep)
                : null,
            'resp_telefone' => $request->filled('resp_telefone')
                ? preg_replace('/\D/', '', $request->resp_telefone)
                : null,
        ]);

        $request->validate([
            'resp_nome'         => 'required|string|max:120',
            'resp_parentesco'   => 'nullable|string|max:60',
            'resp_cpf'          => ['nullable', 'string', 'size:11', new CpfValido],
            'resp_telefone'     => 'nullable|string|max:20',
            'resp_email'        => 'nullable|email|max:150',
            'resp_cep'          => 'nullable|digits:8',
            'resp_logradouro'   => 'nullable|string|max:150',
            'resp_numero'       => 'nullable|string|max:10',
            'resp_complemento'  => 'nullable|string|max:100',
            'resp_bairro'       => 'nullable|string|max:150',
            'resp_cidade'       => 'nullable|string|max:150',
        ]);

        // VERIFICA DUPLICIDADE DE CPF (descriptografando em PHP, pois o valor no banco é cifrado)
        if ($request->filled('resp_cpf')) {

            $cpfJaExiste = Responsavel::where('id_emp_id', $user->id_emp_id)
                ->get()
                ->contains(fn($r) => $r->resp_cpf === $request->resp_cpf);

            if ($cpfJaExiste) {
                return back()->withErrors([
                    'resp_cpf' => 'Erro, já existe esse CPF cadastrado.'
                ])->withInput();
            }
        }
        $responsavel = Responsavel::create([
            ...$request->except('tipo_cadastro'),
            'id_emp_id' => $user->id_emp_id
        ]);

        if ($request->tipo_cadastro == 'adulto') {

            return redirect()->route(
                'alunos',
                [
                    Crypt::encrypt($responsavel->id_responsavel),
                    'nome' => $responsavel->resp_nome
                ]
            );
        }

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

        return view('view_admin_user.view_principal.view_responsavel.edit', compact('responsavel'));
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
            'resp_cep' => $request->filled('resp_cep')
                ? preg_replace('/\D/', '', $request->resp_cep)
                : null,

            'resp_telefone' => $request->filled('resp_telefone')
                ? preg_replace('/\D/', '', $request->resp_telefone)
                : null,
        ]);

        if ($request->filled('resp_cpf')) {
            $request->merge([
                'resp_cpf' => preg_replace('/\D/', '', $request->resp_cpf),
            ]);
        }

        $request->validate([
            'resp_nome'         => 'required|string|max:120',
            'resp_parentesco'   => 'nullable|string|max:60',
            'resp_cpf'          => ['nullable', 'string', 'size:11', new CpfValido],
            'resp_telefone'     => 'nullable|string|max:20',
            'resp_email'        => 'nullable|email|max:150',
            'resp_cep'          => 'nullable|digits:8',
            'resp_logradouro'   => 'nullable|string|max:150',
            'resp_numero'       => 'nullable|string|max:10',
            'resp_complemento'  => 'nullable|string|max:100',
            'resp_bairro'       => 'nullable|string|max:150',
            'resp_cidade'       => 'nullable|string|max:150',
        ]);

        if ($request->filled('resp_cpf')) {

            $cpfJaExiste = Responsavel::where('id_emp_id', $user->id_emp_id)
                ->where('id_responsavel', '!=', $id)
                ->get()
                ->contains(fn($r) => $r->resp_cpf === $request->resp_cpf);

            if ($cpfJaExiste) {
                return back()
                    ->withErrors([
                        'resp_cpf' => 'Erro, já existe esse CPF cadastrado.'
                    ])
                    ->withInput();
            }
        }

        if (!$request->filled('resp_cpf')) {
            $request->request->remove('resp_cpf');
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
