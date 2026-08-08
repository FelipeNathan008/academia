<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class SuperAdminPlanoController extends Controller
{
    public function index()
    {
        $planos = Plano::orderBy('plano_valor')->get();

        return view(
            'view_superadmin.planos.index',
            compact('planos')
        );
    }

    public function visualizar()
    {
        $planos = Plano::orderByRaw("
        CASE
            WHEN plano_tipo = 'financeiro' THEN 999999
            ELSE plano_valor
        END
    ")->get();

        return view(
            'view_superadmin.planos.visualizar',
            compact('planos')
        );
    }

    public function store(Request $request)
    {
        // Converte "1.500,50" para "1500.50"
        $request->merge([
            'plano_valor' => str_replace(
                ',',
                '.',
                str_replace('.', '', $request->plano_valor)
            )
        ]);

        $request->validate([
            'plano_nome' => 'required|string|max:100',
            'plano_tipo' => 'required|in:normal,financeiro',
            'plano_valor' => 'required|numeric|min:0',
            'plano_quantidade' => 'nullable|integer|min:1',
            'plano_desc' => 'required|string',
        ]);

        $descricao = array_values(array_filter(
            array_map(
                'trim',
                preg_split('/\r\n|\r|\n/', $request->plano_desc)
            )
        ));

        Plano::create([
            'plano_nome' => $request->plano_nome,
            'plano_tipo' => $request->plano_tipo,
            'plano_valor' => $request->plano_valor,
            'plano_quantidade' => $request->plano_quantidade,
            'plano_desc' => $descricao,
        ]);

        return redirect()
            ->route('superadmin.planos')
            ->with('success', 'Plano cadastrado com sucesso.');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $plano = Plano::findOrFail($id);

        return view(
            'view_superadmin.planos.edit',
            compact('plano')
        );
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $request->merge([
            'plano_valor' => str_replace(
                ',',
                '.',
                str_replace('.', '', $request->plano_valor)
            )
        ]);

        $request->validate([
            'plano_nome' => 'required|string|max:100',
            'plano_tipo' => 'required|in:normal,financeiro',
            'plano_valor' => 'required|numeric|min:0',
            'plano_quantidade' => 'nullable|integer|min:1',
            'plano_desc' => 'required|string',
        ]);

        $descricao = array_values(array_filter(
            array_map(
                'trim',
                preg_split('/\r\n|\r|\n/', $request->plano_desc)
            )
        ));

        $plano = Plano::findOrFail($id);

        $plano->update([
            'plano_nome' => $request->plano_nome,
            'plano_tipo' => $request->plano_tipo,
            'plano_valor' => $request->plano_valor,
            'plano_quantidade' => $request->plano_quantidade,
            'plano_desc' => $descricao,
        ]);

        return redirect()
            ->route('superadmin.planos')
            ->with('success', 'Plano atualizado com sucesso.');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        Plano::findOrFail($id)->delete();

        return redirect()
            ->route('superadmin.planos')
            ->with('success', 'Plano removido com sucesso.');
    }
}
