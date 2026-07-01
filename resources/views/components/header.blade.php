<style>
    :root {
        --azul-principal: #0F4C81;
        --azul-secundario: #1F6AA5;
        --azul-hover: #0C3C66;
        --azul-claro: #E7F0FA;
        --cinza-borda: #D9E2EC;
        --texto-claro: #FFFFFF;
        --texto-escuro: #1F2937;
        --vermelho: #EF4444;
    }

    .header {
        height: 80px;
        background: white;
        border-bottom: 1px solid var(--cinza-borda);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 35px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .header-title {
        font-size: 28px;
        font-weight: bold;
        color: var(--texto-escuro);
        margin-right: 20px;
    }

    .header-menu {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-link {
        text-decoration: none;
        background: var(--azul-principal);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .header-link.active {
        background: var(--azul-hover);
        box-shadow: 0 0 0 2px rgba(15, 76, 129, 0.2);
    }

    .header-link:hover {
        background: var(--azul-hover);
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .header-user {
        text-align: right;
    }

    .header-user strong {
        display: block;
        color: var(--texto-escuro);
        font-size: 15px;
    }

    .header-user span {
        color: #6B7280;
        font-size: 13px;
        text-transform: capitalize;
    }

    .avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--azul-principal);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
    }

    .btn-logout {
        border: none;
        background: var(--vermelho);
        color: white;
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-logout:hover {
        background: #DC2626;
    }
</style>

<header class="header">

    <div class="header-left">



        {{-- MENU ADMIN --}}
        @if(auth()->user()->role === 'admin')

        <div class="header-menu">

            <a href="{{ route('admin.principal') }}"
                class="header-link {{ request()->routeIs(
                        'admin.principal',
                        'dashboard.*', 'dashboard',
                        'responsaveis.*', 'responsaveis',
                        'alunos.*', 'alunos', 'detalhes-aluno.*',
                        'matricula.*', 'matricula', 'finaceiro.*', 'mensalidade',
                        'professores.*', 'professores', 'detalhes-professor.*',
                        'frequencia.*',
                        
                ) ? 'active' : '' }}">
                Principal
            </a>

            <a href="{{ route('admin.administracao') }}"
                class="header-link {{ request()->routeIs(
                        'admin.administracao',
                        'grades.*', 'aulas', 'grade_horarios.*','grade_horarios',
                        'graduacoes', 'graduacoes.*',
                        'modalidades', 'modalidades.*',
                        'horario_treino', 'horario_treino.*',
                        'preco-aula', 'preco-aula.*',
                        'turmas', 'turmas.*',
                ) ? 'active' : '' }}">
                Administração
            </a>

            <a href="{{ route('admin.controle') }}"
                class="header-link {{ request()->routeIs(
                        'admin.controle',
                        'filiais.*', 'filiais', 'detalhes-filial.*', 'detalhes-filial',
                        'usuarios.*', 'usuarios',
                        'empresa.*', 'empresa',
                        
                ) ? 'active' : '' }}">
                Controle
            </a>

            <a href="{{ route('dashboard.graduacoes') }}"
                class="header-link {{ request()->routeIs(
                        'dashboard.graduacoes'
                        
                ) ? 'active' : '' }}">
                Graduação
            </a>


        </div>

        @endif

        @if(auth()->user()->role === 'professor')

        <div class="header-menu">
            <a href="{{ route('dashboard-professor') }}"
                class="header-link {{ request()->routeIs(
                        'dashboard-professor',
                ) ? 'active' : '' }}">
                Dashboard
            </a>
        </div>

        <div class="header-menu">
            <a href="{{ route('professor.show') }}"
                class="header-link {{ request()->routeIs(
                        'professor.show',
                ) ? 'active' : '' }}">
                Dados
            </a>
        </div>


        <div class="header-menu">
            <a href="{{ route('professor-aluno.index') }}"
                class="header-link {{ request()->routeIs(
                        'professor-aluno.*', 'professor-detalhes-aluno.*', 'professor-responsavel.*',
                        'professor-matricula.*', 'professor-financeiro', 'professor-matricula',
                ) ? 'active' : '' }}">
                Alunos
            </a>
        </div>

        <div class="header-menu">
            <a href="{{ route('professor-frequencia') }}"
                class="header-link {{ request()->routeIs(
                        'professor-frequencia', 'professor-frequencia.*',
                ) ? 'active' : '' }}">
                Frequência
            </a>
        </div>

        <div class="header-menu">
            <a href="{{ route('professor-agenda') }}"
                class="header-link {{ request()->routeIs(
                        'professor-agenda',
                ) ? 'active' : '' }}">
                Horários
            </a>
        </div>
        @endif

        @if(auth()->user()->role === 'aluno')

        <div class="header-menu">
            <a href="{{ route('dashboard-aluno') }}"
                class="header-link {{ request()->routeIs(
                        'dashboard-aluno',
                ) ? 'active' : '' }}">
                Dashboard
            </a>
        </div>

        <div class="header-menu">
            <a href="{{ route('responsavel.index') }}"
                class="header-link {{ request()->routeIs(
                        'responsavel.index', 
                ) ? 'active' : '' }}">
                Meus Dados
            </a>
        </div>

        <div class="header-menu">
            <a href="{{ route('aluno.index') }}"
                class="header-link {{ request()->routeIs(
                        'aluno.*', 'aluno-matricula.*', 'aluno-financeiro.*'
                ) ? 'active' : '' }}">
                Alunos
            </a>
        </div>





        @endif

    </div>

    <div class="header-right">

        <div class="header-user">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->role }}</span>
        </div>

        <div class="avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="btn-logout">
                Sair
            </button>
        </form>

    </div>

</header>