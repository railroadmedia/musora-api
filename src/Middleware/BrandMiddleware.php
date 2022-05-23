<?php

namespace Railroad\MusoraApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Railroad\Railcontent\Services\ConfigService;

class BrandMiddleware
{
    /**
     * Allows brand to be set on any request for the host installationg.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('brand') ) {
            ConfigService::$brand = $request->get('brand');
            ConfigService::$availableBrands = Arr::wrap($request->get('brand'));
        }

        return $next($request);
    }
}