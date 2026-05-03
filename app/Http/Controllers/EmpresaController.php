<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class EmpresaController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $empresa = Empresa::where('id_empresa', $user->id_emp_id)
            ->firstOrFail();

        return view('view_empresa.index', compact('empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'emp_nome' => 'required|string|max:255',
            'emp_apelido' => 'required|string|max:255',
            'emp_nome_responsavel' => 'required|string|max:255',
            'emp_email_responsavel' => 'required|email|max:255',
            'emp_telefone_responsavel' => 'required|max:20',
            'emp_cpf' => 'required|max:14',
            'emp_tipo' => 'required|string|max:150',
            'emp_foto' => 'nullable|image|max:2048'
        ]);

        $filename = null;

        if ($request->hasFile('emp_foto')) {

            $file = $request->file('emp_foto');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images/empresas'), $filename);
        }

        $empresa = Empresa::create([
            'emp_nome' => $request->emp_nome,
            'emp_apelido' => $request->emp_apelido,
            'emp_nome_responsavel' => $request->emp_nome_responsavel,
            'emp_email_responsavel' => $request->emp_email_responsavel,
            'emp_telefone_responsavel' => $request->emp_telefone_responsavel,
            'emp_cpf' => $request->emp_cpf,
            'emp_tipo' => $request->emp_tipo,
            'emp_foto' => $filename
        ]);
        return redirect()->route('register', ['empresa_id' => $empresa->id_empresa]);
    }

    public function edit(string $id)
    {
        $empresa = Empresa::findOrFail($id);

        return view('view_empresas.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt(urldecode($id));;
        } catch (DecryptException $e) {
            abort(404);
        }
        $empresa = Empresa::findOrFail($id);

        $request->validate([
            'emp_nome' => 'required|string|max:255',
            'emp_apelido' => 'required|string|max:255',
            'emp_nome_responsavel' => 'required|string|max:255',
            'emp_email_responsavel' => 'required|email|max:255',
            'emp_telefone_responsavel' => 'required|max:20',
            'emp_cpf' => 'required|max:14',
            'emp_tipo' => 'required|max:150',
            'emp_foto' => 'nullable|image|max:2048'
        ]);

        //dd($request->all(), $request->file('emp_foto'));
        if ($request->hasFile('emp_foto')) {

            if ($empresa->emp_foto && file_exists(public_path('images/empresas/' . $empresa->emp_foto))) {
                unlink(public_path('images/empresas/' . $empresa->emp_foto));
            }

            $file = $request->file('emp_foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/empresas'), $filename);

            $empresa->emp_foto = $filename;
        }

        $empresa->emp_nome = $request->emp_nome;
        $empresa->emp_apelido = $request->emp_apelido;
        $empresa->emp_nome_responsavel = $request->emp_nome_responsavel;
        $empresa->emp_email_responsavel = $request->emp_email_responsavel;
        $empresa->emp_telefone_responsavel = $request->emp_telefone_responsavel;
        $empresa->emp_cpf = $request->emp_cpf;
        $empresa->emp_tipo = $request->emp_tipo;

        $empresa->save();

        return redirect()->route('empresa')
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $empresa = Empresa::where('id_empresa', Auth::user()->id_emp_id)->firstOrFail();

        if ($empresa->emp_foto && file_exists(public_path('images/empresas/' . $empresa->emp_foto))) {
            unlink(public_path('images/empresas/' . $empresa->emp_foto));
        }

        $empresa->delete();

        return redirect()->route('empresas')
            ->with('success', 'Empresa removida com sucesso!');
    }
}
