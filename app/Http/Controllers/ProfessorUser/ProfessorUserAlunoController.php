<?php

namespace App\Http\Controllers\professorUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class ProfessorUserAlunoController extends Controller
{

    public function index(Request $request)
    {
        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        // QUERY BASE (apenas alunos do professor)
        $query = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })->with([
            'responsavel',
            'matriculas.grade',
            'matriculas.mensalidades.detalhes'
        ]);

        // FILTROS

        if ($request->filled('nome')) {
            $query->where('aluno_nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('responsavel')) {
            $query->whereHas('responsavel', function ($q) use ($request) {
                $q->where('resp_nome', 'like', '%' . $request->responsavel . '%');
            });
        }

        if ($request->filled('bolsista')) {
            $query->where('aluno_bolsista', $request->bolsista);
        }

        $totalAlunos = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })->count();

        $alunos = $query->paginate(10);

        return view('view_professor_user.aluno.index', compact(
            'alunos',
            'totalAlunos'
        ));
    }

    public function editAluno(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->with('responsavel')
            ->findOrFail($id);

        return view('view_professor_user.aluno.edit', [
            'aluno' => $aluno,
            'responsavel' => $aluno->responsavel
        ]);
    }

    public function updateAluno(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = Auth::user();
        $professor = $user->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($q) use ($professor) {
            $q->where('professor_id_professor', $professor->id_professor);
        })
            ->where('id_aluno', $id)
            ->firstOrFail();

        $request->validate([
            'aluno_nome' => 'required|string|max:120',
            'aluno_nascimento' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(6)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],
            'aluno_parentesco' => 'required|string|max:60',
            'aluno_desc' => 'required|string',
            'aluno_foto' => 'nullable|image|max:2048',
        ]);

        // FOTO
        if ($request->hasFile('aluno_foto')) {

            if (
                $aluno->aluno_foto &&
                file_exists(public_path('images/alunos/' . $aluno->aluno_foto))
            ) {
                unlink(public_path('images/alunos/' . $aluno->aluno_foto));
            }

            $file = $request->file('aluno_foto');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images/alunos'), $filename);

            $aluno->aluno_foto = $filename;
        }

        $aluno->aluno_nome = $request->aluno_nome;
        $aluno->aluno_parentesco = $request->aluno_parentesco;
        $aluno->aluno_nascimento = $request->aluno_nascimento;
        $aluno->aluno_desc = $request->aluno_desc;

        $aluno->save();

        return redirect()
            ->route('professor-alunos')
            ->with('success', 'Aluno atualizado com sucesso!');
    }
    public function show(string $id)
    {
        $id = decrypt($id);

        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }

        $aluno = Aluno::whereHas('matriculas.grade', function ($query) use ($professor) {
            $query->where('professor_id_professor', $professor->id_professor);
        })
            ->with(['responsavel', 'matriculas.grade'])
            ->findOrFail($id);

        return view('view_professor_user.aluno.show', compact('aluno'));
    }
}
