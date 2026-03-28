<?php

namespace App\Http\Controllers;

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

        $graduacoes = DetalhesAluno::where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->ordenarPorFaixa()
            ->orderBy('det_grau')
            ->get();


        $graduacoesTotais = Graduacao::ordenarPorFaixa()
            ->orderBy('gradu_grau')
            ->get();

        $responsavel = $aluno->responsavel;

        return view('view_alunos.detalhes_aluno', compact(
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
            'det_gradu_nome_cor' => 'required|string|max:80',
            'det_grau'           => 'required|integer',
            'det_modalidade'     => 'required|string|max:100',
            'det_data'           => 'required|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $jaExiste = DetalhesAluno::where('aluno_id_aluno', $id)
            ->where('det_gradu_nome_cor', $request->det_gradu_nome_cor)
            ->where('det_grau', $request->det_grau)
            ->where('det_modalidade', $request->det_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'det_grau' => 'Erro, essa graduação já foi cadastrada.'
            ])->withInput();
        }
        $user = Auth::user();

        $dados = [
            'aluno_id_aluno'     => $id,
            'det_gradu_nome_cor' => $request->det_gradu_nome_cor,
            'det_grau'           => $request->det_grau,
            'det_modalidade'     => $request->det_modalidade,
            'det_data'           => $request->det_data,
            'id_emp_id' => $user->id_emp_id,
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

        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();
        $graduacoesTotais = Graduacao::all();

        return view('view_alunos.detalhes_aluno_edit', compact(
            'detalhe',
            'aluno',
            'modalidades',
            'graduacoesTotais'
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

        $request->validate([
            'det_gradu_nome_cor' => 'sometimes|string|max:80',
            'det_grau'           => 'sometimes|integer',
            'det_modalidade'     => 'sometimes|string|max:100',
            'det_data'           => 'sometimes|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $jaExiste = DetalhesAluno::where('aluno_id_aluno', $detalhe->aluno_id_aluno)
            ->where('det_grau', $request->det_grau)
            ->where('det_modalidade', $request->det_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_det_aluno', '!=', $detalhe->id_det_aluno)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'det_grau' => 'Erro, essa graduação já foi cadastrada.'
            ])->withInput();
        }

        $dados = $request->only(['det_gradu_nome_cor', 'det_grau', 'det_modalidade', 'det_data']);

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
            ->with('success', 'Graduação do professor atualizada com sucesso!');
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
