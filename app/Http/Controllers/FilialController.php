<?php

namespace App\Http\Controllers;

use App\Models\Filial;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class FilialController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $filiais = Filial::where('id_emp_id', $user->id_emp_id)->get();
        $empresa = Empresa::where('id_empresa', $user->id_emp_id)
            ->firstOrFail();
        return view('view_controle.filiais', compact('filiais', 'empresa'));
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
            'filial_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $dados = $request->except('filial_foto');


        $dados['id_emp_id'] = Auth::user()->id_emp_id;

        // upload da imagem
        if ($request->hasFile('filial_foto')) {
            $arquivo = $request->file('filial_foto');
            $nome = time() . '_' . $arquivo->getClientOriginalName();

            $arquivo->move(public_path('images/emp_filiais_logo'), $nome);

            $dados['filial_foto'] = $nome;
        }

        Filial::create($dados);

        return redirect()->route('filiais')
            ->with('success', 'Filial cadastrada com sucesso!');
    }

    public function edit(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $filial = Filial::findOrFail($id);
        $empresas = Empresa::all();

        return view('view_controle.filiais_edit', compact('filial', 'empresas'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $filial = Filial::findOrFail($id);

        $request->validate([
            'id_emp_id' => 'required|exists:empresas,id_empresa',
            'filial_nome' => 'required|max:120',
            'filial_apelido' => 'required|max:120',
            'filial_nome_responsavel' => 'required|max:120',
            'filial_email_responsavel' => 'required|email',
            'filial_telefone_responsavel' => 'required|max:20',
            'filial_cpf' => 'nullable|max:14'
        ]);

        $dados = $request->except('filial_foto');

        // Atualiza dados básicos
        $filial->update($dados);

        // Upload de nova imagem
        if ($request->hasFile('filial_foto')) {

            // remove antiga
            if ($filial->filial_foto && file_exists(public_path('images/emp_filiais_logo/' . $filial->filial_foto))) {
                unlink(public_path('images/emp_filiais_logo/' . $filial->filial_foto));
            }

            $arquivo = $request->file('filial_foto');
            $nome = time() . '_' . $arquivo->getClientOriginalName();

            $arquivo->move(public_path('images/emp_filiais_logo'), $nome);

            $filial->update([
                'filial_foto' => $nome
            ]);
        }

        return redirect()->route('filiais')
            ->with('success', 'Filial atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $filial = Filial::findOrFail($id);

        // remove imagem
        if ($filial->filial_foto && file_exists(public_path('images/emp_filiais_logo/' . $filial->filial_foto))) {
            unlink(public_path('images/emp_filiais_logo/' . $filial->filial_foto));
        }

        $filial->delete();

        return redirect()->route('filiais')
            ->with('success', 'Filial removida com sucesso!');
    }
}
