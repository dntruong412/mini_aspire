<?php

namespace Domains\Supports\Exceptions;

use Exception;

class ActionFailedException extends Exception
{
    protected $responseParams = [];
    protected $responseData = [];

    public function __construct($responseParams, $responseData = []) {
        $this->responseParams = $responseParams;
        $this->responseData = $responseData;
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
            return response()->json(array_merge([ 'status' => 0 ], $this->responseParams, $this->responseData), 400);
        }
        return response()->json(array_merge([
            'status'  => 0,
            'message' => $this->responseParams
        ], $this->responseData), 400);
    }
}
