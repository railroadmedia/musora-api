<?php

namespace Railroad\MusoraApi\Controllers;

use App\Services\LiveStreamEventService;
use App\Services\User\UserAccessService;
use ChatRoll;
use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\Railcontent\Services\ContentService;
use Services\CalendarService;

class LiveController extends Controller
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var ChatRoll
     */
    private $chatRoll;

    /**
     * @var CalendarService
     */
    private $calendarService;

    /**
     * @var LiveStreamEventService
     */
    private $liveStreamEventService;

    /**
     * LiveController constructor.
     *
     * @param ContentService $contentService
     * @param ChatRoll $chatRoll
     * @param CalendarService $calendarService
     * @param LiveStreamEventService $liveStreamEventService
     */
    public function __construct(
        ContentService $contentService,
        ChatRoll $chatRoll,
        CalendarService $calendarService,
        LiveStreamEventService $liveStreamEventService
    ) {
        $this->contentService = $contentService;
        $this->chatRoll = $chatRoll;
        $this->calendarService = $calendarService;
        $this->liveStreamEventService = $liveStreamEventService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getLiveEvent(Request $request)
    {
        $member = current_user();
        $this->calendarService->getTimezone($request);

        $chatrollEmbedUrl = $this->chatRoll->getEmbedUrl(
            $member->getId(),
            $member->getDisplayName(),
            $member->getProfilePictureUrl(),
            url()->route('user.dashboard', [$member->getId()]),
            UserAccessService::isAdministrator($member->getId())
        );

        if ($request->has('forced-content-id')) {
            // this is for previewing any upcoming event
            $currentEvent = $this->contentService->getById($request->get('forced-content-id'));
            $youtubeId = '36YnV9STBqc';
        } else {

            if (config('railcontent.appUpcomingEventPriorMinutes')) {
                LiveStreamEventService::$upcomingPriorMinutes = config('railcontent.appUpcomingEventPriorMinutes');
            }

            $currentEvent = $this->liveStreamEventService->getCurrentOrNextLiveEvent();
        }

        if (!$currentEvent) {
            return response()->json();
        }

        $currentEvent['youtubeId'] = $youtubeId ?? $this->liveStreamEventService->getCurrentOrNextYoutubeEventId();
        $currentEvent['chatRollEmbedUrl'] =$chatrollEmbedUrl;
        $currentEvent['chatRollViewersNumberClass'] =  '.chat-online-count';
        $currentEvent['chatRollStyle'] = $this->chatRoll->getCustomStyle();

        return ResponseService::live($currentEvent);
    }

}
