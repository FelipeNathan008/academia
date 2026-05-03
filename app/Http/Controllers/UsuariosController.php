<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\Professor;
use App\Models\Responsavel;
use Illuminate\Support\Facades\Auth;
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

    public function empresaIndex()
    {
        $user = Auth::user();

        $empresa = Empresa::where('id_empresa', $user->id_emp_id)
            ->firstOrFail();

        $users = User::where('id_emp_id', $empresa->id_empresa)
            ->whereNull('id_filial_id')
            ->get();

        // IDs já utilizados
        $professoresUsados = User::whereNotNull('professor_id')->pluck('professor_id');
        $responsaveisUsados = User::whereNotNull('responsavel_id')->pluck('responsavel_id');

        // FILTRO
        $professores = Professor::where('id_emp_id', $empresa->id_empresa)
            ->whereNull('id_filial_id')
            ->whereNotIn('id_professor', $professoresUsados)
            ->get();

        $responsaveis = Responsavel::where('id_emp_id', $empresa->id_empresa)
            ->whereNull('id_filial_id')
            ->whereNotIn('id_responsavel', $responsaveisUsados)
            ->get();

        return view('view_controle.usuarios_empresa', compact('users', 'empresa', 'responsaveis', 'professores'));
    }

    public function buscarPessoas(Request $request)
    {
        $tipo = $request->tipo; // professor ou responsavel
        $busca = $request->busca;

        if ($tipo === 'professor') {

            $usados = User::whereNotNull('professor_id')->pluck('professor_id');

            $dados = Professor::whereNotIn('id_professor', $usados)
                ->when($busca, fn($q) => $q->where('prof_nome', 'like', "%{$busca}%"))
                ->paginate(5);
        } else {

            $usados = User::whereNotNull('responsavel_id')->pluck('responsavel_id');

            $dados = Responsavel::whereNotIn('id_responsavel', $usados)
                ->when($busca, fn($q) => $q->where('resp_nome', 'like', "%{$busca}%"))
                ->paginate(2);
        }

        return response()->json($dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,professor,aluno',
            'id_emp_id' => 'required|exists:empresas,id_empresa',
            'id_filial_id' => 'nullable|exists:filiais,id_filial',

            'professor_id' => 'nullable|exists:professor,id_professor',
            'responsavel_id' => 'nullable|exists:responsavel,id_responsavel',
        ], [
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.'
        ]);

        $professorId = null;
        $responsavelId = null;

        if ($request->role === 'professor') {
            $professorId = $request->professor_id;
        }

        if ($request->role === 'aluno') {
            $responsavelId = $request->responsavel_id;
        }

        if ($request->role === 'professor' && $request->professor_id) {
            if (User::where('professor_id', $request->professor_id)->exists()) {
                return back()->withErrors(['Professor já vinculado a outro usuário.']);
            }
        }

        if ($request->role === 'aluno' && $request->responsavel_id) {
            if (User::where('responsavel_id', $request->responsavel_id)->exists()) {
                return back()->withErrors(['Responsável já vinculado a outro usuário.']);
            }
        }


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'id_emp_id' => $request->id_emp_id,
            'id_filial_id' => $request->id_filial_id ?: null,

            'professor_id' => $professorId,
            'responsavel_id' => $responsavelId,
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

    // EDIT EMPRESA
    public function editEmpresa($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);
            $user = User::findOrFail($id);

            return view('view_controle.usuarios_edit_empresa', compact('user'));
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    // UPDATE EMPRESA
    public function updateEmpresa(Request $request, $idCriptografado)
    {
        $id = Crypt::decrypt($idCriptografado);
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,professor,aluno',

            'professor_id' => 'nullable|exists:professor,id_professor',
            'responsavel_id' => 'nullable|exists:responsavel,id_responsavel',
        ], [
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'id_emp_id' => $user->id_emp_id,
            'id_filial_id' => null
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('usuarios.empresa')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function update(Request $request, $idCriptografado)
    {
        $id = Crypt::decrypt($idCriptografado);
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,professor,aluno',

            'professor_id' => 'nullable|exists:professor,id_professor',
            'responsavel_id' => 'nullable|exists:responsavel,id_responsavel',
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

        if ($user->id_filial_id) {
            return redirect()->route('usuarios.index', Crypt::encrypt($user->id_filial_id));
        } else {
            return redirect()->route('usuarios.empresa');
        }
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
