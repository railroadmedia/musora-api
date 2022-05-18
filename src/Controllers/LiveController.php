<?php

namespace Railroad\MusoraApi\Controllers;

use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Contracts\ChatProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Services\LiveStreamEventService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\Railcontent\Services\ContentService;

class LiveController extends Controller
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var ChatProviderInterface
     */
    private $chatProvider;

    /**
     * @var LiveStreamEventService
     */
    private $liveStreamEventService;
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * LiveController constructor.
     *
     * @param ContentService $contentService
     * @param ChatProviderInterface $chatProvider
     * @param LiveStreamEventService $liveStreamEventService
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        ContentService $contentService,
        ChatProviderInterface $chatProvider,
        LiveStreamEventService $liveStreamEventService,
        UserProviderInterface $userProvider
    ) {
        $this->contentService = $contentService;
        $this->chatProvider = $chatProvider;
        $this->liveStreamEventService = $liveStreamEventService;
        $this->userProvider = $userProvider;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getLiveEvent(Request $request)
    {
        $this->userProvider->setAndGetUserTimezone();

        $chatrollEmbedUrl = $this->chatProvider->getEmbedUrl();

        if ($request->has('forced-content-id')) {
            // this is for previewing any live event
            $currentEvent = $this->contentService->getById($request->get('forced-content-id'));
            $currentEvent['isLive'] = true;
            $youtubeId = '36YnV9STBqc';
        } elseif ($request->has('forced-upcoming-content-id')) {
            // this is for previewing any upcoming event
            $currentEvent = $this->contentService->getById($request->get('forced-upcoming-content-id'));
            $currentEvent['isLive'] = false;
            $youtubeId = '36YnV9STBqc';
        } else {
            if (config('railcontent.appUpcomingEventPriorMinutes')) {
                LiveStreamEventService::$upcomingPriorMinutes = config('railcontent.appUpcomingEventPriorMinutes');
            }

            //get current or next live event for selected instructor
            $fieldIds = [];
            if ($request->has('coach_id')) {
                $coach = $this->contentService->getById($request->get('coach_id'));

                if ($coach) {
                    $fieldIds[] = $coach['id'];
                    $instructor =
                        $this->contentService->getBySlugAndType($coach['slug'], 'coach')
                            ->first();
                    if ($instructor) {
                        $fieldIds[] = $instructor['id'];
                    }
                }
            }

            $currentEvent = $this->liveStreamEventService->getCurrentOrNextLiveEvent(null, $fieldIds);
        }

        if (!$currentEvent) {
            return ResponseService::array([]);
        }

        $currentEvent['instructor_head_shot_picture_url'] =
            $currentEvent->fetch('fields.instructor.data.head_shot_picture_url');

        $currentEvent['youtube_video_id'] = $youtubeId ?? $currentEvent->fetch(
                'fields.live_event_youtube_id',
                $this->liveStreamEventService->getCurrentOrNextYoutubeEventId()
            );
        $currentEvent['chatRollEmbedUrl'] = $chatrollEmbedUrl;
        $currentEvent['chatRollViewersNumberClass'] = '.chat-online-count';
        $currentEvent['chatRollStyle'] = $this->chatProvider->getCustomStyle();

        $currentEvent['userId'] = auth()->id();
//            $this->userProvider->getCurrentUser()
//                ->getId();

        $railchatDataArray = $this->chatProvider->getRailchatData();

        $event = array_merge($currentEvent->getArrayCopy(), $railchatDataArray);

        return ResponseService::live($event);
    }

}
