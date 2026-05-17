<?php

namespace App\Http\Controllers\ProfessorUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\DetalhesAluno;
use App\Models\Graduacao;
use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfessorUserDetalhesAlunoController extends Controller
{
    public function index($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($query) use ($professor) {
            $query->where('professor_id_professor', $professor->id_professor);
        })->findOrFail($id);

        $graduacoes = DetalhesAluno::where('aluno_id_aluno', $id)
            ->ordenarPorFaixa()
            ->orderBy('det_grau')
            ->get();

        $graduacoesTotais = Graduacao::ordenarPorFaixa()
            ->orderBy('gradu_grau')
            ->get();

        $modalidades = Modalidade::all();

        return view('view_professor_user.aluno.detalhes_aluno', compact(
            'aluno',
            'graduacoes',
            'graduacoesTotais',
            'modalidades'
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

        $professor = Auth::user()->professor;

        $aluno = Aluno::whereHas('matriculas.grade', function ($query) use ($professor) {
            $query->where('professor_id_professor', $professor->id_professor);
        })->findOrFail($id);

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
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'det_grau' => 'Erro, essa graduação já foi cadastrada.'
            ])->withInput();
        }

        $dados = [
            'aluno_id_aluno'     => $id,
            'det_gradu_nome_cor' => $request->det_gradu_nome_cor,
            'det_grau'           => $request->det_grau,
            'det_modalidade'     => $request->det_modalidade,
            'det_data'           => $request->det_data,
            'id_emp_id'          => Auth::user()->id_emp_id,
        ];

        if ($request->hasFile('det_certificado')) {

            $file = $request->file('det_certificado');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images/alunos-certificados'), $filename);

            $dados['det_certificado'] = 'images/alunos-certificados/' . $filename;
        }

        DetalhesAluno::create($dados);

        return redirect()
            ->route('professor-detalhes-aluno.index', Crypt::encrypt($id))
            ->with('success', 'Graduação cadastrada com sucesso!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $detalhe = DetalhesAluno::findOrFail($id);

        $aluno = $detalhe->aluno;

        $modalidades = Modalidade::all();

        $graduacoesTotais = Graduacao::all();

        return view('view_professor_user.aluno.detalhes_aluno_edit', compact(
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

        $detalhe = DetalhesAluno::findOrFail($id);

        $request->validate([
            'det_gradu_nome_cor' => 'required|string|max:80',
            'det_grau'           => 'required|integer',
            'det_modalidade'     => 'required|string|max:100',
            'det_data'           => 'required|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $dados = $request->only([
            'det_gradu_nome_cor',
            'det_grau',
            'det_modalidade',
            'det_data'
        ]);

        if ($request->hasFile('det_certificado')) {

            if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
                unlink(public_path($detalhe->det_certificado));
            }

            $file = $request->file('det_certificado');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images/alunos-certificados'), $filename);

            $dados['det_certificado'] = 'images/alunos-certificados/' . $filename;
        }

        $detalhe->update($dados);

        return redirect()
            ->route('professor-detalhes-aluno.index', Crypt::encrypt($detalhe->aluno_id_aluno))
            ->with('success', 'Graduação atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $detalhe = DetalhesAluno::findOrFail($id);

        if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
            unlink(public_path($detalhe->det_certificado));
        }

        $alunoId = $detalhe->aluno_id_aluno;

        $detalhe->delete();

        return redirect()
            ->route('professor-detalhes-aluno.index', Crypt::encrypt($alunoId))
            ->with('success', 'Graduação removida com sucesso!');
    }
}
