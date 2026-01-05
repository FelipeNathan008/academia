<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\ModalidadeController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\GradeHorarioController;
use App\Http\Controllers\GraduacaoController;
use App\Http\Controllers\DetalhesProfessorController;
use App\Http\Controllers\DetalhesMatriculaController;
use App\Http\Controllers\ValorAulaController;
use App\Http\Controllers\MensalidadeController;
use App\Http\Controllers\FrequenciaAlunoController;
use App\Http\Controllers\DetalhesMensalidadeController;

Route::apiResource('alunos', AlunoController::class);
Route::apiResource('responsaveis', ResponsavelController::class);
Route::apiResource('modalidades', ModalidadeController::class);
Route::apiResource('matriculas', MatriculaController::class);
Route::apiResource('professores', ProfessorController::class);
Route::apiResource('grades', GradeHorarioController::class);
Route::apiResource('graduacoes', GraduacaoController::class);

Route::apiResource('detalhes-professor', DetalhesProfessorController::class)
    ->only(['index', 'store', 'show', 'destroy']);

Route::apiResource('detalhes-matricula', DetalhesMatriculaController::class);
Route::apiResource('valor-aula', ValorAulaController::class);
Route::apiResource('mensalidade', MensalidadeController::class);
Route::apiResource('frequencia-aluno', FrequenciaAlunoController::class);
Route::apiResource('detalhes-mensalidade', DetalhesMensalidadeController::class);
