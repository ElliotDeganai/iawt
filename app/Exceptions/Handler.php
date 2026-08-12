<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (!app()->environment(['local', 'testing']) && $e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();

            if (in_array($status, [403, 404, 419, 429, 500, 503])) {
                return inertia('Error', [
                    'status' => $status,
                ])
                ->toResponse($request)
                ->setStatusCode($status);
            }
        }

        return $response;
    }
}