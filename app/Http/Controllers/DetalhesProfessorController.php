<?php

namespace App\Http\Controllers;

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

        $graduacoes = DetalhesProfessor::where('professor_id_professor', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->ordenarPorFaixa()
            ->orderBy('det_grau')
            ->get();

        $graduacoesTotais = Graduacao::ordenarPorFaixa()
            ->orderBy('gradu_grau')
            ->get();

        return view('view_professores.detalhes_professor', compact(
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

        $request->validate([
            'det_gradu_nome_cor' => 'required|string|max:80',
            'det_grau'           => 'required|integer',
            'det_modalidade'     => 'required|string|max:50',
            'det_data'           => 'required|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $filename = null;
        $jaExiste = DetalhesProfessor::where('professor_id_professor', $id)
            ->where('det_grau', $request->det_grau)
            ->where('det_modalidade', $request->det_modalidade)
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
            'det_gradu_nome_cor'     => $request->det_gradu_nome_cor,
            'det_grau'               => $request->det_grau,
            'det_modalidade'         => $request->det_modalidade,
            'det_data'               => $request->det_data,
            'det_certificado'        => $filename ? 'images/professores-certificados/' . $filename : null,
            'id_emp_id'              => $user->id_emp_id
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

        $professor = $detalhe->professor;
        $modalidades = Modalidade::where('id_emp_id', $user->id_emp_id)->get();
        $graduacoesTotais = Graduacao::all();

        return view('view_professores.detalhes_professor_edit', compact(
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

        $request->validate([
            'det_gradu_nome_cor' => 'required|string|max:80',
            'det_grau'           => 'required|integer',
            'det_modalidade'     => 'required|string|max:50',
            'det_data'           => 'required|date',
            'det_certificado'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $jaExiste = DetalhesProfessor::where('professor_id_professor', $detalhe->professor_id_professor)
            ->where('det_grau', $request->det_grau)
            ->where('det_modalidade', $request->det_modalidade)
            ->where('id_emp_id', $user->id_emp_id)
            ->where('id_det_professor', '!=', $detalhe->id_det_professor)
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'det_grau' => 'Erro, essa graduação já foi cadastrada.'
            ])->withInput();
        }

        $dados = $request->all();

        if ($request->hasFile('det_certificado')) {
            if ($detalhe->det_certificado && file_exists(public_path($detalhe->det_certificado))) {
                unlink(public_path($detalhe->det_certificado));
            }

            $file = $request->file('det_certificado');
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
