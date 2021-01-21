<?php

namespace Railroad\MusoraApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Railroad\MusoraApi\Decorators\DateFormatDecorator;
use Railroad\MusoraApi\Decorators\MobileAppUrlDecorator;
use Railroad\MusoraApi\Decorators\StripTagDecorator;
use Railroad\Railcontent\Services\ConfigService;

class SetCustomDecorators
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allDecoratorsForContent =
            array_merge(
                ConfigService::$decorators['content'],
                [DateFormatDecorator::class, StripTagDecorator::class, MobileAppUrlDecorator::class]
            );

        $allDecoratorsForComment = array_merge(ConfigService::$decorators['comment'], [StripTagDecorator::class]);

        ConfigService::$decorators['content'] = $allDecoratorsForContent;
        ConfigService::$decorators['comment'] = $allDecoratorsForComment;

        return $next($request);
    }
}