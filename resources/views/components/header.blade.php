<style>
    :root {
        --azul-principal: #0F4C81;
        --azul-secundario: #1F6AA5;
        --azul-hover: #0C3C66;
        --azul-claro: #E7F0FA;
        --cinza-fundo: #F0F5FB;
        --cinza-borda: #E2E8F0;
    }

    .header {
        height: 64px;
        background: linear-gradient(90deg, var(--azul-principal), var(--azul-secundario));
        border-bottom: 1px solid var(--cinza-borda);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    /* sobrescreve o azul quando for professor */
    .bg-professor {
        background: #000000 !important;
    }

    /* sobrescreve o azul quando for aluno */
    .bg-aluno {
        background: #FFA500 !important;
    }

    .header-left,
    .header-center {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .header-title {
        font-size: 18px;
        font-weight: 600;
        color: #ffffff;
    }

    .btn-logout {
        padding: 8px 16px;
        background-color: #8E251F;
        color: #ffffff;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-logout:hover {
        background-color: #73201A;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .menu-icon span {
        display: block;
        width: 22px;
        height: 2px;
        background-color: white;
        margin: 4px 0;
    }
</style>

<header class="header 
    {{ auth()->user()->role === 'professor' ? 'bg-professor' : (auth()->user()->role === 'aluno' ? 'bg-aluno' : '') }}">
    
    <div class="header-left">
        <div onclick="toggleSidebar()" class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="header-center">
        @auth
        <h1 class="header-title">
            Olá, {{ auth()->user()->name }}
            ({{ auth()->user()->role === 'admin' ? 'Admin' : auth()->user()->role }})
        </h1>
        @endauth
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            Logout
        </button>
    </form>
</header>