@extends('layouts.dashboard')

@section('title', 'SuperAdmin')

@section('content')

<h2 class="text-3xl font-bold mb-8">
    Dashboard SuperAdmin
</h2>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-gray-500 text-sm">
            Empresas
        </p>

        <h2 class="text-3xl font-bold">
            {{ $totalEmpresas }}
        </h2>
    </div>


</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-white rounded-xl shadow p-6">

        <p class="text-gray-500 text-sm">
            Administradores
        </p>

        <h2 class="text-3xl font-bold">
            {{ $admins }}
        </h2>

    </div>

</div>



@endsection