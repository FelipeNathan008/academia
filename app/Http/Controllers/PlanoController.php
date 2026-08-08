<?php

namespace App\Http\Controllers;

use App\Models\Plano;

class PlanoController extends Controller
{
    public function planosPublicos()
    {
        $planos = Plano::orderByRaw("
            CASE
                WHEN plano_tipo = 'financeiro' THEN 999
                ELSE plano_valor
            END
        ")->get();

        return view('planos', compact('planos'));
    }
}