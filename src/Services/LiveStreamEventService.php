<?php

namespace Railroad\MusoraApi\Services;

use Carbon\Carbon;
use Exception;
use Google_Client;
use Google_Service_YouTube;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\ContentService;

class LiveStreamEventService
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var array
     */
    private static $internalCache = [];

    const NOT_LIVE_PAGE_SWITCH_MINUTES = 30;

    // upcoming live stream section from homepage is displayed only if there is an event within the next $upcomingPriorMinutes minutes
    public static $upcomingPriorMinutes = null;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * @param null $types
     * @param array $instructorIds
     * @return mixed|ContentEntity|null
     */
    public function getCurrentOrNextLiveEvent($types = null, $instructorIds = [])
    {
        if (!empty(self::$internalCache[__FUNCTION__])) {
            return self::$internalCache[__FUNCTION__];
        }

        $previousStatuses = ContentRepository::$availableContentStatues;
        $previousPullFutureContent = ContentRepository::$pullFutureContent;

        ContentRepository::$availableContentStatues = [
            ContentService::STATUS_PUBLISHED,
            ContentService::STATUS_SCHEDULED,
        ];

        ContentRepository::$pullFutureContent = true;

        $requiredInstructor = !empty($instructorIds) ? ['key' => 'instructor', 'value' => $instructorIds] : [];
        $liveEvents = $this->contentService->getWhereTypeInAndStatusAndPublishedOnOrdered(
            $types ?? config('railcontent.liveContentTypes', []),
            ContentService::STATUS_SCHEDULED,
            Carbon::now()
                ->subHours(24)
                ->toDateTimeString(),
            '>',
            'live_event_start_time',
            'asc',
            $requiredInstructor,
            10
        );

        // calculate if there is a current event and the previous/next events
        $showLivePage = false;
        $eventsWithinTimeFrame = [];
        $currentEvent = null;
        $nextEvent = null;

        foreach ($liveEvents as $liveEvent) {
            if (empty($liveEvent->fetch('fields.live_event_start_time')) ||
                empty($liveEvent->fetch('fields.live_event_end_time'))) {
                continue;
            }

            $startTimeUtc = Carbon::parse($liveEvent->fetch('fields.live_event_start_time'));
            $endTimeUtc = Carbon::parse($liveEvent->fetch('fields.live_event_end_time'));

            if (Carbon::now() > $startTimeUtc->subMinutes(self::NOT_LIVE_PAGE_SWITCH_MINUTES) &&
                Carbon::now() < $endTimeUtc->addMinutes(self::NOT_LIVE_PAGE_SWITCH_MINUTES)) {
                $showLivePage = true;
                $eventsWithinTimeFrame[] = $liveEvent;
            }
        }

        // if there are multiple events withing the time frame we need to figure out which one is closest
        foreach ($eventsWithinTimeFrame as $eventWithinTimeFrame) {
            $startTimeUtc = Carbon::parse($eventWithinTimeFrame->fetch('fields.live_event_start_time'));
            $endTimeUtc = Carbon::parse($eventWithinTimeFrame->fetch('fields.live_event_end_time'));

            if (empty($currentEvent)) {
                $currentEvent = $eventWithinTimeFrame;
            }

            if (Carbon::now() > $startTimeUtc && Carbon::now() < $endTimeUtc) {
                $currentEvent = $eventWithinTimeFrame;
            }
        }

        // if there is no current live event, calculate the next upcoming event
        if (!$showLivePage) {
            foreach ($liveEvents as $liveEvent) {
                if (empty($liveEvent->fetch('fields.live_event_start_time')) ||
                    empty($liveEvent->fetch('fields.live_event_end_time'))) {
                    continue;
                }

                $startTimeUtc = Carbon::parse($liveEvent->fetch('fields.live_event_start_time'));

                $priorCondition = true;

                if (self::$upcomingPriorMinutes) {
                    $upcomingEventStartTime =
                        Carbon::now()
                            ->addMinutes(self::$upcomingPriorMinutes)
                            ->toDateTimeString();
                    $priorCondition = $startTimeUtc <= $upcomingEventStartTime;
                }

                if (Carbon::now() < $startTimeUtc->subMinutes(self::NOT_LIVE_PAGE_SWITCH_MINUTES) && $priorCondition) {
                    $nextEvent = $liveEvent;
                    break;
                }
            }
        }

        self::$internalCache[__FUNCTION__] = $currentEvent ?? $nextEvent;

        ContentRepository::$availableContentStatues = $previousStatuses;
        ContentRepository::$pullFutureContent = $previousPullFutureContent;

        return self::$internalCache[__FUNCTION__];
    }

    /**
     * @param false $withBuffer
     * @return bool
     */
    public function currentlyLive($withBuffer = false)
    {
        $liveEvent = $this->getCurrentOrNextLiveEvent();

        if (empty($liveEvent) ||
            empty($liveEvent->fetch('fields.live_event_start_time')) ||
            empty($liveEvent->fetch('fields.live_event_end_time'))) {
            return false;
        }

        $startTimeUtc = Carbon::parse($liveEvent->fetch('fields.live_event_start_time'));
        $endTimeUtc = Carbon::parse($liveEvent->fetch('fields.live_event_end_time'));

        if ($withBuffer) {
            if (Carbon::now() > $startTimeUtc->subMinutes(self::NOT_LIVE_PAGE_SWITCH_MINUTES) &&
                Carbon::now() < $endTimeUtc->addMinutes(self::NOT_LIVE_PAGE_SWITCH_MINUTES)) {
                return true;
            }
        } else {
            if (Carbon::now() > $startTimeUtc->subMinutes(5) && Carbon::now() < $endTimeUtc->addMinutes(5)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return null|string
     */
    public function getCurrentOrNextYoutubeEventId()
    {
        try {
            $cacheStore =
                cache()
                    ->store(config('cache.default'))
                    ->getStore();
            $brand = config('railcontent.brand');
            if (method_exists($cacheStore, 'setPrefix')) {
                $oldPrefix = $cacheStore->getPrefix();
                $cacheStore->setPrefix(config('cache.prefix'));
            }

            if (cache()->has($brand . '_next_youtube_live_embed_id')) {
                return cache()->get($brand . '_next_youtube_live_embed_id');
            }

            // get the youtube id from their API
            // NOTE: this uses the YT API project inside the drumeolive@gmail.com google account
            $client = new Google_Client();
            $youtube = new Google_Service_YouTube($client);

            if (cache()->has($brand . '-yt-access-token-data')) {
                $tokenData = cache()->pull($brand . '-yt-access-token-data');
            } else {
                $client->setClientId(config('railcontent.video_sync.youtube_client_api.client_id'));
                $client->setClientSecret(config('railcontent.video_sync.youtube_client_api.client_secret'));

                $client->setScopes(['https://www.googleapis.com/auth/youtube']);
                $client->setAccessType("offline");
                $client->setApprovalPrompt('force');

                $tokenData = $client->refreshToken(config('railcontent.video_sync.youtube_client_api.refresh_token'));

                cache()->set($brand . '-yt-access-token-data', $tokenData, $tokenData['expires_in'] - 500);
            }

            $client->setAccessToken($tokenData['access_token']);

            $data = $youtube->liveBroadcasts->listLiveBroadcasts('id,snippet,contentDetails', [
                    'mine' => true,
                    'maxResults' => 25,
                ]);

            $liveBroadcastItems = $data->getItems();

            usort($liveBroadcastItems, function ($a, $b) {
                $t1 = strtotime($a->snippet->scheduledStartTime);
                $t2 = strtotime($b->snippet->scheduledStartTime);

                return $t1 - $t2;
            });

            // sort by scheduled start time and make sure to render the correct broadcast
            $broadcastToRender = null;

            foreach ($liveBroadcastItems as $broadcastItem) {
                // For some reason when a content editor open the YT live management page, it create a 'live now' event
                // on the fly with 'scheduledStartTime' = '1970-01-01T00:00:00Z' and no actual end time.
                // This has been fucking up and overriding all our scheduled events so we must skip them using this
                // check. We only want to show scheduled events, not 'live now' ones.
                if (Carbon::parse($broadcastItem->snippet->scheduledStartTime) <
                    Carbon::now()
                        ->subDay()) {
                    continue;
                }

                if (!empty($broadcastItem->snippet->scheduledStartTime) &&
                    (empty($broadcastItem->snippet->actualEndTime) ||
                        Carbon::parse($broadcastItem->snippet->actualEndTime) >
                        Carbon::now()
                            ->subMinutes(15))) { // this is how long the old event will hang around

                    if (empty($broadcastToRender) ||
                        Carbon::parse($broadcastItem->snippet->scheduledStartTime) < Carbon::parse(
                            $broadcastToRender->snippet->scheduledStartTime
                        )) {
                        $broadcastToRender = $broadcastItem;
                    }
                }
            }

            cache()->put(
                $brand . '_next_youtube_live_embed_id',
                $broadcastToRender->id ?? null,
                2
            );

            if (method_exists($cacheStore, 'setPrefix')) {
                $cacheStore->setPrefix($oldPrefix);
            }

            return $broadcastToRender->id ?? null;
        } catch (Exception $exception) {
            error_log($exception);
            return null;
        }
    }
}
