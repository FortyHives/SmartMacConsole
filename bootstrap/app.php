<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LocaleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
<<<<<<< HEAD
=======
    api: __DIR__ . '/../routes/api.php', // Add this if not added by install:api
>>>>>>> parent of ea68716 (Update API routes)
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(LocaleMiddleware::class);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
