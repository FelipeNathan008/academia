<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Filial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;

class UsuariosController extends Controller
{
    public function index($filialCriptografado = null)
    {
        $empresa = null;
        $filial = null;

        if ($filialCriptografado) {
            try {
                $filialId = Crypt::decrypt($filialCriptografado);
                $filial = Filial::findOrFail($filialId);
                $empresa = $filial->empresa; // pega a empresa relacionada
                $users = User::where('id_filial_id', $filial->id_filial)->get();
            } catch (DecryptException $e) {
                abort(404);
            }
        } else {
            abort(404);
        }

        return view('view_controle.usuarios', compact('users', 'empresa', 'filial'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
            'id_emp_id' => 'required|exists:empresas,id_empresa',
            'id_filial_id' => 'nullable|exists:filiais,id_filial'
        ], [
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'id_emp_id' => $request->id_emp_id,
            'id_filial_id' => $request->id_filial_id
        ]);

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
            $user = User::findOrFail($id);

            $empresas = Empresa::all();
            $filiais = Filial::all();
            $filial = $user->filial;

            return view('view_controle.usuarios_edit', compact('user', 'empresas', 'filiais', 'filial'));
        } catch (DecryptException $e) {
            abort(404);
        }
    }


    public function update(Request $request, $idCriptografado)
    {
        $id = Crypt::decrypt($idCriptografado);
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,user',
        ], [
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.'
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'id_emp_id' => $request->id_emp_id,
            'id_filial_id' => $request->id_filial_id
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('usuarios.index', Crypt::encrypt($user->id_filial_id))
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível excluir o usuário.');
        }
    }
}
