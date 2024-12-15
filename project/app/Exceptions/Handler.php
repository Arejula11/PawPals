<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // App\Exceptions\Handler.php
    public function render($request, Throwable $exception)
    {   
        Log::info('inside render', ['exception' => $exception]);
        if ($exception instanceof \App\Exceptions\PolicyAuthorizationException) {
            Log::info('inside policy authorization exception');
            return redirect()->route('banned.show')
                             ->with('error', "Policy: {$exception->policy}, Action: {$exception->action}, Message: {$exception->getMessage()}");
        }
    
        return parent::render($request, $exception);
    }

}
