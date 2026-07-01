<?php


namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\DetalhesProfessor;
use App\Models\Graduacao;
use App\Models\Modalidade;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;


class DetalhesProfessorController extends Controller
{
    public function index($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();

        $graduacoes = DetalhesProfessor::with([
            'graduacao',
            'graduacao.modalidade'
        ])
            ->where('professor_id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->get()
            ->sortByDesc('graduacao.gradu_ordem');

        $graduacoesTotais = Graduacao::with('modalidade')
            ->ordem()
            ->where('id_emp_id', $user->id_emp_id)
            ->get();

        return view('view_admin_user.view_principal.view_professores.detalhes_professor', compact(
            'professor',
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

        $user = Auth::user();
        $professor = Professor::where('id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'id_graduacao' => 'required|exists:graduacao,id_graduacao',
            'det_data' => [
                'required',
                'date',
                'after_or_equal:' . $professor->prof_nascimento,
                'before_or_equal:today'
            ],
        ], [
            'det_data.after_or_equal' => 'Não é possível informar uma data anterior ao nascimento do aluno.',
            'det_data.before_or_equal' => 'Não é possível informar uma data futura.',
        ]);

        $filename = null;
        $jaExiste = DetalhesProfessor::where('professor_id_professor', $id)
            ->where('id_graduacao', $request->id_graduacao)
            ->where('id_emp_id', $user->id_emp_id)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'det_grau' => 'Erro, essa graduação já foi cadastrada.'
            ])->withInput();
        }

        if ($request->hasFile('det_certificado')) {
            $file = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores-certificados'), $filename);
        }

        DetalhesProfessor::create([
            'professor_id_professor' => $id,
            'id_graduacao' => $request->id_graduacao,
            'det_data' => $request->det_data,
            'det_certificado' => $filename ? 'images/professores-certificados/' . $filename : null,
            'id_emp_id' => $user->id_emp_id
        ]);

        return redirect()->back()->with('success', 'Graduação do professor cadastrada!');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();

        $detalhe = DetalhesProfessor::where('id_det_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $professor  = $detalhe->professor;
        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();
        $graduacoesTotais = Graduacao::with('modalidade')->ordem()->where('id_emp_id', $user->id_emp_id)->get();

        return view('view_admin_user.view_principal.view_professores.detalhes_professor_edit', compact(
            'detalhe',
            'professor',
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

        $detalhe = DetalhesProfessor::where('id_det_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $professor = $detalhe->professor;

        $request->validate([
            'id_graduacao'    => 'required|exists:graduacao,id_graduacao',
            'det_data' => [
                'required',
                'date',
                'after_or_equal:' . $professor->prof_nascimento,
                'before_or_equal:today'
            ],
            'det_certificado' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'det_data.after_or_equal' => 'Não é possível informar uma data anterior ao nascimento do aluno.',
            'det_data.before_or_equal' => 'Não é possível informar uma data futura.',
        ]);

        $jaExiste = DetalhesProfessor::where('professor_id_professor', $detalhe->professor_id_professor)
            ->where('id_graduacao', $request->id_graduacao)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_det_professor', '!=', $detalhe->id_det_professor)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'id_graduacao' => 'Erro, essa graduação já foi cadastrada.'
            ])->withInput();
        }

        $dados = [
            'id_graduacao' => $request->id_graduacao,
            'det_data'     => $request->det_data,
        ];

        if ($request->hasFile('det_certificado')) {
            if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
                unlink(public_path($detalhe->det_certificado));
            }
            $file     = $request->file('det_certificado');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/professores-certificados'), $filename);
            $dados['det_certificado'] = 'images/professores-certificados/' . $filename;
        }

        $detalhe->update($dados);

        return redirect()->route('detalhes-professor.index', Crypt::encrypt($detalhe->professor_id_professor))
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

        $detalhe = DetalhesProfessor::where('id_det_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        // Apaga o arquivo do certificado, se existir
        if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
            unlink(public_path($detalhe->det_certificado));
        }

        $professorId = $detalhe->professor_id_professor;

        $detalhe->delete();

        return redirect()
            ->route('detalhes-professor.index', Crypt::encrypt($professorId))
            ->with('success', 'Graduação removida com sucesso!');
    }
}
