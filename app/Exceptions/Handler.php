<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request); 
        }

        if ($exception instanceof ModelNotFoundException){
           $modelo = strtolower(class_basename($exception->getModel()));
           return $this->errorResponse("No hay una instancia de {$modelo} con ese ID", 404); 
        }

        if ($exception instanceof AuthenticationException) 
           return $this->unauthenticated($request, $exception);

        if ($exception instanceof AuthorizationException) 
           return $this->errorResponse("No tiene permisos para ejecutar esta accion", 403);
        
        if ($exception instanceof NotFoundHttpException) 
           return $this->errorResponse("No se encontró la URL especificada", 404);

        if ($exception instanceof MethodNotAllowedHttpException) 
           return $this->errorResponse("Método especificado no válido", 404);

        if ($exception instanceof HttpException) 
           return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());

        if ($exception instanceof QueryException) {
           $codigoError = $exception->errorInfo[1];
           if ($codigoError == 1451)
               return $this->errorResponse('No se puede eliminar este recurso porque está relacionado con otro.', 409);
        }
    
        if (config('app.debug'))  // Si esta en modo debug se muestra el error completo en html  
           return parent::render($request, $exception);
        
        // Si no esta en modo debug no se muestra el error sino el siguiente mensaje
        return $this->errorResponse("Error inesperado",500);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No autenticado', 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors=$e->validator->errors()->getmessages();
        return $this->errorResponse($errors,422);
    }
}
