<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUser\{
    DashboardController,
    ResponsavelController,
    AlunoController,
    DetalhesAlunoController,
    MatriculaController,
    MensalidadeController,
    ProfessorController,
    DetalhesProfessorController,
    GradeHorarioController,
    FrequenciaAlunoController,
    GraduacaoController,
    ModalidadeController,
    HorarioTreinoController,
    PrecoModalidadeController,
    TurmaController,
    FilialController,
    UsuariosController,
    DetalhesFilialController,
    EmpresaController,
};

use App\Http\Controllers\{
    ProfileController,
    AulaController,
};

use App\Http\Controllers\ProfessorUser\{
    ProfessorUserDashboardController,
    ProfessorUserProfessorController,
    ProfessorUserAgendaController,
    ProfessorUserAlunoController,
    ProfessorUserHubController,
    ProfessorUserResponsavelController,
    ProfessorUserMatriculaController,
    ProfessorUserFinanceiroController,
    ProfessorUserDetalhesAlunoController,
    ProfessorUserFrequenciaController,
};

use App\Http\Controllers\AlunoUser\{
    AlunoUserDashboardController,
    AlunoUserAlunoController,
    AlunoUserDetalhesAlunoController,
    AlunoUserResponsavelController,
    AlunoUserMatriculaController,
    AlunoUserFinanceiroController,
    AlunoUserFrequenciaController,
};

// ROTAS PÚBLICAS

Route::get('/', fn() => view('apresentacao'))->name('apresentacao');

Route::get('/cadastro-empresa', fn() => view('cadastro_empresa'))->name('cadastro_empresa');
Route::post('/cadastro-empresa/store', [EmpresaController::class, 'store'])
    ->name('cadastro_empresa.store');

// ROTAS COMUNS

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//
// ADMIN
// 
Route::middleware(['auth'])->group(function () {

    Route::put('/mensalidade/editar-forma', [MensalidadeController::class, 'editarForma'])
        ->name('mensalidade.editarForma');

    Route::put('/mensalidade/baixar/{id}', [MensalidadeController::class, 'darBaixa'])
        ->name('mensalidade.darBaixa');

    Route::put('/mensalidade/desfazer/{id}', [MensalidadeController::class, 'desfazerBaixa'])
        ->name('mensalidade.desfazerBaixa');
});
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/principal', function () {
        return view('view_admin_user.principal');
    })->name('admin.principal');

    Route::get('/admin/administracao', function () {
        return view('view_admin_user.administracao');
    })->name('admin.administracao');

    Route::get('/admin/controle', function () {
        return view('view_admin_user.controle');
    })->name('admin.controle');

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/mensalidades-atrasadas', [DashboardController::class, 'mensalidadesAtrasadas'])->name('dashboard.mensalidadesAtrasadas');
    Route::get('/dashboard/graduacoes', [DashboardController::class, 'graduacoes'])->name('dashboard.graduacoes');

    // RESPONSÁVEIS
    Route::get('/responsaveis', [ResponsavelController::class, 'index'])->name('responsaveis');
    Route::post('/responsaveis', [ResponsavelController::class, 'store'])->name('responsaveis.store');
    Route::get('/responsaveis/{id}/edit', [ResponsavelController::class, 'edit'])->name('responsaveis.edit');
    Route::put('/responsaveis/{id}', [ResponsavelController::class, 'update'])->name('responsaveis.update');
    Route::delete('/responsaveis/{id}', [ResponsavelController::class, 'destroy'])->name('responsaveis.destroy');

    //ALUNOS
    Route::get('/responsaveis/{id}/alunos', [AlunoController::class, 'index'])->name('alunos');
    Route::post('/responsaveis/{id}/alunos', [AlunoController::class, 'store'])->name('alunos.store');
    Route::get('/alunos/{id}/editar', [AlunoController::class, 'edit'])->name('alunos.edit');
    Route::put('/alunos/{id}', [AlunoController::class, 'update'])->name('alunos.update');
    Route::get('/alunos/{id}', [AlunoController::class, 'show'])->name('alunos.show');
    Route::delete('/alunos/{id}', [AlunoController::class, 'destroy'])->name('alunos.destroy');

    // MATRÍCULA
    Route::get('/professor/{id}/turmas', [MatriculaController::class, 'getTurmasPorProfessor']);
    Route::get('/matriculas', [MatriculaController::class, 'indexSidebar'])->name('matricula.index');
    Route::get('/alunos/{id}/matricula', [MatriculaController::class, 'index'])->name('matricula');
    Route::get('/alunos/{id}/matricula/create', [MatriculaController::class, 'create'])->name('matricula.create');
    Route::post('/alunos/{id}/matricula', [MatriculaController::class, 'store'])->name('matricula.store');
    Route::get('/matricula/{id}', [MatriculaController::class, 'show'])->name('matricula.show');
    Route::delete('/matricula/{id}', [MatriculaController::class, 'destroy'])->name('matricula.destroy');


    // AULA
    Route::get('/grades/aulas', [AulaController::class, 'grades'])->name('grades.aulas');
    Route::get('/grade/{id}/aulas', [AulaController::class, 'index'])->name('aulas');
    Route::post('/grade/{id}/aulas/store', [AulaController::class, 'store'])->name('aulas.store');
    Route::get('/aulas/{id}/edit', [AulaController::class, 'edit'])->name('aulas.edit');
    Route::put('/aulas/{id}', [AulaController::class, 'update'])->name('aulas.update');
    Route::delete('/aulas/{id}', [AulaController::class, 'destroy'])->name('aulas.destroy');

    //MENSALIDADE
    Route::get('/alunos/{id}/mensalidade', [MensalidadeController::class, 'index'])->name('mensalidade');

    // DETALHES ALUNO
    Route::get('/alunos/{id}/detalhes', [DetalhesAlunoController::class, 'index'])->name('detalhes-aluno.index');
    Route::post('/alunos/{id}/detalhes', [DetalhesAlunoController::class, 'store'])->name('detalhes-aluno.store');
    Route::get('/detalhes-aluno/{id}/edit', [DetalhesAlunoController::class, 'edit'])->name('detalhes-aluno.edit');
    Route::put('/detalhes-aluno/{id}', [DetalhesAlunoController::class, 'update'])->name('detalhes-aluno.update');
    Route::delete('/detalhes-aluno/{id}', [DetalhesAlunoController::class, 'destroy'])->name('detalhes-aluno.destroy');
    Route::get('/certificado-aluno/{path}', [DetalhesAlunoController::class, 'showCertificado'])->name('detalhes-aluno.showCertificado');

    Route::get('/grade_horarios', [GradeHorarioController::class, 'index'])->name('grade_horarios');
    Route::get('/agenda', [GradeHorarioController::class, 'index'])->name('grade_horarios.visualizar');
    Route::post('/grade_horarios', [GradeHorarioController::class, 'store'])->name('grade_horarios.store');
    Route::get('/grade_horarios/{id}/edit', [GradeHorarioController::class, 'edit'])->name('grade_horarios.edit');
    Route::put('grade_horarios/update/{id}', [GradeHorarioController::class, 'update'])->name('grade_horarios.update');
    Route::delete('/grade_horarios/{id}', [GradeHorarioController::class, 'destroy'])->name('grade_horarios.destroy');

    // PROFESSORES
    Route::get('/professores', [ProfessorController::class, 'index'])->name('professores');
    Route::get('/professores/alunos', [ProfessorController::class, 'index'])->name('professores.alunos');

    Route::post('/professores', [ProfessorController::class, 'store'])->name('professores.store');
    Route::get('/professores/{id}/edit', [ProfessorController::class, 'edit'])->name('professores.edit');
    Route::put('/professores/{id}', [ProfessorController::class, 'update'])->name('professores.update');
    Route::get('/professores/{id}', [ProfessorController::class, 'show'])->name('professores.show');
    Route::delete('/professores/{id}', [ProfessorController::class, 'destroy'])->name('professores.destroy');

    // DETALHES PROFESSOR
    Route::get('/professores/{id}/detalhes', [DetalhesProfessorController::class, 'index'])->name('detalhes-professor.index');
    Route::post('/professores/{id}/detalhes', [DetalhesProfessorController::class, 'store'])->name('detalhes-professor.store');
    Route::get('/detalhes-professor/{id}/edit', [DetalhesProfessorController::class, 'edit'])->name('detalhes-professor.edit');
    Route::put('/detalhes-professor/{id}', [DetalhesProfessorController::class, 'update'])->name('detalhes-professor.update');
    Route::delete('/detalhes-professor/{id}', [DetalhesProfessorController::class, 'destroy'])->name('detalhes-professor.destroy');
    Route::get('/certificado-professor/{path}', [DetalhesProfessorController::class, 'showCertificado'])->name('certificado.show');

    // FREQUENCIA ALUNO
    Route::get('/frequencia', [FrequenciaAlunoController::class, 'listagemGrades'])->name('frequencia.listagem');
    Route::get('/frequencia/{gradeId}/dias', [FrequenciaAlunoController::class, 'listagemDias'])->name('frequencia.dias');
    Route::put('/frequencia/alterar-data', [FrequenciaAlunoController::class, 'alterarData'])->name('frequencia.alterarData');
    Route::get('/frequencia/visualizar/{id}', [FrequenciaAlunoController::class, 'visualizar'])->name('frequencia.visualizar');
    Route::get('/frequencia/{id}/edit', [FrequenciaAlunoController::class, 'edit'])->name('frequencia.edit');
    Route::put('/frequencia/{id}', [FrequenciaAlunoController::class, 'update'])->name('frequencia.update');
    Route::post('/frequencia', [FrequenciaAlunoController::class, 'store'])->name('frequencia.store');

    // CONTROLE //
    //USERS

    Route::get('/buscar-pessoas', [UsuariosController::class, 'buscarPessoas'])->name('usuarios.buscarPessoas');

    Route::get('/usuarios/{filial?}', [UsuariosController::class, 'indexFilial'])->name('usuarios.indexFilial');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/edit', [UsuariosController::class, 'editFilial'])->name('usuarios.editFilial');
    Route::put('/usuarios/{id}', [UsuariosController::class, 'updateFilial'])->name('usuarios.updateFilial');
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');

    Route::get('/usuarios-empresa', [UsuariosController::class, 'indexEmpresa'])->name('usuarios.indexEmpresa');
    Route::get('/usuarios-empresa/{id}/edit', [UsuariosController::class, 'editEmpresa'])->name('usuarios.editEmpresa');
    Route::put('/usuarios-empresa/{id}', [UsuariosController::class, 'updateEmpresa'])->name('usuarios.updateEmpresa');

    //EMPRESA
    Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa');
    Route::put('/empresa/{id}', [EmpresaController::class, 'update'])->name('empresa.update');
    Route::post('/empresa', [EmpresaController::class, 'store'])->name('empresa.store');
    Route::delete('/empresa/{id}', [EmpresaController::class, 'destroy'])->name('empresa.destroy');

    //FILIAIS
    Route::get('/filiais', [FilialController::class, 'index'])->name('filiais');
    Route::get('/filiais/{id}/edit', [FilialController::class, 'edit'])->name('filiais.edit');
    Route::put('/filiais/{id}/', [FilialController::class, 'update'])->name('filiais.update');
    Route::post('/filiais', [FilialController::class, 'store'])->name('filiais.store');
    Route::delete('/filiais/{id}', [FilialController::class, 'destroy'])->name('filiais.destroy');

    // DETALHES FILIAIS
    Route::get('/filiais/{id}/detalhes', [DetalhesFilialController::class, 'index'])->name('detalhes-filial.index');
    Route::get('/detalhes-filial/{id}/edit', [DetalhesFilialController::class, 'edit'])->name('detalhes-filial.edit');
    Route::post('/filiais/{id}/detalhes', [DetalhesFilialController::class, 'store'])->name('detalhes-filial.store');
    Route::put('/detalhes-filial/{id}', [DetalhesFilialController::class, 'update'])->name('detalhes-filial.update');
    Route::delete('/detalhes-filial/{id}', [DetalhesFilialController::class, 'destroy'])->name('detalhes-filial.destroy');

    // ADMIN geral
    // ADMINISTRAÇÃO
    Route::get('/graduacoes', [GraduacaoController::class, 'index'])->name('graduacoes');
    Route::post('/graduacoes', [GraduacaoController::class, 'store'])->name('graduacoes.store');
    Route::get('/graduacoes/{id}/edit', [GraduacaoController::class, 'edit'])->name('graduacoes.edit');
    Route::put('/graduacoes/{id}', [GraduacaoController::class, 'update'])->name('graduacoes.update');
    Route::delete('/graduacoes/{id}', [GraduacaoController::class, 'destroy'])->name('graduacoes.destroy');

    Route::get('/preco-aula', [PrecoModalidadeController::class, 'index'])->name('preco-aula');
    Route::post('/preco-aula', [PrecoModalidadeController::class, 'store'])->name('preco-aula.store');
    Route::get('/preco-aula/{id}/edit', [PrecoModalidadeController::class, 'edit'])->name('preco-aula.edit');
    Route::put('/preco-aula/{id}', [PrecoModalidadeController::class, 'update'])->name('preco-aula.update');
    Route::delete('/preco-aula/{id}', [PrecoModalidadeController::class, 'destroy'])->name('preco-aula.destroy');

    Route::get('/modalidades', [ModalidadeController::class, 'index'])->name('modalidades');
    Route::post('/modalidades', [ModalidadeController::class, 'store'])->name('modalidades.store');
    Route::get('/modalidades/{id}/edit', [ModalidadeController::class, 'edit'])->name('modalidades.edit');
    Route::put('/modalidades/{id}', [ModalidadeController::class, 'update'])->name('modalidades.update');
    Route::delete('/modalidades/{id}', [ModalidadeController::class, 'destroy'])->name('modalidades.destroy');

    Route::get('/horario-treino', [HorarioTreinoController::class, 'index'])->name('horario_treino');
    Route::post('/horario-treino', [HorarioTreinoController::class, 'store'])->name('horario_treino.store');
    Route::get('/horario-treino/{id}/edit', [HorarioTreinoController::class, 'edit'])->name('horario_treino.edit');
    Route::put('/horario-treino/{id}', [HorarioTreinoController::class, 'update'])->name('horario_treino.update');
    Route::delete('/horario-treino/{id}', [HorarioTreinoController::class, 'destroy'])->name('horario_treino.destroy');

    Route::get('/turmas', [TurmaController::class, 'index'])->name('turmas');
    Route::post('/turmas', [TurmaController::class, 'store'])->name('turmas.store');
    Route::get('/turmas/{id}/edit', [TurmaController::class, 'edit'])->name('turmas.edit');
    Route::put('/turmas/{id}', [TurmaController::class, 'update'])->name('turmas.update');
    Route::delete('/turmas/{id}', [TurmaController::class, 'destroy'])->name('turmas.destroy');
});

//
// PROFESSOR USER
//

Route::middleware(['auth', 'professor'])->group(function () {

    //DASHBOARD PROFESSOR
    Route::get('/professor/dashboard', [ProfessorUserDashboardController::class, 'index'])->name('dashboard-professor');

    //PROFESSOR
    Route::get('/professor/show', [ProfessorUserProfessorController::class, 'show'])->name('professor.show');

    // AGENDA
    Route::get('/professor/agenda', [ProfessorUserAgendaController::class, 'agenda'])->name('professor-agenda');
    Route::get('/professor/agenda/{id}', [ProfessorUserAgendaController::class, 'showAgenda'])->name('professor-agenda.show');

    //ALUNOS
    Route::get('/professor/aluno/hub/{id}', [ProfessorUserHubController::class, 'hub'])->name('professor-aluno.hub');
    Route::get('/professor/alunos', [ProfessorUserAlunoController::class, 'index'])->name('professor-aluno.index');
    Route::get('/professor/aluno/{id}', [ProfessorUserAlunoController::class, 'show'])->name('professor-aluno.show');
    Route::get('/professor/aluno/{id}/edit', [ProfessorUserAlunoController::class, 'editAluno'])->name('professor-aluno.edit');
    Route::put('/professor/aluno/{id}', [ProfessorUserAlunoController::class, 'updateAluno'])->name('professor-aluno.update');

    //DETALHES ALUNO
    Route::get('/professor/{id}/detalhes', [ProfessorUserDetalhesAlunoController::class, 'index'])->name('professor-detalhes-aluno.index');
    Route::get('/professor/aluno/{id}/graduacoes', [ProfessorUserDetalhesAlunoController::class, 'index'])->name('professor-detalhes-aluno.index');
    Route::post('/professor/aluno/{id}/graduacoes', [ProfessorUserDetalhesAlunoController::class, 'store'])->name('professor-detalhes-aluno.store');
    Route::get('/professor/graduacoes/{id}/edit', [ProfessorUserDetalhesAlunoController::class, 'edit'])->name('professor-detalhes-aluno.edit');
    Route::put('/professor/graduacoes/{id}', [ProfessorUserDetalhesAlunoController::class, 'update'])->name('professor-detalhes-aluno.update');
    Route::delete('/professor/graduacoes/{id}', [ProfessorUserDetalhesAlunoController::class, 'destroy'])->name('professor-detalhes-aluno.destroy');
    Route::get('/professor/certificado-aluno/{path}', [ProfessorUserDetalhesAlunoController::class, 'showCertificado'])->name('professor-detalhes-aluno.certificado');

    // RESPONSAVEIS DOS ALUNOS
    Route::get('/professor/responsavel/{id}', [ProfessorUserResponsavelController::class, 'show'])->name('professor-responsavel.show');
    Route::put('/professor/responsavel/{id}', [ProfessorUserResponsavelController::class, 'update'])->name('professor-responsavel.update');

    //MATRICULA
    Route::get('/professor/matricula/{id}', [ProfessorUserMatriculaController::class, 'index'])->name('professor-matricula');
    Route::get('/professor/matricula/show/{id}', [ProfessorUserMatriculaController::class, 'show'])->name('professor-matricula.show');

    //FINANCEIRO
    Route::get('/professor/aluno/{id}/financeiro', [ProfessorUserFinanceiroController::class, 'index'])->name('professor-financeiro');

    // FREQUENCIA
    Route::get('/professor/frequencia', [ProfessorUserFrequenciaController::class, 'listagemGrades'])->name('professor-frequencia');
    Route::get('/professor/frequencia/{id}/dias', [ProfessorUserFrequenciaController::class, 'listagemDias'])->name('professor-frequencia.dias');
    Route::post('/professor/frequencia/store', [ProfessorUserFrequenciaController::class, 'store'])->name('professor-frequencia.store');
    Route::put('/professor/frequencia/alterar-data', [ProfessorUserFrequenciaController::class, 'alterarData'])->name('professor-frequencia.alterarData');
    Route::get('/professor/frequencia/{id}/edit', [ProfessorUserFrequenciaController::class, 'edit'])->name('professor-frequencia.edit');
    Route::put('/professor/frequencia/{id}', [ProfessorUserFrequenciaController::class, 'update'])->name('professor-frequencia.update');
    Route::get('/professor-frequencia/relatorio/{id}', [ProfessorUserFrequenciaController::class, 'relatorio'])->name('professor-frequencia.relatorio');
});


Route::middleware(['auth', 'aluno'])->group(function () {

    //DASHBOARD ALUNO
    Route::get('/aluno/dashboard', [AlunoUserDashboardController::class, 'index'])->name('dashboard-aluno');

    // ALUNO
    Route::get('/aluno/alunos', [AlunoUserAlunoController::class, 'index'])->name('aluno.index');
    Route::get('/aluno/alunos/{id}', [AlunoUserAlunoController::class, 'show'])->name('aluno.show');

    // DETALHES
    Route::get('/aluno/detalhes-aluno/{id}', [AlunoUserDetalhesAlunoController::class, 'index'])->name('aluno-detalhes.index');
    Route::get('/aluno/detalhes-aluno/certificado/{path}', [AlunoUserDetalhesAlunoController::class, 'showCertificado'])->name('aluno-detalhes.showCertificado');

    // RESPONSAVEL
    Route::get('/aluno/responsavel', [AlunoUserResponsavelController::class, 'index'])->name('responsavel.index');

    // MATRÍCULA
    Route::get('/aluno/matriculas/{id}', [AlunoUserMatriculaController::class, 'index'])->name('aluno-matricula.index');
    Route::get('/aluno/matricula/show/{id}', [AlunoUserMatriculaController::class, 'show'])->name('aluno-matricula.show');

    //FINANCEIRO
    Route::get('/aluno/financeiro/{id}', [AlunoUserFinanceiroController::class, 'index'])->name('aluno-financeiro.index');

    //FREQUENCIA
    Route::get('/aluno/frequencia/{id}',[AlunoUserFrequenciaController::class, 'visualizar'])->name('aluno-frequencia.visualizar');
});

require __DIR__ . '/auth.php';
