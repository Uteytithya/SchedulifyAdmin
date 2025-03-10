<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api/user')
                ->middleware('api')
                ->name('api.user.')
                ->group(base_path('routes/user.php'));

            Route::prefix('admin')
                ->middleware('web')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function () {
            if (Request::is('api/*')) {
                return abort(response()->json([
                    'message' => 'Unauthorized access. Please log in.'
                ], Response::HTTP_UNAUTHORIZED));
            }
            else {
                return route('admin.login');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
