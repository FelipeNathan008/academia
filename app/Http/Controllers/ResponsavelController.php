<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use App\Models\Aluno;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    public function index($id)
    {
        $id_aluno = $id;
        $aluno = Aluno::findOrFail($id_aluno);
        $responsaveis = Responsavel::where('aluno_id_aluno', $id_aluno)->get();

        return view('view_responsavel.index', compact('id_aluno', 'aluno', 'responsaveis'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'resp_cpf' => preg_replace('/\D/', '', $request->resp_cpf),
            'resp_cep' => preg_replace('/\D/', '', $request->resp_cep),
            'resp_telefone' => preg_replace('/\D/', '', $request->resp_telefone),
        ]);

        $request->validate([
            'aluno_id_aluno' => 'required|exists:aluno,id_aluno',
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

        $responsavel = Responsavel::create($request->all());

        return redirect()
            ->route('responsaveis.index', $request->aluno_id_aluno)
            ->with('success', 'Responsável cadastrado com sucesso!');
    }

    public function show($id)
    {
        $responsavel = Responsavel::with('aluno')->findOrFail($id);

        return response()->json(['data' => $responsavel]);
    }

    public function edit($id)
    {
        $responsavel = Responsavel::findOrFail($id);
        $aluno = Aluno::findOrFail($responsavel->aluno_id_aluno);

        return view('view_responsavel.edit', compact('responsavel', 'aluno'));
    }

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

        $responsavel->update($request->only([
            'resp_nome',
            'resp_parentesco',
            'resp_cpf',
            'resp_cep',
            'resp_logradouro',
            'resp_numero',
            'resp_complemento',
            'resp_bairro',
            'resp_cidade',
        ]));

        return redirect()
            ->route('responsaveis.index', $responsavel->aluno_id_aluno)
            ->with('success', 'Responsável atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $responsavel = Responsavel::findOrFail($id);
        $alunoId = $responsavel->aluno_id_aluno;
        $responsavel->delete();

        return redirect()
            ->route('responsaveis.index', $alunoId)
            ->with('success', 'Responsável removido com sucesso.');
    }
}
