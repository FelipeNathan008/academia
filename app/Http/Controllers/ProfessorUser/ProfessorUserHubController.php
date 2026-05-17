<?php

namespace App\Http\Controllers\professorUser;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfessorUserHubController extends Controller
{
    public function hub($id)
    {
        try {
            $idAluno = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $aluno = Aluno::with(['responsavel'])->findOrFail($idAluno);
        $responsavel = $aluno->responsavel;

        return view('view_professor_user.hub', compact('aluno', 'responsavel'));
    }
}
