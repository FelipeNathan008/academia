<?php

namespace App\Http\Controllers\AlunoUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Traits\ProcessaFotoTrait;

class AlunoUserAlunoController extends Controller
{
    use ProcessaFotoTrait;

    public function index()
    {
        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }

        $alunos = Aluno::with([
            'matriculas',
            'detalhes'
        ])
            ->where(
                'responsavel_id_responsavel',
                $responsavel->id_responsavel
            )
            ->get();

        return view(
            'view_aluno_user.aluno.index',
            compact(
                'responsavel',
                'alunos'
            )
        );
    }

    public function show($id)
    {
        try {

            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {

            abort(404);
        }


        $user = Auth::user();

        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }


        $aluno = Aluno::with([
            'responsavel',
            'matriculas',
            'detalhes'
        ])
            ->where('id_aluno', $id)
            ->where(
                'responsavel_id_responsavel',
                $responsavel->id_responsavel
            )
            ->firstOrFail();


        return view(
            'view_aluno_user.aluno.show',
            compact('aluno')
        );
    }


    public function update(Request $request, $id)
    {
        // DESCRIPTOGRAFAR ID
        try {

            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {

            abort(404);
        }


        // USUÁRIO LOGADO
        $user = Auth::user();


        // RESPONSÁVEL DO USUÁRIO
        $responsavel = $user->responsavel;

        if (!$responsavel) {
            abort(403);
        }


        // BUSCAR ALUNO GARANTINDO QUE PERTENCE
        // AO RESPONSÁVEL LOGADO
        $aluno = Aluno::where('id_aluno', $id)
            ->where(
                'responsavel_id_responsavel',
                $responsavel->id_responsavel
            )
            ->firstOrFail();


        $request->validate([

            'aluno_nome' => [
                'required',
                'string',
                'max:120',
            ],

            'aluno_parentesco' => [
                'required',
                'string',
                'max:60',
            ],

            'aluno_nascimento' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(6)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],

            'aluno_bolsista' => [
                'required',
                'in:sim,nao',
            ],

            'aluno_desc' => [
                'required',
                'string',
            ],

            'aluno_foto' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],

        ]);

        if ($request->hasFile('aluno_foto')) {

            // Remove a foto antiga
            if ($aluno->aluno_foto) {

                $this->removerFoto(
                    'alunos',
                    $aluno->aluno_foto
                );
            }

            // Processa a nova foto
            $filename = $this->processarFoto(
                $request->file('aluno_foto'),
                'alunos'
            );

            $aluno->aluno_foto = $filename;
        }

        $aluno->aluno_nome = $request->aluno_nome;
        $aluno->aluno_parentesco = $request->aluno_parentesco;
        $aluno->aluno_nascimento = $request->aluno_nascimento;
        $aluno->aluno_bolsista = $request->aluno_bolsista;
        $aluno->aluno_desc = $request->aluno_desc;


        $aluno->save();

        return redirect()
            ->route(
                'aluno.show',
                Crypt::encrypt($aluno->id_aluno)
            )
            ->with(
                'success',
                'Aluno atualizado com sucesso!'
            );
    }
}
