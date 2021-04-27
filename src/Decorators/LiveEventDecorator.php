<?php

namespace Railroad\MusoraApi\Decorators;

use Carbon\Carbon;
use Exception;
use Railroad\Railcontent\Support\Collection;

class LiveEventDecorator extends ModeDecoratorBase
{

    const NOT_LIVE_PAGE_SWITCH_MINUTES = 30;

    /**
     * @param Collection $contents
     * @return Collection
     */
    public function decorate(Collection $contents)
    {
        $contentsOfType = $contents->whereIn('type', config('railcontent.liveContentTypes'));

        if ($contentsOfType->isEmpty()) {
            return $contents;
        }

        foreach ($contents as $contentIndex => $content) {
            if (in_array($content['type'], config('railcontent.liveContentTypes'))) {
                continue;
            }
            if (empty($content->fetch('fields.live_event_start_time')) ||
                empty($content->fetch('fields.live_event_end_time'))) {
                continue;
            }

            try {
                $startTimeUtc = Carbon::parse($content->fetch('fields.live_event_start_time'));
                $endTimeUtc = Carbon::parse($content->fetch('fields.live_event_end_time'));

                if (Carbon::now() > $startTimeUtc->subMinutes(self::NOT_LIVE_PAGE_SWITCH_MINUTES) &&
                    Carbon::now() < $endTimeUtc->addMinutes(self::NOT_LIVE_PAGE_SWITCH_MINUTES)) {
                    $contents[$contentIndex]['isLive'] = true;
                } else {
                    $contents[$contentIndex]['isLive'] = false;
                }
            } catch (Exception $exception) {
                error_log($exception);

                $contents[$contentIndex]['isLive'] = false;
            }
        }

        return $contents;
    }
}
