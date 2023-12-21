<?php

namespace Railroad\MusoraApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Railroad\MusoraApi\Decorators\DateFormatDecorator;
use Railroad\MusoraApi\Decorators\LiveEventDecorator;
use Railroad\MusoraApi\Decorators\MobileAppUrlDecorator;
use Railroad\MusoraApi\Decorators\OldPlatformLinksDecorator;
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
        $allDecoratorsForContent = array_merge(config('railcontent.decorators')['content'], [
            //   TimezoneDecorator::class,
            DateFormatDecorator::class,
            StripTagDecorator::class,
            MobileAppUrlDecorator::class,
            LiveEventDecorator::class,
            OldPlatformLinksDecorator::class,
        ]);

        $allDecoratorsForComment = array_merge(
            config('railcontent.decorators')['comment'],
            [StripTagDecorator::class, DateFormatDecorator::class, OldPlatformLinksDecorator::class,]
        );

        $allDecoratorsForPlaylistItems = array_merge(
            config('railcontent.decorators')['playlist-item'],
            [DateFormatDecorator::class]
        );

        ConfigService::$decorators['content'] = $allDecoratorsForContent;
        ConfigService::$decorators['comment'] = $allDecoratorsForComment;
        ConfigService::$decorators['playlist-item'] = $allDecoratorsForPlaylistItems;

        return $next($request);
    }
}