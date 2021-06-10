<?php

namespace Domains\Supports\Middlewares;

use Closure;

class RequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  String  $scope
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $requestData = $request->all();
        $responseData = $response->getOriginalContent();
        if (isset($responseData['exception'])) {
            unset($responseData['trace']); 
        } else {
            if (isset($responseData['data'])) {
                if (!is_array($responseData['data'])) {
                    $responseData['data'] = json_decode(json_encode($responseData['data']), true);
                }
            }
        }

        $logData = [
            'method'   => $request->method(), 
            'request'  => $requestData,
            'response' => $responseData
        ];

        if ($response->status() == 200) {
            \Log::channel('request')->info($request->fullUrl(), $logData);
        } else {
            \Log::channel('request')->error($request->fullUrl(), $logData);
        }

        return $response;
    }
}
