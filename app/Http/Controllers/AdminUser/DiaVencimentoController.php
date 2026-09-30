<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\DiaVencimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class DiaVencimentoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $diasVencimento = DiaVencimento::where('id_emp_id', $user->id_emp_id)
            ->orderBy('dia_vencimento')
            ->get();

        return view('view_admin_user.view_admin.view_dias_vencimento.index', compact('diasVencimento'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'dia_vencimento' => 'required|integer|min:1|max:31',
        ], [
            'dia_vencimento.required' => 'Informe o dia de vencimento.',
            'dia_vencimento.integer' => 'O dia de vencimento deve ser um número.',
            'dia_vencimento.min' => 'O dia de vencimento deve ser maior que zero.',
            'dia_vencimento.max' => 'O dia de vencimento não pode ser maior que 31.',
        ]);

        $existe = DiaVencimento::where('id_emp_id', $user->id_emp_id)
            ->where('dia_vencimento', $request->dia_vencimento)
            ->exists();

        if ($existe) {
            return redirect()->route('dia_vencimento')
                ->withErrors(['erro' => 'Este dia de vencimento já está cadastrado.']);
        }

        DiaVencimento::create([
            'dia_vencimento' => $request->dia_vencimento,
            'id_emp_id' => $user->id_emp_id,
        ]);

        return redirect()->route('dia_vencimento')
            ->with('success', 'Dia de vencimento cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $user = Auth::user();

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $diaVencimento = DiaVencimento::where('id_dia_vencimento', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        return view('view_admin_user.view_admin.view_dias_vencimento.edit', compact('diaVencimento'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $diaVencimento = DiaVencimento::where('id_dia_vencimento', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $request->validate([
            'dia_vencimento' => 'required|integer|min:1|max:31',
        ], [
            'dia_vencimento.required' => 'Informe o dia de vencimento.',
            'dia_vencimento.integer' => 'O dia de vencimento deve ser um número.',
            'dia_vencimento.min' => 'O dia de vencimento deve ser maior que zero.',
            'dia_vencimento.max' => 'O dia de vencimento não pode ser maior que 31.',
        ]);

        $existe = DiaVencimento::where('id_emp_id', $user->id_emp_id)
            ->where('dia_vencimento', $request->dia_vencimento)
            ->where('id_dia_vencimento', '!=', $id)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['erro' => 'Este dia de vencimento já está cadastrado.']);
        }

        $diaVencimento->update([
            'dia_vencimento' => $request->dia_vencimento,
        ]);

        return redirect()->route('dia_vencimento')
            ->with('success', 'Dia de vencimento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $diaVencimento = DiaVencimento::where('id_dia_vencimento', $id)
            ->where('id_emp_id', $user->id_emp_id)
            ->firstOrFail();

        $diaVencimento->delete();

        return redirect()->route('dia_vencimento')
            ->with('success', 'Dia de vencimento removido com sucesso!');
    }
}