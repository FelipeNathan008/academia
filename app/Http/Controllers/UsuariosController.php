<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa; // se quiser trazer dados da empresa
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class UsuariosController extends Controller
{
    /**
     * Lista os usuários de uma empresa específica
     *
     * @param string $empresaIdCripto
     */
    public function index()
    {
        $users = User::all();

        return view('dashboard', compact(''));
    }
}
