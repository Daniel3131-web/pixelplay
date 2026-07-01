<?php

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();

            if ($user) {

                if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
                    return '/verify-email';
                }

                
                if ($user->role === 'organizador') {
                    return '/dashboard'; 
                }

                return '/eventos';
            }

            return '/';
        });

        $middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);

        $middleware->alias([
            'org' => \App\Http\Middleware\EnsureUserIsOrganizer::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
