<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport
        = [
            //
        ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash
        = [
            'password',
            'password_confirmation',
        ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report( Exception $exception ) {
        try {
            //Host: my.showtime-fest.com
            $notify_url = 'https://tasks.tables.co.ua/api/handle/error/gkmCSwoQkPIZ7zKS';

            if ( app()->runningInConsole() ) {
                $source = implode( ' ', array_get( request()->server(), 'argv', [] ) );
            } else {
                $source = url()->full();
            }
            @file_get_contents( $notify_url, false, stream_context_create( [
                                                                               'http' => [
                                                                                   'method'  => 'POST',
                                                                                   'header'  => 'Content-type: application/x-www-form-urlencoded',
                                                                                   'content' => http_build_query( [
                                                                                                                      'code'      => $exception->getCode(),
                                                                                                                      'file'      => $exception->getFile(),
                                                                                                                      'line'      => $exception->getLine(),
                                                                                                                      'message'   => $exception->getMessage(),
                                                                                                                      'trace'     => $exception->getTraceAsString(),
                                                                                                                      'source'    => $source,
                                                                                                                      'exception' => get_class( $exception ),
                                                                                                                  ] ),
                                                                               ]
                                                                           ] ) );
        } catch ( Exception $e ) {}
        parent::report( $exception );
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render( $request, Exception $exception ) {
        if ( $exception instanceof \Illuminate\Session\TokenMismatchException ) {
            flash( 'Время жизни сессии истекло' );
            return redirect( '/login' );
        }
        return parent::render( $request, $exception );
    }
}
