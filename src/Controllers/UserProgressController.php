<?php

namespace Railroad\MusoraApi\Controllers;

use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Services\UserContentProgressService;
use Railroad\Railcontent\Support\Collection;
use Railroad\Railtracker\Trackers\MediaPlaybackTracker;

class UserProgressController extends Controller
{

    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var UserContentProgressService
     */
    private $userContentProgressService;

    /**
     * @var MediaPlaybackTracker
     */
    private $mediaPlaybackTracker;

    /**
     * UserProgressController constructor.
     *
     * @param ContentService $contentService
     * @param UserContentProgressService $userContentProgressService
     * @param MediaPlaybackTracker $mediaPlaybackTracker
     */
    public function __construct(
        ContentService $contentService,
        UserContentProgressService $userContentProgressService,
        MediaPlaybackTracker $mediaPlaybackTracker
    ) {
        $this->contentService = $contentService;
        $this->userContentProgressService = $userContentProgressService;
        $this->mediaPlaybackTracker = $mediaPlaybackTracker;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function completeUserProgressOnContent(Request $request)
    {
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $rules = [
            'content_id' => 'required',
            'device_type' => 'required',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'title' => 'Complete Failed',
                    'message' => implode(
                        ',',
                        $validator->getMessageBag()
                            ->all()
                    ),
                ],
                422
            );
        }

        $content = $this->contentService->getById($request->get('content_id'));

        if (empty($content)) {
            return response()->json($content);
        }


        $this->userContentProgressService->completeContent(
            $request->get('content_id'),
            auth()->id()
        );

        if ($content['type'] != 'assignment') {
            ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

            $parentType = $this->getLessonTypeParentType($content['type']);

            $parent = $this->contentService->getByChildIdWhereType($request->get('content_id'), $parentType);
        } else {
            $parent = $this->contentService->getByChildId($request->get('content_id'));
        }

        $response =  [
            'success' => true,
            'parent' => $parent,
        ];

        if(config('musora-api.shouldDisplayReview', false)) {
            $user = $this->userRepository->findOneBy(['id' => auth()->id()]);

            $userSessions = $this->userContentProgressService->countUserProgress(
                $user->getId(),
                Carbon::now()
                    ->subDays(30),
                'completed'
            );

            $isPaidSubscription = false;
            $userActiveSubscriptions =
                $this->subscriptionRepository->getUserActiveSubscription(
                    $this->ecommerceUserProvider->getCurrentUser()
                );

            foreach ($userActiveSubscriptions as $userActiveSubscription) {
                foreach ($userActiveSubscription->getPayments() as $payment) {
                    if ($payment->getStatus() == 'paid' && $payment->getTotalPaid() > 0) {
                        $isPaidSubscription = true;
                    }
                }
            }

            $displayIosReviewModal =
                $isPaidSubscription &&
                (is_null($user->getIosLatestReviewDisplayDate()) ||
                    ($user->getIosCountReviewDisplay() < 3 &&
                        ($user->getIosLatestReviewDisplayDate() <
                            Carbon::now()
                                ->subDays(30))) ||
                    ($user->getIosCountReviewDisplay() == 3 &&
                        $user->getIosLatestReviewDisplayDate() <=
                        Carbon::now()
                            ->subYear(1))) &&
                ($userSessions >= ((!$user->getIosLatestReviewDisplayDate()) ? 5 : 10));

            $displayGoogleReviewModal =
                $isPaidSubscription &&
                (is_null($user->getGoogleLatestReviewDisplayDate()) ||
                    ($user->getGoogleCountReviewDisplay() < 3 &&
                        ($user->getGoogleLatestReviewDisplayDate() <
                            Carbon::now()
                                ->subDays(30))) ||
                    ($user->getGoogleCountReviewDisplay() == 3 &&
                        $user->getGoogleLatestReviewDisplayDate() <=
                        Carbon::now()
                            ->subYear(1))) &&
                ($userSessions >= ((!$user->getGoogleLatestReviewDisplayDate()) ? 5 : 10));

            if ($request->get('device_type') == 'ios' && $displayIosReviewModal) {
                $count = $user->getIosCountReviewDisplay();
                $newCountValue =
                    ($user->getIosCountReviewDisplay() == 3 &&
                        $user->getIosLatestReviewDisplayDate() <=
                        Carbon::now()
                            ->subYear(1)) ? 1 : ($count + 1);

                $user->setIosLatestReviewDisplayDate(Carbon::now());
                $user->setIosCountReviewDisplay($newCountValue);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }

            if ($request->get('device_type') == 'android' && $displayGoogleReviewModal) {
                $count = $user->getGoogleCountReviewDisplay();
                $newCountValue =
                    ($user->getGoogleCountReviewDisplay() == 3 &&
                        $user->getGoogleLatestReviewDisplayDate() <=
                        Carbon::now()
                            ->subYear(1)) ? 1 : ($count + 1);

                $user->setGoogleLatestReviewDisplayDate(Carbon::now());
                $user->setGoogleCountReviewDisplay($newCountValue);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }

            $response = array_merge($response, [
                'displayIosReviewModal' => $displayIosReviewModal,
                'displayGoogleReviewModal' => $displayGoogleReviewModal
            ]);
        }

        return response()->json($response);
    }


    /**
     * @param $childLessonType
     * @return string|null
     */
    private function getLessonTypeParentType($childLessonType)
    {
        switch ($childLessonType) {
            case 'course-part':
                return 'course';
        }

        return null;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetUserProgressOnContent(Request $request)
    {
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $content = $this->contentService->getById($request->get('content_id'));

        if (empty($content)) {
            return response()->json($content);
        }

        $this->userContentProgressService->resetContent(
            $request->get('content_id'),
            auth()->id()
        );

        if ($content['type'] != 'assignment') {
            $parentType = $this->getLessonTypeParentType($content['type']);

            $parent = $this->contentService->getByChildIdWhereType($request->get('content_id'), $parentType);
        } else {
            $parent = $this->contentService->getByChildId($request->get('content_id'));
        }

        return response()->json(
            $parent
        );
    }


    /**
     * @param Request $request
     * @param null $sessionId
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveVideoProgress(Request $request, $sessionId = null)
    {
        if (!$sessionId) {
            $mediaTypeId = $this->mediaPlaybackTracker->trackMediaType(
                $request->get('media_type', 'video'),
                $request->get('media_category', 'vimeo')
            );

            $sessionId = $this->mediaPlaybackTracker->trackMediaPlaybackStart(
                $request->get('media_id'),
                $request->get('media_length_seconds'),
                auth()->id(),
                $mediaTypeId,
                $request->get('current_second', 0)
            );

            if ($request->get('media_type', 'video') == 'video') {
                $this->userContentProgressService->startContent(
                    $request->get('content_id'),
                    auth()->id()
                );
            }
        } else {
            $this->mediaPlaybackTracker->trackMediaPlaybackProgress(
                $sessionId,
                $request->get('seconds_played'),
                $request->get('current_second')
            );
        }

        return response()->json(['success' => true, 'session_id' => $sessionId]);
    }
}
