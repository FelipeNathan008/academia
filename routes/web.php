<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\GraduacaoController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\DetalhesProfessorController;
use App\Http\Controllers\ModalidadeController;
use App\Http\Controllers\ResponsavelController;

// Redireciona "/" para dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// Dashboard protegido
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware('auth')
    ->name('dashboard');

// Rotas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', fn() => view('admin.dashboard'))->name('admin.dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/alunos', [AlunoController::class, 'index'])->name('alunos');
    Route::post('/alunos', [AlunoController::class, 'store'])->name('alunos.store');
    Route::get('alunos/edit/{id}', [AlunoController::class, 'edit'])->name('alunos.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/professores', [ProfessorController::class, 'index'])->name('professores');
    Route::post('/professores', [ProfessorController::class, 'store'])->name('professores.store');
    Route::get('professores/edit/{id}', [ProfessorController::class, 'edit'])->name('professores.edit');
    Route::put('professores/update/{id}', [ProfessorController::class, 'update'])->name('professores.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/graduacoes', [GraduacaoController::class, 'index'])->name('graduacoes');
    Route::post('/graduacoes', [GraduacaoController::class, 'store'])->name('graduacoes.store');
    Route::get('graduacoes/edit/{id}', [GraduacaoController::class, 'edit'])->name('graduacoes.edit');
    Route::put('graduacoes/update/{id}', [GraduacaoController::class, 'update'])->name('graduacoes.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/modalidades', [ModalidadeController::class, 'index'])->name('modalidades');
    Route::post('/modalidades', [ModalidadeController::class, 'store'])->name('modalidades.store');
    Route::get('modalidades/edit/{id}', [ModalidadeController::class, 'edit'])->name('modalidades.edit');
    Route::put('modalidades/update/{id}', [ModalidadeController::class, 'update'])->name('modalidades.update');
});


Route::middleware('auth')->group(function () {
    Route::post('/detalhes', [DetalhesProfessorController::class, 'store'])->name('detalhes.store');
    Route::delete('/detalhes/{id}', [DetalhesProfessorController::class, 'destroy'])->name('detalhes.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/responsaveis/{id}', [ResponsavelController::class, 'index'])->name('responsaveis.index');
    Route::post('/responsaveis', [ResponsavelController::class, 'store'])->name('responsaveis.store');
    Route::get('/responsaveis/{id}/edit', [ResponsavelController::class, 'edit'])->name('responsaveis.edit');
    Route::put('/responsaveis/{id}', [ResponsavelController::class, 'update'])->name('responsaveis.update');
    Route::delete('/responsaveis/{id}', [ResponsavelController::class, 'destroy'])->name('responsaveis.destroy');
});

// Breeze auth routes
require __DIR__ . '/auth.php';
