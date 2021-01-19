<?php

namespace Railroad\MusoraApi\Decorators;

use Carbon\Carbon;
use Railroad\Railcontent\Support\Collection;
use Exception;

class DateFormatDecorator extends ModeDecoratorBase
{
    public function decorate(Collection $contents)
    {
        try {
            foreach ($contents as $contentIndex => $content) {

                $contents[$contentIndex]['published_on'] =
                    Carbon::parse($content['published_on'])
                        ->format('Y/m/d H:i:s');

                foreach ($content['fields'] as $index => $field) {
                    if ($field['key'] === 'live_event_start_time') {
                        $contents[$contentIndex]['fields'][$index]['value'] =
                            Carbon::parse($field['value'])
                                ->format('Y/m/d H:i:s');
                    }
                    if ($field['key'] === 'live_event_end_time') {
                        $contents[$contentIndex]['fields'][$index]['value'] =
                            Carbon::parse($field['value'])
                                ->format('Y/m/d H:i:s');
                    }
                }

                if (isset($content['live_event_start_time_in_timezone'])) {
                    $contents[$contentIndex]['live_event_start_time_in_timezone'] =
                        $content['live_event_start_time_in_timezone']->format('Y/m/d H:i:s');
                }
                if (isset($content['live_event_end_time_in_timezone'])) {
                    $contents[$contentIndex]['live_event_end_time_in_timezone'] =
                        $content['live_event_end_time_in_timezone']->format('Y/m/d H:i:s');
                }
                if (isset($content['published_on_in_timezone'])) {
                    $contents[$contentIndex]['published_on_in_timezone'] =
                        $content['published_on_in_timezone']->format('Y/m/d H:i:s');
                }
            }
        }catch(Exception $exception){

        }

        return $contents;
    }
}