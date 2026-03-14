<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class EmpresaFilialScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // filtra pela empresa
        if ($user->id_emp_id) {
            $builder->where('id_emp_id', $user->id_emp_id);
        }

        // se for filial, filtra também pela filial
        if ($user->id_filial_id) {
            $builder->where('id_filial_id', $user->id_filial_id);
        }
    }
}