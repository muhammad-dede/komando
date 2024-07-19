<?php

namespace App\Exceptions;

use Adldap\Auth\PasswordRequiredException;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\Exception\NotReadableException;
use Psy\Exception\ErrorException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

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
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        if (app()->bound('sentry') && $this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
		if ($e instanceof AuthorizationException) {
			return redirect()->back()->withWarning("You are not authorized to this action");
		}

        if ($e instanceof NotFoundHttpException) {
            return response(view('errors.404'), 404);
		}

        if ($e instanceof TokenMismatchException) {
            //redirect to a form. Here is an example of how I handle mine
            return redirect($request->fullUrl())->with('error', "Opps! Seems you couldn't submit form for a longtime. Please try again");
        }

        if (env('EXCEPTION_HANDLER', true)) {

            if ($e instanceof HttpException) {
                return response(view('errors.503'), 404);
            }

            if ($e instanceof QueryException) {
                $error_code = $e->getCode();
                $error_message = $e->getSql();
                return response(view('errors.500', compact('error_code', 'error_message')), 404);
            }

            if ($e instanceof Oci8Exception) {
                $error_code = $e->getCode();
                $error_message = $e->getFile() . ' (' . $e->getLine() . ')';
                return response(view('errors.500', compact('error_code', 'error_message')), 404);
            }

            if ($e instanceof FatalErrorException) {
                $error_code = $e->getCode();
                $error_message = $e->getFile() . ' (' . $e->getLine() . ')';
                return response(view('errors.500', compact('error_code', 'error_message')), 404);
            }

            if ($e instanceof ErrorException) {
                $error_code = $e->getCode();
                $error_message = $e->getFile() . ' (' . $e->getLine() . ')';
                return response(view('errors.500', compact('error_code', 'error_message')), 404);
            }

            if ($e instanceof \ReflectionException) {
                $error_code = $e->getCode();
                $error_message = $e->getFile() . ' (' . $e->getLine() . ')';
                return response(view('errors.500', compact('error_code', 'error_message')), 404);
            }

            if ($e instanceof NotReadableException) {
                $error_code = $e->getCode();
                $error_message = $e->getFile() . ' (' . $e->getLine() . ')';
                return response(view('errors.500', compact('error_code', 'error_message')), 404);
            }

//        if ($e instanceof PasswordRequiredException){
//            $error_code = $e->getCode();
//            $error_message = $e->getFile().' ('.$e->getLine().')';
//            return response(view('errors.500', compact('error_code', 'error_message')), 404);
//        }

            if ($e instanceof FileNotFoundException) {
                $error_code = $e->getCode();
                $error_message = $e->getFile() . ' (' . $e->getLine() . ')';
                return response(view('errors.500', compact('error_code', 'error_message')), 404);
			}
			
            /*     if ($e instanceof QueryException) {
                     $error_code = $e->getCode();
                     $error_message = $e->getSql();
                     return response(view('errors.500', compact('error_code', 'error_message')), 404);
                 }

             if ($e instanceof Oci8Exception){
                 $error_code = $e->getCode();
                 $error_message = $e->getFile().' ('.$e->getLine().')';
                 return response(view('errors.500', compact('error_code', 'error_message')), 404);
             }
         */
//            if ($e instanceof Exception) {
//                $error_code = $e->getCode();
//                $error_message = '';
//                return response(view('errors.500', compact('error_code', 'error_message')), 500);
//            }
        }

        return parent::render($request, $e);
    }
}
