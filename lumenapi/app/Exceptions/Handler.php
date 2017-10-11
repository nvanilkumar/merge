<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use App\Models\Error;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        $message = trim($e->getMessage());
        $code = $e->getCode();
        $statusCode = 500;

        if ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();

            if ($code === 0) {
                $code = $statusCode;
            }

            if (!strlen($message)) {

                switch ($statusCode) {
                    case 404:
                        $message = 'Resource not found';
                        break;
                    case 400:
                        $message = 'Malformed request';
                        break;
                    case 401:
                        $message = 'Unauthorized';
                        break;
                    case 403:
                        $message = 'Access denied';
                        break;
                    case 409:
                        $message = 'There was a conflict with the request';
                        break;
                    default:
                        $message = 'An unexpected error has occurred';
                        break;
                }
            }
        } else if ($e instanceof MethodNotAllowedHttpException) {
            $message = 'Method not allowed';
            $code = $e->getStatusCode();
                    
        } else if ($e instanceof InvalidArgumentException) {
            $message = (strlen($message) > 0 ) ? $message : 'An unexpected error has occurred';
            $statusCode=412; 

        } else {

//            var_dump($e);
            $statusCode= (method_exists ($e,"getStatusCode") === true)? $e->getStatusCode(): $statusCode ;
            $message = (strlen($message) > 0 ) ? $message : 'An unexpected error has occurred';
        }

        return response()->json(new Error($message, $code), $statusCode);

    }

}
