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
use Illuminate\Support\Facades\DB;
use App\Models\Matricula;

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
        if (!$professor) abort(403);

        $user = Auth::user();

        $aluno = Aluno::whereHas('matriculas.grade', function ($query) use ($professor) {
            $query->where('professor_id_professor', $professor->id_professor);
        })->findOrFail($id);

        $graduacoes = DetalhesAluno::with(['graduacao', 'graduacao.modalidade'])
            ->where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->get()
            ->sortByDesc('graduacao.gradu_ordem');

        $graduacoesTotais = Graduacao::with('modalidade')
            ->ordem()
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();

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

        $user = Auth::user();

        $request->validate([
            'id_graduacao' => 'required|exists:graduacao,id_graduacao',
            'det_data' => [
                'required',
                'date',
                'after_or_equal:' . $aluno->aluno_nascimento,
                'before_or_equal:today',
            ],
            'det_certificado' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'confirma_apagar_frequencias' => 'required|accepted',
        ], [
            'det_data.after_or_equal' => 'Não é possível informar uma data anterior ao nascimento do aluno.',
            'det_data.before_or_equal' => 'Não é possível informar uma data futura.',
            'confirma_apagar_frequencias.accepted' => 'Você precisa confirmar que está ciente de que as frequências serão apagadas.',
        ]);

        $jaExiste = DetalhesAluno::where('aluno_id_aluno', $id)
            ->where('id_graduacao', $request->id_graduacao)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors(['id_graduacao' => 'Essa graduação já foi cadastrada.']);
        }

        $graduacao = Graduacao::with('modalidade')->findOrFail($request->id_graduacao);
        $modalidadeNome = $graduacao->modalidade->mod_nome;

        // Verifica se existe matrícula ATIVA na modalidade da graduação
        $matriculaAtiva = Matricula::where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('matri_status', 'Matriculado')
            ->whereHas('grade', function ($q) use ($modalidadeNome) {
                $q->where('grade_modalidade', $modalidadeNome);
            })
            ->exists();

        if (!$matriculaAtiva) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_graduacao' => "O aluno não possui uma matrícula ativa na modalidade {$modalidadeNome}. Só é possível cadastrar uma graduação quando a matrícula da mesma modalidade estiver com status 'Matriculado'."
                ]);
        }

        // Matrícula(s) ativa(s) do aluno na modalidade da graduação selecionada
        $matriculasAtivas = Matricula::where('aluno_id_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('matri_status', 'Matriculado')
            ->whereHas('grade', function ($q) use ($modalidadeNome) {
                $q->where('grade_modalidade', $modalidadeNome);
            })
            ->get();

        DB::beginTransaction();

        try {
            $totalFrequenciasApagadas = 0;

            foreach ($matriculasAtivas as $matricula) {
                $totalFrequenciasApagadas += $matricula->frequencias()->count();
                $matricula->frequencias()->delete();
            }

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

            DetalhesAluno::create($dados);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'id_graduacao' => 'Erro ao processar a graduação. Nada foi alterado. Tente novamente.'
            ]);
        }

        $mensagem = 'Graduação cadastrada com sucesso!';

        if ($totalFrequenciasApagadas > 0) {
            $mensagem .= " {$totalFrequenciasApagadas} registro(s) de frequência da matrícula ativa desta modalidade foram apagados.";
        }

        return redirect()
            ->route('professor-detalhes-aluno.index', Crypt::encrypt($id))
            ->with('success', $mensagem);
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }


        $user    = Auth::user();
        $detalhe = DetalhesAluno::where('id_det_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $aluno           = $detalhe->aluno;
        $modalidades     = Modalidade::where('id_emp_id', $user->id_emp_id)->get();
        $graduacoesTotais = Graduacao::with('modalidade')
            ->ordem()
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

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

        $user    = Auth::user();
        $detalhe = DetalhesAluno::where('id_det_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $aluno = $detalhe->aluno;

        $request->validate([
            'id_graduacao'    => 'sometimes|exists:graduacao,id_graduacao',
            'det_data'        => [
                'sometimes',
                'date',
                'after_or_equal:' . $aluno->aluno_nascimento,
                'before_or_equal:today',
            ],
            'det_certificado' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'det_data.after_or_equal'  => 'Não é possível informar uma data anterior ao nascimento do aluno.',
            'det_data.before_or_equal' => 'Não é possível informar uma data futura.',
        ]);

        $jaExiste = DetalhesAluno::where('aluno_id_aluno', $detalhe->aluno_id_aluno)
            ->where('id_graduacao', $request->id_graduacao)
            ->where('id_det_aluno', '!=', $detalhe->id_det_aluno)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors(['id_graduacao' => 'Essa graduação já foi cadastrada.']);
        }

        $dados = $request->only(['id_graduacao', 'det_data']);

        if ($request->hasFile('det_certificado')) {
            if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
                unlink(public_path($detalhe->det_certificado));
            }

            $file     = $request->file('det_certificado');
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

        $user    = Auth::user();
        $detalhe = DetalhesAluno::where('id_det_aluno', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

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
