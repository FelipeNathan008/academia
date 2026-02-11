<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    ResponsavelController,
    AlunoController,
    MatriculaController,
    DetalhesAlunoController,
    ProfessorController,
    DetalhesProfessorController,
    FinanceiroController,
    GraduacaoController,
    ModalidadeController,
    HorarioTreinoController,
    GradeHorarioController
};

Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // responsaveis
    Route::get('/responsaveis', [ResponsavelController::class, 'index'])->name('responsaveis');
    Route::post('/responsaveis', [ResponsavelController::class, 'store'])->name('responsaveis.store');
    Route::get('/responsaveis/{id}/edit', [ResponsavelController::class, 'edit'])->name('responsaveis.edit');
    Route::put('/responsaveis/{id}', [ResponsavelController::class, 'update'])->name('responsaveis.update');
    Route::delete('/responsaveis/{id}', [ResponsavelController::class, 'destroy'])->name('responsaveis.destroy');

    //alunos
    Route::get('/responsaveis/{id}/alunos', [AlunoController::class, 'index'])->name('alunos');
    Route::post('/responsaveis/{id}/alunos', [AlunoController::class, 'store'])->name('alunos.store');
    Route::get('/alunos/{id}/editar', [AlunoController::class, 'edit'])->name('alunos.edit');
    Route::put('/alunos/{id}', [AlunoController::class, 'update'])->name('alunos.update');

    Route::delete('/alunos/{id}', [AlunoController::class, 'destroy'])->name('alunos.destroy');


    // matrícula
    Route::get('/professor/{id}/turmas', [MatriculaController::class, 'getTurmasPorProfessor']);
    Route::get('/matriculas', [MatriculaController::class, 'indexSidebar'])->name('matricula.index');

    Route::get('/alunos/{id}/matricula', [MatriculaController::class, 'index'])->name('matricula');
    Route::get('/alunos/{id}/matricula/create', [MatriculaController::class, 'create'])->name('matricula.create');
    Route::post('/alunos/{id}/matricula', [MatriculaController::class, 'store'])->name('matricula.store');
    Route::get('/matricula/{id}', [MatriculaController::class, 'show'])->name('matricula.show');
    Route::delete('/matricula/{id}', [MatriculaController::class, 'destroy'])->name('matricula.destroy');

    // Financeiro
    Route::get('/financeiro/{id}', [FinanceiroController::class, 'index'])->name('financeiro.index');


    // Detalhes do aluno (Graduações)
    Route::get('/alunos/{id}/detalhes', [DetalhesAlunoController::class, 'index'])->name('detalhes-aluno.index');
    Route::post('/alunos/{id}/detalhes', [DetalhesAlunoController::class, 'store'])->name('detalhes-aluno.store');
    Route::get('/detalhes-aluno/{id}/edit', [DetalhesAlunoController::class, 'edit'])->name('detalhes-aluno.edit');
    Route::put('/detalhes-aluno/{id}', [DetalhesAlunoController::class, 'update'])->name('detalhes-aluno.update');
    Route::delete('/detalhes-aluno/{id}', [DetalhesAlunoController::class, 'destroy'])->name('detalhes-aluno.destroy');


    //professores
    Route::get('/professores', [ProfessorController::class, 'index'])->name('professores');
    Route::post('/professores', [ProfessorController::class, 'store'])->name('professores.store');
    Route::get('/professores/{id}/edit', [ProfessorController::class, 'edit'])->name('professores.edit');
    Route::put('/professores/{id}', [ProfessorController::class, 'update'])->name('professores.update');

    //detalhes do professor
    Route::get('/professores/{id}/detalhes', [DetalhesProfessorController::class, 'index'])->name('detalhes-professor.index');
    Route::post('/professores/{id}/detalhes', [DetalhesProfessorController::class, 'store'])->name('detalhes-professor.store');
    Route::get('/detalhes-professor/{id}/edit', [DetalhesProfessorController::class, 'edit'])->name('detalhes-professor.edit');
    Route::put('/detalhes-professor/{id}', [DetalhesProfessorController::class, 'update'])->name('detalhes-professor.update');
    Route::delete('/detalhes-professor/{id}', [DetalhesProfessorController::class, 'destroy'])->name('detalhes-professor.destroy');


    Route::get('/graduacoes', [GraduacaoController::class, 'index'])->name('graduacoes');
    Route::post('/graduacoes', [GraduacaoController::class, 'store'])->name('graduacoes.store');
    Route::get('/graduacoes/{id}/edit', [GraduacaoController::class, 'edit'])->name('graduacoes.edit');
    Route::put('/graduacoes/{id}', [GraduacaoController::class, 'update'])->name('graduacoes.update');

    Route::get('/modalidades', [ModalidadeController::class, 'index'])->name('modalidades');
    Route::post('/modalidades', [ModalidadeController::class, 'store'])->name('modalidades.store');
    Route::get('/modalidades/{id}/edit', [ModalidadeController::class, 'edit'])->name('modalidades.edit');
    Route::put('/modalidades/{id}', [ModalidadeController::class, 'update'])->name('modalidades.update');

    Route::get('/horario-treino', [HorarioTreinoController::class, 'index'])->name('horario_treino');
    Route::post('/horario-treino', [HorarioTreinoController::class, 'store'])->name('horario_treino.store');
    Route::get('/horario-treino/{id}/edit', [HorarioTreinoController::class, 'edit'])->name('horario_treino.edit');
    Route::put('/horario-treino/{id}', [HorarioTreinoController::class, 'update'])->name('horario_treino.update');
    Route::delete('/horario-treino/{id}', [HorarioTreinoController::class, 'destroy'])->name('horario_treino.destroy');

    Route::get('/grade_horarios', [GradeHorarioController::class, 'index'])->name('grade_horarios');
    Route::post('/grade_horarios', [GradeHorarioController::class, 'store'])->name('grade_horarios.store');
    Route::get('/grade_horarios/{id}/edit', [GradeHorarioController::class, 'edit'])->name('grade_horarios.edit');
    Route::put('grade_horarios/update/{id}', [GradeHorarioController::class, 'update'])->name('grade_horarios.update');
    Route::delete('/grade_horarios/{id}', [GradeHorarioController::class, 'destroy'])->name('grade_horarios.destroy');
});


require __DIR__ . '/auth.php';
