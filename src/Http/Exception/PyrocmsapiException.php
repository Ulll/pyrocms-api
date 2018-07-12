<?php

namespace Pyrocmsapi\Http\Exception;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as BaseExceptionHandler;
use Illuminate\Contracts\Debug\ExceptionHandler;

class PyrocmsapiException extends BaseExceptionHandler
{
    /**
     * @var ExceptionHandler
     */
    protected $handler;

    /**
     * CustomExceptionHandler constructor.
     *
     * @param  ExceptionHandler $handler
     */
    public function __construct(ExceptionHandler $handler)
    {
        $this->handler  = $handler;
    }

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

        $this->handler->report($exception);
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

        return $this->handler->render($request, $exception);
    }
}
