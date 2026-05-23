<?php

namespace App\Http\Controllers\ProfessorUser;

use App\Http\Controllers\Controller;

use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ProfessorUserProfessorController extends Controller
{
   public function show()
    {
        $professor = Auth::user()->professor;

        if (!$professor) {
            abort(403);
        }
     

        return view('view_professor_user.professor.show', compact('professor'));
    }
}
