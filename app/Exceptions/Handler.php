<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse(__("Unauthorized"), Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse(__("Unauthenticated"), Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->errorResponse(__("Model not found"), Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse(__("Not found"), Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse(__("Method not allowed"), Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof \Exception) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
