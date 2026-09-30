<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Faz a autenticação usando email + role + senha
        $request->authenticate();

        // Regenera a sessão por segurança
        $request->session()->regenerate();

        $user = Auth::user();

        // Redireciona de acordo com o perfil escolhido
        return match ($user->role) {

            'admin' => redirect()->route('dashboard'),

            'professor' => redirect()->route('dashboard-professor'),

            'aluno' => redirect()->route('dashboard-aluno'),

            'superadmin' => redirect()->route('dashboard-superadmin'),

            default => abort(403),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
