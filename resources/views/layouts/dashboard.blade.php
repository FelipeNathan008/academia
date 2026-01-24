<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

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
