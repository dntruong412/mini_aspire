<?php

namespace Domains\Backend\Exceptions;

use Exception;

class UserExistException extends Exception
{
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
            'code'    => __('error_codes.user_exist_exception'),
            'message' => __('error_messages.user_exist_exception'),
        ], 400);
    }
}
