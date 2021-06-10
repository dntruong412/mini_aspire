<?php

namespace Domains\Supports\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $responseParams = [];

    public function __construct($message) {
        $this->responseParams = $message;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request)
    {
        if (is_array($this->responseParams)) {
            return response()->json(array_merge(['status' => 0], $this->responseParams), 404);
        }
        return response()->json([
            'status'  => 0,
            'message' => $this->responseParams
        ], 404);
    }
}
