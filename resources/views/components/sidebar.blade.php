<style>
    :root {
        --azul-principal: #0F4C81;
        --azul-secundario: #1F6AA5;
        --azul-hover: #0C3C66;
        --azul-claro: #E7F0FA;
        --cinza-fundo: #F0F5FB;
        --cinza-borda: #E2E8F0;

        --vermelho-principal: #8E251F;
        --vermelho-hover: #73201A;
        --vermelho-transparente: rgba(142, 37, 31, 0.2);
    }

    /* MENU ICON */
    .menu-icon {
        width: 24px;
        height: 18px;
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .menu-icon span {
        position: absolute;
        height: 3px;
        width: 100%;
        background: white;
        left: 0;
        transition: all 0.3s ease;
    }

    .menu-icon span:nth-child(1) {
        top: 0;
    }

    .menu-icon span:nth-child(2) {
        top: 7.5px;
    }

    .menu-icon span:nth-child(3) {
        top: 15px;
    }

    .menu-icon.open span:nth-child(1) {
        transform: rotate(45deg);
        top: 7.5px;
    }

    .menu-icon.open span:nth-child(2) {
        opacity: 0;
    }

    .menu-icon.open span:nth-child(3) {
        transform: rotate(-45deg);
        top: 7.5px;
    }

    /* SIDEBAR */
    #sidebar {
        min-width: 0;
    }

    .sidebar {
        width: 256px;
        background: linear-gradient(180deg, var(--azul-principal), var(--azul-hover));
        color: #ffffff;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.08);
        transition: width 0.3s ease;
        overflow: hidden;
        white-space: nowrap;
    }

    /* SIDEBAR FECHADA */
    html.sidebar-closed #sidebar {
        width: 0;
        padding: 0;
    }

    /* LINKS */
    .sidebar a,
    .sidebar button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 12px 20px;
        margin: 4px 8px;
        border-radius: 6px;
        color: #e0ecf7;
        text-decoration: none;
        font-size: 15px;
        transition: all 0.25s ease;
    }

    /* HOVER */
    .sidebar a:hover,
    .sidebar button:hover {
        background-color: var(--vermelho-transparente);
        color: #ffffff;
        padding-left: 24px;
    }

    /* ATIVO */
    .sidebar a.active,
    .sidebar button.active {
        background-color: var(--vermelho-principal);
        color: #ffffff;
        font-weight: 600;
    }

    /* SUBMENU */
    .submenu a {
        display: block;
        padding: 10px 20px;
        margin-left: 48px;
        border-radius: 6px;
        transition: all 0.25s ease;
    }

    .submenu a:hover {
        background-color: rgba(142, 37, 31, 0.3);
    }

    .submenu a.active {
        background-color: var(--vermelho-principal);
        font-weight: 600;
    }

    /* TÍTULO */
    .sidebar .sidebar-title {
        padding: 20px;
        font-size: 18px;
        font-weight: bold;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* DIVISOR */
    .sidebar hr {
        border: none;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin: 10px 0;
    }

    .link-active {
        background-color: var(--vermelho-principal);
        color: #fff;
    }

    .link-hover:hover {
        background-color: var(--vermelho-hover);
    }
</style>
<script>
    (function() {
        const isClosed = localStorage.getItem('sidebarClosed');
        if (isClosed === 'true' || isClosed === null) {
            document.documentElement.classList.add('sidebar-closed');
        }
    })();
</script>

<aside id="sidebar" class="sidebar">


    <!-- TOPO -->
    <div class="p-6 flex items-center justify-between">
        <span class="text-3xl font-bold text-white">
            OSS <span style="color: #bf0e6a;">AMIGOS</span>
        </span>
    </div>

    @php
    $user = auth()->user();

    $menu = $user->role === 'admin'
    ? [
    [
    'type' => 'link',
    'label' => 'Dashboard',
    'route' => 'dashboard',
    'active' => ['dashboard', 'dashboard.admin', 'dashboard.mensalidadesAtrasadas', 'dashboard.graduacoes'],
    ],
    [
    'type' => 'link',
    'label' => 'Matrícula',
    'route' => 'responsaveis',
    'active' => ['responsaveis', 'responsaveis.edit', 'alunos', 'alunos.edit', 'detalhes-aluno.index', 'detalhes-aluno.edit', 'mensalidade', 'matricula', 'matricula.show', 'matricula.edit', 'matriculas'],
    ],
    [
    'type' => 'link',
    'label' => 'Alunos',
    'route' => 'matricula.index',
    'active' => ['alunos.show', 'matricula.index'],
    ],
    [
    'type' => 'link',
    'label' => 'Agenda Mensal',
    'route' => 'grade_horarios.visualizar',
    'active' => ['grade_horarios.visualizar'],
    ],
    [
    'type' => 'link',
    'label' => 'Professores / Alunos',
    'route' => 'professores.alunos',
    'active' => ['professores.alunos'],
    ],
    [
    'type' => 'link',
    'label' => 'Frequência dos Alunos',
    'route' => 'frequencia.listagem',
    'active' => ['frequencia.dias', 'frequencias.edit', 'frequencia.listagem', 'frequencia.visualizar'],
    ],
    [
    'type' => 'submenu',
    'label' => 'Administração',
    'items' => [
    [
    'label' => 'Dashboard Admin',
    'route' => 'dashboard.admin',
    'active' => ['dashboard.admin', 'dashboard.mensalidadesAtrasadas', 'dashboard.graduacoes'],
    ],
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
    'active' => ['usuarios.empresa', 'usuarios.edit','usuarios.index'],
    ],
    ],
    ],
    ]
    : [
    [
    'type' => 'link',
    'label' => 'Painel',
    'route' => 'painel',
    'active' => ['painel'],
    ],
    [
    'type' => 'link',
    'label' => 'Matrícula',
    'route' => 'responsaveis',
    'active' => ['responsaveis', 'responsaveis.edit', 'alunos', 'alunos.edit', 'detalhes-aluno.index', 'detalhes-aluno.edit', 'mensalidade', 'matricula', 'matricula.show', 'matricula.edit', 'matriculas'],
    ],
    [
    'type' => 'link',
    'label' => 'Alunos',
    'route' => 'matricula.index',
    'active' => ['alunos.show', 'matricula.index'],
    ],
    [
    'type' => 'link',
    'label' => 'Frequência do Aluno',
    'route' => 'grade_horarios',
    'active' => ['grade_horarios', 'grade_horarios.index', 'grade_horarios.edit'],
    ],
    ];

    $isLinkActive = function (array $activeRoutes) {
    return in_array(Route::currentRouteName(), $activeRoutes);
    };

    $submenuIsActive = function (array $items) use ($isLinkActive) {
    foreach ($items as $item) {
    if ($isLinkActive($item['active'])) {
    return true;
    }
    }
    return false;
    };
    @endphp

    <nav id="menu" class="px-4 space-y-1 overflow-hidden transition-opacity duration-300 ease-in-out">
        @foreach ($menu as $item)
        @if ($item['type'] === 'submenu')
        @php
        $submenuId = Str::slug($item['label']);
        $open = $submenuIsActive($item['items']);
        @endphp

        <button type="button"
            onclick="toggleSubMenu('{{ $submenuId }}')"
            class="w-full flex justify-between items-center gap-3 px-4 py-2 rounded-lg transition
                        {{ $open ? 'bg-[#8E251F]/40 text-white' : 'hover:bg-[#8E251F]/20 hover:text-white' }}">
            <span>{{ $item['label'] }}</span>
            <span class="arrow-icon text-white inline-block w-4 text-center text-[16px] leading-none">
                <span class="{{ $open ? 'hidden' : '' }}">▸</span>
                <span class="{{ $open ? '' : 'hidden' }}">▾</span>
            </span>
        </button>

        <div id="submenu-{{ $submenuId }}"
            class="mt-1 space-y-1 {{ $open ? '' : 'hidden' }}">
            @foreach ($item['items'] as $subItem)
            @php $subActive = $isLinkActive($subItem['active']); @endphp

            <a href="{{ route($subItem['route']) }}"
                class="block pl-12 py-2 rounded-lg transition
                                {{ $subActive ? 'bg-[#8E251F] text-white font-semibold' : 'hover:bg-[#732920]/50 hover:text-white' }}"
                style="margin-left: 48px;">
                {{ $subItem['label'] }}
            </a>
            @endforeach
        </div>
        @else
        @php $active = $isLinkActive($item['active']); @endphp

        <a href="{{ route($item['route']) }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                        {{ $active ? 'bg-[#8E251F] text-white' : 'hover:bg-[#8E251F]/20 hover:text-white' }}">
            {{ $item['label'] }}
        </a>
        @endif
        @endforeach
    </nav>
</aside>

<script>
    function toggleSidebar() {
        document.documentElement.classList.toggle('sidebar-closed');
    }

    function toggleSubMenu(id) {
        const el = document.getElementById('submenu-' + id);
        if (el) el.classList.toggle('hidden');
    }
</script>