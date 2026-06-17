<?php
// app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {

        // Adicione esta linha:
        $middleware->appendToGroup('web', \App\Http\Middleware\PreventBackHistory::class);

        $middleware->alias([
            'admin'                => \App\Http\Middleware\AdminMiddleware::class,
            'professor'            => \App\Http\Middleware\ProfessorMiddleware::class,
            'aluno'                => \App\Http\Middleware\AlunoMiddleware::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
