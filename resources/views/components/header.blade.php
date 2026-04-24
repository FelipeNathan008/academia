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

    /* ÁREAS */
    .header-left,
    .header-center,
    .header-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    /* TÍTULO */
    .header-title {
        padding: 12px 0;
        font-size: 18px;
        font-weight: 600;
        color: #ffffff;
    }

    /* BOTÃO LOGOUT */
    /* BOTÃO LOGOUT */
    .btn-logout {
        padding: 8px 16px;
        background-color: #8E251F;
        color: #ffffff;
        /* texto branco para contraste correto */
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* HOVER */
    .btn-logout:hover {
        background-color: #73201A;
        /* vermelho mais escuro baseado no original */
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    /* MENU ICON (opcional melhoria) */
    .menu-icon span {
        display: block;
        width: 22px;
        height: 2px;
        background-color: white;
        margin: 4px 0;
        transition: 0.3s;
    }
</style>
<header class="header">
    <div class="header-left">
        <div onclick="toggleSidebar()" class="menu-icon" id="menuIcon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="header-center">
        @auth
        @if(auth()->user()->role === 'admin')
        <h1 class="header-title">
            Olá, {{ auth()->user()->name }} (Admin)
        </h1>
        @else
        <h1 class="header-title">
            Olá, {{ auth()->user()->name }} (User)
        </h1>
        @endif
        @endauth
    </div>

    <div class="header-right">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                Logout
            </button>
        </form>
    </div>
</header>