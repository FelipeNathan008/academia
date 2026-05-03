@extends('layouts.dashboard')

@section('title', 'Minha Agenda')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Minha Grade de Horários
    </h2>
</div>

@include('view_professor_user.agenda_semanal_professor')

@endsection