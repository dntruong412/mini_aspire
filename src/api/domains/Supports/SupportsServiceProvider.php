<?php

namespace Domains\Supports;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class SupportsServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->app['router']->aliasMiddleware('request', \Domains\Supports\Middlewares\RequestLog::class);
        $this->app['router']->middlewareGroup('api_req', [
            'api',
            'request'
        ]);
    }
}
