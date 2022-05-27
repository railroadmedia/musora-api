<?php

namespace Railroad\MusoraApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Railroad\Railcontent\Services\ConfigService;

class ApiVersionMiddleware
{
    /**
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        config(['musora-api.api.version' => $guard]);
        return $next($request);
    }
}