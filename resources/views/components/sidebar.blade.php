<style>
    /* Botão Hamburger */
    .menu-icon {
        width: 24px;
        height: 18px;
        position: relative;
        display: inline-block;
        cursor: pointer;
        transition: transform 0.3s ease;
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

    /* Sidebar fechado */
    aside.closed {
        width: 0;
        overflow: hidden;
        transition: width 0.3s ease;
    }


    /* Menu */
    nav {
        transition: opacity 0.3s ease;
    }
</style>

<aside id="sidebar" class="closed w-64 bg-[#3A403E] text-[#9A9F9A] border-r shadow-sm transition-all">
    <!-- Topo -->
    <div class="p-6 flex items-center justify-between">
        <span class="text-xl font-bold text-white">DudaJJ</span>

    </div>

    <!-- Menu -->
    <nav id="menu" class="px-4 space-y-1 overflow-hidden transition-opacity duration-300 ease-in-out">
        @php
        $menu = [
        'Dashboard' => 'dashboard',
        'Alunos/Responsáveis' => 'alunos', // Sempre leva para alunos
        'Professores' => 'professores',
        'Administração' => [
        'Graduações' => 'graduacoes',
        'Modalidades' => 'modalidades',
        ],
        ];
        @endphp

        @foreach ($menu as $label => $route)

        @if(is_array($route))
        {{-- MENU COM SUBMENU --}}
        @php
        $isSubActive = false;
        foreach ($route as $subRoute) {
        if (request()->routeIs($subRoute) || request()->routeIs($subRoute.'.edit')) {
        $isSubActive = true;
        break;
        }
        }
        @endphp

        <button onclick="toggleSubMenu('{{ Str::slug($label) }}')"
            class="w-full flex justify-between items-center gap-3 px-4 py-2 rounded-lg transition
                    {{ $isSubActive ? 'bg-[#8E251F]/40 text-white' : 'hover:bg-[#8E251F]/20 hover:text-white' }}">
            <span>{{ $label }}</span>
            <span class="arrow-icon text-white inline-block w-4 text-center text-[16px] leading-none">
                <span class="{{ $isSubActive ? 'hidden' : '' }}">▸</span>
                <span class="{{ $isSubActive ? '' : 'hidden' }}">▾</span>
            </span>
        </button>

        <div id="submenu-{{ Str::slug($label) }}"
            class="mt-1 space-y-1 {{ $isSubActive ? '' : 'hidden' }}">
            @foreach($route as $subLabel => $subRoute)
            @php
            $isSubmenuActive = request()->routeIs($subRoute) || request()->routeIs($subRoute.'.edit');
            @endphp
            <a href="{{ route($subRoute) }}"
                class="block pl-12 py-2 rounded-lg transition
                            {{ $isSubmenuActive
                                ? 'bg-[#8E251F] text-white font-semibold'
                                : 'hover:bg-[#732920]/50 hover:text-white' }}"
                style="margin-left: 48px;">
                {{ $subLabel }}
            </a>
            @endforeach
        </div>

        @else
        {{-- ITEM NORMAL --}}
        @php
        // Ativa Alunos/Responsáveis se estiver em qualquer rota de alunos ou responsáveis
        if ($label === 'Alunos/Responsáveis') {
        $isActive = in_array(Route::currentRouteName(), [
        'alunos', 'alunos.edit',
        'responsaveis.index', 'responsaveis.edit'
        ]);
        } elseif ($label === 'Professores') {
        $isActive = in_array(Route::currentRouteName(), ['professores', 'professores.edit']);
        } else {
        $isActive = request()->routeIs($route);
        }
        @endphp

        <a href="{{ route($route) }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                    {{ $isActive ? 'bg-[#8E251F] text-white' : 'hover:bg-[#8E251F]/20 hover:text-white' }}">
            {{ $label }}
        </a>
        @endif

        @endforeach
    </nav>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('menuIcon');

        const isClosed = localStorage.getItem('sidebarClosed');

        // Se NÃO existir ainda, assume fechado
        if (isClosed === null || isClosed === 'true') {
            sidebar.classList.add('closed');
            icon.classList.remove('open');
            localStorage.setItem('sidebarClosed', 'true');
        } else {
            sidebar.classList.remove('closed');
            icon.classList.add('open');
        }
    });


    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('menuIcon');

        const isNowClosed = sidebar.classList.toggle('closed');
        icon.classList.toggle('open', !isNowClosed);

        localStorage.setItem('sidebarClosed', isNowClosed);
    }

    function toggleSubMenu(id) {
        document.querySelectorAll('[id^="submenu-"]').forEach(el => {
            if (el.id !== 'submenu-' + id) {
                el.classList.add('hidden');
                const icon = el.previousElementSibling.querySelector('.arrow-icon');
                if (icon) {
                    icon.children[0].classList.remove('hidden');
                    icon.children[1].classList.add('hidden');
                }
            }
        });

        const submenu = document.getElementById('submenu-' + id);
        submenu.classList.toggle('hidden');

        const icon = submenu.previousElementSibling.querySelector('.arrow-icon');
        if (icon) {
            icon.children[0].classList.toggle('hidden');
            icon.children[1].classList.toggle('hidden');
        }
    }
</script>