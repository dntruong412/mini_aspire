<?php

namespace Domains\Supports\Exceptions;

use Illuminate\Validation\ValidationException as DefaultValidatorException;

class ValidationException extends DefaultValidatorException
{
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
        return response()->json([
            'status'  => 0,
            'code'    => __('error_codes.validation_exception'),
            'message' => __('error_messages.validation_exception'),
            'data'    => $this->errors()
        ], 400);
    }
}
