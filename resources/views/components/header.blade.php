<!-- HEADER -->
<header class="h-16 bg-[#3A403E] border-b flex items-center justify-between px-8">
    <!-- Botão Hamburger + Avatar + Logout -->
    <div class="flex items-center gap-4">
        <!-- Botão Hamburger -->
        <div onclick="toggleSidebar()" class="menu-icon sidebar-closed:!open" id="menuIcon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Saudação -->
    <div class="flex items-center gap-4">
        @auth
        @if(auth()->user()->role === 'admin')
        <h1 class="p-6 text-xl font-bold text-white">
            Olá, {{ auth()->user()->name }} (Admin)
        </h1>
        @else
        <h1 class="p-6 text-xl font-bold text-white">
            Olá, {{ auth()->user()->name }}
        </h1>
        @endif
        @endauth
    </div>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="px-4 py-2 bg-[#8E251F] hover:bg-[#732920] text-white rounded-lg transition">
            Logout
        </button>
    </form>
</header>