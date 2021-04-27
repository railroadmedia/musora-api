<?php

namespace Railroad\MusoraApi\Decorators;

use Carbon\Carbon;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\Railcontent\Support\Collection;

class TimezoneDecorator extends ModeDecoratorBase
{

private $userProvider;

    /**
     * TimezoneDecorator constructor.
     *
     * @param UserProviderInterface $userProvider
     */
    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function decorate(Collection $contents)
    {
        $timezoneString = $this->userProvider->setAndGetUserTimezone(request());

        foreach ($contents as $contentIndex => $content) {
            if (!empty($content->fetch('fields.live_event_start_time'))) {
                try {
                    $contents[$contentIndex]['live_event_start_time_in_timezone'] =
                        Carbon::parse($content->fetch('fields.live_event_start_time'), 'UTC')
                            ->timezone($timezoneString)->toDateTimeString();
                } catch (\Exception $exception) {
                    $contents[$contentIndex]['live_event_start_time_in_timezone'] = null;
                    $contents[$contentIndex]['live_event_start_time'] = null;
                }
            }

            if (!empty($content->fetch('fields.live_event_end_time'))) {
                try {
                    $contents[$contentIndex]['live_event_end_time_in_timezone'] =
                        Carbon::parse($content->fetch('fields.live_event_end_time'), 'UTC')
                            ->timezone($timezoneString)->toDateTimeString();
                } catch (\Exception $exception) {
                    $contents[$contentIndex]['live_event_end_time_in_timezone'] = null;
                    $contents[$contentIndex]['live_event_end_time'] = null;
                }
            }

            $contents[$contentIndex]['published_on_in_timezone'] =
                Carbon::parse($content['published_on'], 'UTC')
                    ->timezone($timezoneString)->toDateTimeString();
        }

        return $contents;
    }
}