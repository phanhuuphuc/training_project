<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Predis\Connection\ConnectionException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mi\L5Core\Exceptions\BaseException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
    // public function register(): void
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        // check if !api then return
        if (!$request->is('api/*') && !$request->ajax()) {
            return parent::render($request, $e);
        }

        // handle for exception api
        $statusCode  = 400;
        $errors      = [];
        $message     = __('messages.errors.unexpected');
        if (!($e instanceof BaseException)) {
            Log::error($e);
        }
        switch (true) {
            case $e instanceof ValidationException:
                $message    = __('messages.errors.input');
                $errors     = $e->errors();
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                break;

            case $e instanceof AuthenticationException:
                $message = __('messages.' . $e->getMessage());
                $errors     = $e->getMessage();
                $statusCode = Response::HTTP_UNAUTHORIZED;
                break;

            case $e instanceof ModelNotFoundException:
                $message = __('messages.errors.not_found');
                $errors = 'record.not_found';
                $statusCode = Response::HTTP_NOT_FOUND;
                break;

            case $e instanceof RouteNotFoundException:
            case $e instanceof NotFoundHttpException:
            case $e instanceof MethodNotAllowedHttpException:
                $message    = __('messages.errors.route');
                $errors     = 'route.not_found';
                $statusCode = Response::HTTP_NOT_FOUND;
                break;

            case $e instanceof BaseException:
                $message    = $e->getMessage();
                $errors     = $e->getMessageCode();
                $statusCode = $e->getCode();
                break;

            case $e instanceof ConnectionException:
            case $e instanceof PDOException:
                $statusCode  = 500;
                break;

            default:
                break;
        }

        return ($request->is('api/*') || $request->ajax())
            ? response()->json([
                'message' => $message,
                'errors'  => $errors,
                'code'    => $statusCode
            ], $statusCode) : response($message, 400);
    }
}
