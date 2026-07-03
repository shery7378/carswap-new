<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminNoCache;

use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'role' => RoleMiddleware::class,
      'permission' => PermissionMiddleware::class,
      'role_or_permission' => RoleOrPermissionMiddleware::class,
      'admin.no_cache' => AdminNoCache::class,
    ]);

    $middleware->validateCsrfTokens(except: [
        'api/webhooks/stripe',
    ]);

    $middleware->web(append: [
        //
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (TokenMismatchException $e, Request $request) {
        $isAdminArea = $request->is('dashboard', 'profile', 'app/*');

        if ($isAdminArea) {
            return redirect()
                ->route('login')
                ->with('status', 'Your session expired. Please sign in again.');
        }

        return response()->view('errors.419', [], 419);
    });
  })->create();
