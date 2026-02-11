<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    // LISTAR RESPONSÁVEIS
    public function index()
    {
        $responsaveis = Responsavel::all();
        return view('view_responsavel.index', compact('responsaveis'));
    }

    // CADASTRAR RESPONSÁVEL
    public function store(Request $request)
    {
        $request->merge([
            'resp_cpf' => preg_replace('/\D/', '', $request->resp_cpf),
            'resp_cep' => preg_replace('/\D/', '', $request->resp_cep),
            'resp_telefone' => preg_replace('/\D/', '', $request->resp_telefone),
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

        Responsavel::create($request->all());

        return redirect()
            ->route('responsaveis')
            ->with('success', 'Responsável cadastrado com sucesso!');
    }

    // EDITAR RESPONSÁVEL
    public function edit($id)
    {
        $responsavel = Responsavel::findOrFail($id);
        return view('view_responsavel.edit', compact('responsavel'));
    }

    // ATUALIZAR RESPONSÁVEL
    public function update(Request $request, $id)
    {
        $responsavel = Responsavel::findOrFail($id);

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

        $responsavel->update($request->all());

        return redirect()
            ->route('responsaveis')
            ->with('success', 'Responsável atualizado com sucesso!');
    }

    // EXCLUIR RESPONSÁVEL
    public function destroy($id)
    {
        $responsavel = Responsavel::findOrFail($id);
        $responsavel->alunos()->delete();
        $responsavel->delete();

        return redirect()
            ->route('responsaveis')
            ->with('success', 'Responsável e alunos removidos!');
    }
}
