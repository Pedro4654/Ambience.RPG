<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use App\Http\Middleware\VerificarStaff;
use App\Http\Middleware\VerificarAdmin;
use App\Http\Middleware\VerificarPunicoes;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            VerificarPunicoes::class,
        ]);

        $middleware->redirectGuestsTo('login');

        // Adicionar middleware de verificação de punições
        $middleware->append(VerificarPunicoes::class);

        // Registrar aliases de middlewares personalizados
        $middleware->alias([
            'guest.custom' => \App\Http\Middleware\RedirectIfAuthenticatedCustom::class,
            'verificar.staff' => VerificarStaff::class,
            'verificar.admin' => VerificarAdmin::class,
            'admin'            => VerificarAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();