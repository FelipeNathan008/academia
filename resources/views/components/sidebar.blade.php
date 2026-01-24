<aside class="w-64 bg-[#3A403E] text-[#9A9F9A] border-r shadow-sm">
    <!-- Topo -->
    <div class="p-6 flex items-center justify-between">
        <span class="text-xl font-bold text-white">DudaJJ</span>

        <button onclick="toggleMenu()" class="text-white text-2xl focus:outline-none">
            ☰
        </button>
    </div>

    <!-- Menu -->
    <nav id="menu"
        class="px-4 space-y-1 overflow-hidden transition-all duration-500 ease-in-out max-h-[500px]">

        @php
        $menu = [
        'Dashboard' => 'dashboard',
        'Alunos' => 'alunos',
        'Professores' => 'professores',
        'Administração' => [
        'Graduações' => 'graduacoes',
        // outros submenus aqui
        ],
        ];
        @endphp

        @foreach ($menu as $label => $route)
        @php
        $isActive = false;
        @endphp

        @if(is_array($route))
        {{-- Item com submenu --}}
        @php
        $isSubActive = false;
        foreach ($route as $subLabel => $subRoute) {
        if(request()->routeIs($subRoute) || request()->routeIs($subRoute.'.edit')) {
        $isSubActive = true;
        break;
        }
        }
        @endphp

        <button onclick="toggleSubMenu('{{ Str::slug($label) }}')"
            class="w-full flex justify-between items-center gap-3 px-4 py-2 rounded-lg transition
                {{ $isSubActive ? 'bg-[#8E251F] text-white' : 'hover:bg-[#8E251F]/20 hover:text-white' }}">
            {{ $label }}
            <span class="text-white">▸</span>
        </button>


        {{-- Submenu --}}
        <div id="submenu-{{ Str::slug($label) }}" class="mt-1 space-y-1 {{ $isSubActive ? '' : 'hidden' }}">
            @foreach($route as $subLabel => $subRoute)
            @php
            // Verifica se este submenu é o ativo
            $isSubmenuActive = request()->routeIs($subRoute) || request()->routeIs($subRoute.'.edit');
            @endphp

            <a href="{{ route($subRoute) }}"
                class="block pl-12 py-2 rounded-lg transition
                  {{ $isSubmenuActive ? 'bg-[#732920] text-white' : 'hover:bg-[#732920]/50 hover:text-white' }}"
                style="margin-left: 48px;">
                {{ $subLabel }}
            </a>
            @endforeach
        </div>



        @else
        {{-- Item normal --}}
        @php
        if ($label === 'Alunos') {
        $isActive = in_array(Route::currentRouteName(), ['alunos', 'alunos.edit']);
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
    let menuOpen = true;

    function toggleMenu() {
        const menu = document.getElementById('menu');
        menu.style.maxHeight = menuOpen ? '0px' : '500px';
        menuOpen = !menuOpen;
    }

    function toggleSubMenu(id) {
        const submenu = document.getElementById('submenu-' + id);
        submenu.classList.toggle('hidden');
    }
</script>