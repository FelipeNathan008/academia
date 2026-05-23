@php
$user = auth()->user();

if ($user->role === 'admin') {

$menu = [
[
'type' => 'link',
'label' => 'Dashboard',
'route' => 'dashboard',
'active' => ['dashboard', 'dashboard.mensalidadesAtrasadas', 'dashboard.graduacoes'],
],
[
'type' => 'link',
'label' => 'Matrícula',
'route' => 'responsaveis',
'active' => ['responsaveis', 'responsaveis.edit', 'alunos', 'alunos.edit',
'detalhes-aluno.index', 'detalhes-aluno.edit', 'mensalidade',
'matricula', 'matricula.show', 'matricula.edit', 'matriculas'],
],
[
'type' => 'link',
'label' => 'Alunos',
'route' => 'matricula.index',
'active' => ['alunos.show', 'matricula.index'],
],
[
'type' => 'link',
'label' => 'Professores',
'route' => 'professores.alunos',
'active' => ['professores.alunos'],
],
[
'type' => 'link',
'label' => 'Frequência',
'route' => 'frequencia.listagem',
'active' => ['frequencia.dias', 'frequencias.edit', 'frequencia.listagem', 'frequencia.visualizar'],
],
[
'type' => 'link',
'label' => 'Aulas',
'route' => 'grades.aulas',
'active' => ['aulas', 'aulas.store', 'aulas.edit','grades.aulas'],
],
[
'type' => 'submenu',
'label' => 'Administração',
'items' => [
[
'label' => 'Professores',
'route' => 'professores',
'active' => ['professores', 'professores.edit', 'detalhes-professor.index', 'detalhes-professor.edit', 'professores.show'],
],
[
'label' => 'Grade de Horários',
'route' => 'grade_horarios',
'active' => ['grade_horarios', 'grade_horarios.index', 'grade_horarios.edit'],
],
[
'label' => 'Graduações',
'route' => 'graduacoes',
'active' => ['graduacoes', 'graduacoes.edit'],
],
[
'label' => 'Modalidades',
'route' => 'modalidades',
'active' => ['modalidades', 'modalidades.edit'],
],
[
'label' => 'Horarios de Treino',
'route' => 'horario_treino',
'active' => ['horario_treino', 'horario_treino.edit'],
],
[
'label' => 'Preço das Aulas',
'route' => 'preco-aula',
'active' => ['preco-aula', 'preco-aula.edit'],
],
[
'label' => 'Turmas',
'route' => 'turmas',
'active' => ['turmas', 'turmas.edit'],
],
],
],
[
'type' => 'submenu',
'label' => 'Controle',
'items' => [
[
'label' => 'Filiais',
'route' => 'filiais',
'active' => ['filiais', 'filiais.edit'],
],
[
'label' => 'Usuários',
'route' => 'usuarios.empresa',
'active' => ['usuarios.empresa', 'usuarios.edit', 'usuarios.index'],
],
[
'label' => 'Empresa',
'route' => 'empresa',
'active' => ['empresa'],
],
],
],
];

} elseif ($user->role === 'professor') {

$menu = [
[
'type' => 'link',
'label' => 'Dashboard',
'route' => 'dashboard-professor',
'active' => ['dashboard-professor'],
],
[
'type' => 'link',
'label' => 'Professor',
'route' => 'professor-index',
'active' => ['professor-index', 'professor.show'],
],
[
'type' => 'link',
'label' => 'Grade de Horários',
'route' => 'professor-agenda',
'active' => ['professor-agenda', 'professor-agenda.show'],
],
[
'type' => 'link',
'label' => 'Alunos',
'route' => 'professor-aluno.index',
'active' => ['professor-alunos.show', 'professor-aluno.index', 'professor-aluno.show',
'professor-financeiro', 'professor-matricula.show', 'professor-matricula',
'professor-responsavel.show', 'professor-aluno.edit', 'professor-aluno.hub',
'professor-detalhes-aluno.index', 'professor-detalhes-aluno.edit',
'professor-detalhes-aluno.certificado'],
],
[
'type' => 'link',
'label' => 'Frequência do Aluno',
'route' => 'professor-frequencia',
'active' => ['professor-frequencia', 'professor-frequencia.edit', 'professor-frequencia.dias'],
],
];

} else {

$menu = [
[
'type' => 'link',
'label' => 'Dashboard',
'route' => 'dashboard-aluno',
'active' => ['dashboard-aluno'],
],
[
'type' => 'link',
'label' => 'Meus Dados',
'route' => 'responsavel.index',
'active' => ['responsavel.index'],
],
[
'type' => 'link',
'label' => 'Alunos',
'route' => 'aluno.index',
'active' => ['aluno.index', 'aluno.show', 'aluno-matricula.index', 'aluno-matricula.show'],
],
];
}
@endphp

<aside class="sidebar">
    <div class="sidebar-logo">
        SISTEMA
    </div>

    <nav class="sidebar-menu">
        @foreach ($menu as $item)

        @if ($item['type'] === 'link')
        <a href="{{ route($item['route']) }}"
            class="sidebar-link {{ request()->routeIs($item['active']) ? 'active' : '' }}">
            {{ $item['label'] }}
        </a>
        @endif

        @if ($item['type'] === 'submenu')
        @php
        $submenuOpen = false;

        foreach ($item['items'] as $subitem) {
        if (request()->routeIs($subitem['active'])) {
        $submenuOpen = true;
        break;
        }
        }
        @endphp

        <button type="button"
            class="sidebar-submenu-btn {{ $submenuOpen ? 'open' : '' }}"
            onclick="toggleSubmenu(this)">
            <span>{{ $item['label'] }}</span>
            <span class="sidebar-arrow">▾</span>
        </button>

        <div class="sidebar-submenu {{ $submenuOpen ? 'show' : '' }}">
            @foreach ($item['items'] as $subitem)
            <a href="{{ route($subitem['route']) }}"
                class="sidebar-link sidebar-subitem {{ request()->routeIs($subitem['active']) ? 'active' : '' }}">
                {{ $subitem['label'] }}
            </a>
            @endforeach
        </div>
        @endif

        @endforeach
    </nav>
</aside>

<script>
    function toggleSubmenu(button) {
        const submenu = button.nextElementSibling;
        button.classList.toggle('open');
        submenu.classList.toggle('show');
    }
</script>