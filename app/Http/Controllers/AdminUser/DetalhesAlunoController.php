<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\Modalidade;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class DetalhesAlunoController extends Controller
{
    public function index($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();

        $graduacoes = DetalhesAluno::with([
            'graduacao',
            'graduacao.modalidade'
        ])
            ->where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $graduacoesTotais = Graduacao::with('modalidade')
            ->ordem()
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $responsavel = $aluno->responsavel;

        return view('view_admin_user.view_principal.view_alunos.detalhes_aluno', compact(
            'aluno',
            'graduacoes',
            'graduacoesTotais',
            'modalidades',
            'responsavel'
        ));
    }

    public function showCertificado($path)
    {
        try {
            $filePath = Crypt::decrypt($path);
        } catch (DecryptException $e) {
            abort(404);
        }

        if (!file_exists(public_path($filePath))) {
            abort(404);
        }

        return response()->file(public_path($filePath));
    }
    public function store(Request $request, $id)
    {

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $aluno = Aluno::where('id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'id_graduacao'      => 'required|exists:graduacao,id_graduacao',
            'det_data'          => [
                'required',
                'date',
                'after_or_equal:' . $aluno->aluno_nascimento,
                'before_or_equal:today'
            ],
            'det_certificado'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'det_data.after_or_equal' => 'Não é possível informar uma data anterior ao nascimento do aluno.',
            'det_data.before_or_equal' => 'Não é possível informar uma data futura.',
        ]);

        $jaExiste = DetalhesAluno::where('aluno_id_aluno', $id)
            ->where('id_graduacao', $request->id_graduacao)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'id_graduacao' => 'Erro, essa graduação já foi cadastrada.'
            ]);
        }

        $user = Auth::user();

        $dados = [
            'aluno_id_aluno' => $id,
            'id_graduacao'   => $request->id_graduacao,
            'det_data'       => $request->det_data,
            'id_emp_id'      => $user->id_emp_id,
        ];

        if ($request->hasFile('det_certificado')) {
            $file = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos-certificados'), $filename);
            $dados['det_certificado'] = 'images/alunos-certificados/' . $filename;
        }

        DetalhesAluno::create($dados); // Agora vai salvar o caminho corretamente

        return redirect()
            ->route('detalhes-aluno.index', Crypt::encrypt($id))
            ->with('success', 'Graduação cadastrada com sucesso!');
    }


    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $detalhe = DetalhesAluno::where('id_det_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $aluno = $detalhe->aluno;
        $responsavel = $aluno->responsavel;

        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();
        $graduacoesTotais = Graduacao::ordem()->get();

        return view('view_admin_user.view_principal.view_alunos.detalhes_aluno_edit', compact(
            'detalhe',
            'aluno',
            'modalidades',
            'graduacoesTotais',
            'responsavel'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $detalhe = DetalhesAluno::where('id_det_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $aluno = $detalhe->aluno;

        $request->validate([
            'id_graduacao'      => 'sometimes|exists:graduacao,id_graduacao',
            'det_data'          => [
                'sometimes',
                'date',
                'after_or_equal:' . $aluno->aluno_nascimento,
                'before_or_equal:today'
            ],
            'det_certificado'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'det_data.after_or_equal' => 'Não é possível informar uma data anterior ao nascimento do aluno.',
            'det_data.before_or_equal' => 'Não é possível informar uma data futura.',
        ]);
        
        $jaExiste = DetalhesAluno::where('aluno_id_aluno', $detalhe->aluno_id_aluno)
            ->where('id_graduacao', $request->id_graduacao)
            ->where('id_det_aluno', '!=', $detalhe->id_det_aluno)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'id_graduacao' => 'Erro, essa graduação já foi cadastrada.'
            ]);
        }

        $dados = $request->only([
            'id_graduacao',
            'det_data'
        ]);

        if ($request->hasFile('det_certificado')) {
            // Remove arquivo antigo
            if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
                unlink(public_path($detalhe->det_certificado));
            }

            $file = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/alunos-certificados'), $filename);
            $dados['det_certificado'] = 'images/alunos-certificados/' . $filename;
        }

        $detalhe->update($dados);

        return redirect()->route('detalhes-aluno.index', Crypt::encrypt($detalhe->aluno_id_aluno))
            ->with('success', 'Graduação do aluno atualizada com sucesso!');
    }


    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $detalhe = DetalhesAluno::where('id_det_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
            unlink(public_path($detalhe->det_certificado));
        }
        $alunoId = $detalhe->aluno_id_aluno;

        $detalhe->delete();

        return redirect()
            ->route('detalhes-aluno.index', Crypt::encrypt($alunoId))
            ->with('success', 'Graduação removida com sucesso!');
    }
}
