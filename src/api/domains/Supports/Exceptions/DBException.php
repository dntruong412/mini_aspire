<?php

namespace Domains\Supports\Exceptions;

use Exception;

class DBException extends Exception
{
    protected $exception;

    public function __construct($exception) {
        $this->exception = $exception;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        $removePath = str_replace('public', '', $_SERVER['DOCUMENT_ROOT']);
        $errorString = str_replace($removePath, '', $this->exception->getFile()) . ':' . $this->exception->getLine();
        \Log::error($errorString, ['message' => $this->exception->getMessage()]);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request)
    {
        return response()->json([
            'status'  => 0,
            'code'    => __('error_codes.db_exception'),
            'message' => __('error_messages.db_exception')
        ], 500);
    }
}
