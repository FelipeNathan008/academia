<header class="h-16 bg-[#3A403E] border-b flex items-center justify-between px-8">
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

    <!-- Avatar + Logout -->
    @auth
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-[#9A9F9A]"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="px-4 py-2 bg-[#8E251F] hover:bg-[#732920] text-white rounded-lg transition">
                Logout
            </button>
        </form>
    </div>
    @endauth
</header>
