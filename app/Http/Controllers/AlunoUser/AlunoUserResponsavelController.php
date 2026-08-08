<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Rules\CpfValido;
use App\Models\Responsavel;

class AlunoUserResponsavelController extends Controller
{
    public function index()
    {
        $responsavel = Auth::user()->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $responsavel->qtd_alunos = $responsavel->alunos()->count();

        return view('view_aluno_user.responsavel.index', compact('responsavel'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $request->merge([
            'resp_cep' => $request->filled('resp_cep')
                ? preg_replace('/\D/', '', $request->resp_cep)
                : null,

            'resp_telefone' => $request->filled('resp_telefone')
                ? preg_replace('/\D/', '', $request->resp_telefone)
                : null,
        ]);

        $cpfAlterado = $request->filled('resp_cpf');

        if ($cpfAlterado) {
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

        if ($cpfAlterado) {

            $jaExiste = Responsavel::where('id_emp_id', $user->id_emp_id)
                ->where('id_responsavel', '!=', $responsavel->id_responsavel)
                ->get()
                ->contains(fn($r) => $r->resp_cpf === $request->resp_cpf);

            if ($jaExiste) {
                return back()
                    ->withErrors([
                        'resp_cpf' => 'Já existe esse CPF cadastrado.'
                    ])
                    ->withInput();
            }
        }

        $dados = $request->except($cpfAlterado ? [] : ['resp_cpf']);

        $responsavel->update($dados);

        return back()->with('success', 'Responsável atualizado com sucesso!');
    }
}
