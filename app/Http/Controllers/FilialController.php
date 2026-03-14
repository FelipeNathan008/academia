<?php

namespace App\Http\Controllers;

use App\Models\Filial;
use App\Models\Empresa;
use Illuminate\Http\Request;

class FilialController extends Controller
{

    public function index()
    {
        $filiais = Filial::all();
        $empresas = Empresa::all(); // <-- necessário para o select
        return view('view_controle.filiais', compact('filiais', 'empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_emp_id' => 'required|exists:empresas,id_empresa',
            'filial_nome' => 'required|string|max:100',
            'filial_apelido' => 'required|string|max:50',
            'filial_nome_responsavel' => 'required|string|max:100',
            'filial_email_responsavel' => 'required|email|max:100',
            'filial_telefone_responsavel' => 'required|string|max:20',
            'filial_cpf' => 'nullable|string|max:20',
            'filial_foto' => 'nullable|string|max:255',
        ]);

        Filial::create($request->all());

        return redirect()->route('filiais')
            ->with('success', 'Filial cadastrada com sucesso!');
    }


    public function edit($id)
    {
        $filial = Filial::findOrFail($id);
        $empresa = $filial->empresa;

        return view('view_filiais.edit', compact('filial', 'empresa'));
    }

    public function update(Request $request, $id)
    {
        $filial = Filial::findOrFail($id);

        $request->validate([
            'filial_nome' => 'required|max:120',
            'filial_apelido' => 'required|max:120',
            'filial_nome_responsavel' => 'required|max:120',
            'filial_email_responsavel' => 'required|email',
            'filial_telefone_responsavel' => 'required|max:20',
            'filial_cpf' => 'required|max:14'
        ]);

        $filial->filial_nome = $request->filial_nome;
        $filial->filial_apelido = $request->filial_apelido;
        $filial->filial_nome_responsavel = $request->filial_nome_responsavel;
        $filial->filial_email_responsavel = $request->filial_email_responsavel;
        $filial->filial_telefone_responsavel = $request->filial_telefone_responsavel;
        $filial->filial_cpf = $request->filial_cpf;

        $filial->save();

        return redirect()->route('filiais', $filial->id_emp_id)
            ->with('success', 'Filial atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $filial = Filial::findOrFail($id);
        $empresaId = $filial->id_emp_id;

        $filial->delete();

        return redirect()->route('filiais', $empresaId)
            ->with('success', 'Filial removida com sucesso!');
    }
}
