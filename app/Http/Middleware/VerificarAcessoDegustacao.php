<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarAcessoDegustacao
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Usuário não autenticado
        if (!$user) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Busca a empresa do usuário
        |--------------------------------------------------------------------------
        */

        $empresa = $user->empresa;

        if (!$empresa) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Se não estiver em degustação, acesso normal
        |--------------------------------------------------------------------------
        */

        if ($empresa->emp_status !== 'Degustação') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Verifica fim da degustação
        |--------------------------------------------------------------------------
        */

        $fimDegustacao = $empresa->emp_degustacao_fim?->startOfDay();
        $hoje = now()->startOfDay();

        // Ainda está dentro do período de degustação
        if (!$fimDegustacao || $hoje->lte($fimDegustacao)) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | DEGUSTAÇÃO ENCERRADA
        |--------------------------------------------------------------------------
        |
        | Essas rotas continuam disponíveis para o administrador
        | conseguir contratar um plano.
        |
        */

        if (
            $request->routeIs('admin.planos') ||
            $request->routeIs('contrato.create') ||
            $request->routeIs('contrato.store') ||
            $request->routeIs('contrato.show') ||
            $request->routeIs('empresa') ||
            $request->routeIs('empresa.update') ||
            $request->routeIs('admin.controle') ||
            $request->routeIs('empresa.store') ||
            $request->routeIs('logout')
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Bloqueia o restante do sistema
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.planos')
            ->with(
                'error',
                'Seu período de degustação terminou. Para continuar utilizando a plataforma, contrate um plano.'
            );
    }
}
