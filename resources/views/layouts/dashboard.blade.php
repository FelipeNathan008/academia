<style>
    :root {
        --azul-principal: #0F4C81;
        --azul-secundario: #1F6AA5;
        --azul-hover: #0C3C66;
        --azul-claro: #E7F0FA;
        --cinza-fundo: #F0F5FB;
        --cinza-borda: #E2E8F0;
    }

    /* FUNDO */
    .body-bg {
        background-color: var(--cinza-fundo);
    }
</style>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>

<body class="body-bg">
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Conteúdo --}}
        <div class="flex-1 flex flex-col">

            {{-- Header --}}
            @include('components.header')

            {{-- Página --}}
            <main class="p-8">
                @yield('content')
            </main>

        </div>

    </div>

</body>

</html>