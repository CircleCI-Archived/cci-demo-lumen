<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     * By default several of these are listed but I've commented out
     * a few so that we get better control over our API errors. -AKF
     *
     * @var array
     */
    protected $dontReport = [
        //AuthorizationException::class,
        HttpException::class,
        //ModelNotFoundException::class,
        //ValidationException::class,
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
        // TODO: Update
        if ($request->wantsJson()) {
            $response = [
                'message' => (string) $e->getMessage(),
                'status' => 400
            ];

            if ($e instanceof HttpException) {
                $response['message'] = Response::$statusTexts[$e->getStatusCode()];
                $response['status'] = $e->getStatusCode();
            } else if ($e instanceof ModelNotFoundException) {
                $response['message'] = Response::$statusTexts[Response::HTTP_NOT_FOUND];
                $response['status'] = Response::HTTP_NOT_FOUND;
            }

            if ($this->isDebugMode()) {
                $response['debug'] = [
                    'exception' => get_class($e),
                    'trace' => $e->getTrace()
                ];
            }

            return response()->json(['error' => $response], $response['status']);
        }

        return parent::render($request, $e);
    }

















    /**
     * See if our app is in debug mode or not.
     *
     * @return bool
     */
    public function isDebugMode()
    {
        return (Boolean) env('APP_DEBUG');
    }

}

