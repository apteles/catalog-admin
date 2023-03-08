<?php

namespace App\Exceptions;

use Core\Domain\Exceptions\NotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundException) {
            return $this->renderError(
                $exception->getMessage(),
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof InvalidArgumentException) {
            return $this->renderError(
                $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }

        return parent::render($request, $exception);
    }

    private function renderError(string $message, int $statusCode)
    {
        return response()->json([
            'message' => $message
        ], $statusCode);
    }

}
