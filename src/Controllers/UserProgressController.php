<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Ecommerce\Repositories\SubscriptionRepository;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Contracts\RailTrackerProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Requests\CompleteContentRequest;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\ContentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Services\UserContentProgressService;
use Illuminate\Validation\ValidationException;

class UserProgressController extends Controller
{
    use ValidatesRequests;

    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var UserContentProgressService
     */
    private $userContentProgressService;

    /**
     * @var RailTrackerProviderInterface
     */
    private $mediaTrackerProvider;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;
    /**
     * @var ProductProviderInterface
     */
    private $productProvider;

    /**
     * @param ContentService $contentService
     * @param UserContentProgressService $userContentProgressService
     * @param RailTrackerProviderInterface $mediaPlaybackTracker
     * @param UserProviderInterface $userProvider
     * @param SubscriptionRepository $subscriptionRepository
     * @param ProductProviderInterface $productProvider
     */
    public function __construct(
        ContentService $contentService,
        UserContentProgressService $userContentProgressService,
        RailTrackerProviderInterface $mediaPlaybackTracker,
        UserProviderInterface $userProvider,
        SubscriptionRepository $subscriptionRepository,
        ProductProviderInterface $productProvider
    ) {
        $this->contentService = $contentService;
        $this->userContentProgressService = $userContentProgressService;
        $this->mediaTrackerProvider = $mediaPlaybackTracker;
        $this->userProvider = $userProvider;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->productProvider = $productProvider;
    }

    /**
     * @param CompleteContentRequest $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Throwable
     */
    public function completeUserProgressOnContent(CompleteContentRequest $request)
    {
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $content = $this->contentService->getById($request->get('content_id'));
        throw_if(!$content, new NotFoundException('Content not found.'));

        $this->userContentProgressService->completeContent(
            $request->get('content_id'),
            $this->userProvider->getCurrentUser()->getId()
        );

        if ($content['type'] != 'assignment') {
            ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

            $parentType = $this->getLessonTypeParentType($content['type']);

            $parent = $this->contentService->getByChildIdWhereType($request->get('content_id'), $parentType);
        } else {
            $parent = $this->contentService->getByChildId($request->get('content_id'));
        }

        if ($parent->isNotEmpty()) {
            $parentTransformed = new ContentTransformer();
            $parent = $parentTransformed->transform(\Arr::first($parent));
        }

        $response = [
            'success' => true,
            'parent' => $parent,
        ];

        if (config('musora-api.shouldDisplayReview', false)) {
            $user = auth()->user();

            $userSessions = $this->userContentProgressService->countUserProgress(
                $user->id,
                Carbon::now()
                    ->subDays(30),
                'completed'
            );

            $isPaidSubscription = false;

            //TODO: Need to investigate how should I decide if the user has active and paid membership
            $userActiveSubscription = $this->subscriptionRepository->getUserSubscriptionForProducts(
                auth()->id(),
                $this->productProvider->getMembershipProductIds(),
                1
            );

            if ($userActiveSubscription) {
                foreach ($userActiveSubscription->getPayments() as $payment) {
                    if ($payment->getStatus() == 'paid' && $payment->getTotalPaid() > 0) {
                        $isPaidSubscription = true;
                    }
                }
            }

            $isPaidSubscription = true;
            $displayIosReviewModal =
                $isPaidSubscription &&
                (is_null($user->ios_latest_review_display_date) ||
                    ($user->ios_count_review_display < 3 &&
                        ($user->ios_latest_review_display_date <
                            Carbon::now()
                                ->subDays(30))) ||
                    ($user->ios_count_review_display == 3 &&
                        $user->ios_latest_review_display_date <=
                        Carbon::now()
                            ->subYear(1))) &&
                ($userSessions >= ((!$user->ios_latest_review_display_date) ? 5 : 10));

            $displayGoogleReviewModal =
                $isPaidSubscription &&
                (is_null($user->google_latest_review_display_date) ||
                    ($user->google_count_review_display < 3 &&
                        ($user->google_latest_review_display_date <
                            Carbon::now()
                                ->subDays(30))) ||
                    ($user->google_count_review_display == 3 &&
                        $user->google_latest_review_display_date <=
                        Carbon::now()
                            ->subYear(1))) &&
                ($userSessions >= ((!$user->google_latest_review_display_date) ? 5 : 10));

            if ($request->get('device_type') == 'ios' && $displayIosReviewModal) {
                $count = $user->ios_count_review_display;
                $newCountValue =
                    ($user->ios_count_review_display == 3 &&
                        $user->ios_latest_review_display_date <=
                        Carbon::now()
                            ->subYear(1)) ? 1 : ($count + 1);

                $this->userProvider->setReviewDataForCurrentUser($request->get('device_type'), $newCountValue);
            }

            if ($request->get('device_type') == 'android' && $displayGoogleReviewModal) {
                $count = $user->google_count_review_display;
                $newCountValue =
                    ($user->google_count_review_display == 3 &&
                        $user->google_latest_review_display_date <=
                        Carbon::now()
                            ->subYear(1)) ? 1 : ($count + 1);

                $this->userProvider->setReviewDataForCurrentUser($request->get('device_type'), $newCountValue);
            }

            $response = array_merge(
                $response,
                [
                    'displayIosReviewModal' => $displayIosReviewModal,
                    'displayGoogleReviewModal' => $displayGoogleReviewModal,
                ]
            );
        }

        return ResponseService::array($response);
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
            case 'pack-bundle-lesson':
                return 'pack-bundle';
            case 'song-part':
                return 'song';
            case 'play-along-part':
                return 'play-along';
            case 'unit-part':
                return 'unit';
        }


        return null;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function resetUserProgressOnContent(Request $request)
    {
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $content = $this->contentService->getById($request->get('content_id'));
        throw_if(!$content, new NotFoundException('Content not found.'));

        $this->userContentProgressService->resetContent(
            $request->get('content_id'),
            $this->userProvider->getCurrentUser()
                ->getId()
        );

        if ($content['type'] != 'assignment') {
            $parentType = $this->getLessonTypeParentType($content['type']);

            $parent = $this->contentService->getByChildIdWhereType($request->get('content_id'), $parentType);
        } else {
            $parent = $this->contentService->getByChildId($request->get('content_id'));
        }

        if ($parent->isEmpty()) {
            return ResponseService::empty();
        }

        return ResponseService::content($parent->first());
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
        try {
            $this->validate(
                $request,
                [
                    'media_id' => 'required',
                    'media_length_seconds' => 'numeric',
                    'media_type' => 'required|string',
                    'media_category' => 'required|string',
                    'current_second' => 'numeric|min:0',
                    'seconds_played' => 'numeric|min:0',
                ]
            );
        } catch (ValidationException $exception) {
            throw new HttpResponseException(
                response()->json(
                    [
                        'errors' => $exception->errors(),
                    ],
                    422
                )
            );
        }

        if (!$sessionId) {
            $mediaTypeId = $this->mediaTrackerProvider->trackMediaType(
                $request->get('media_type', 'video'),
                $request->get('media_category', 'vimeo')
            );

            $sessionId = $this->mediaTrackerProvider->trackMediaPlaybackStart(
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
            $this->mediaTrackerProvider->trackMediaPlaybackProgress(
                $sessionId,
                $request->get('seconds_played'),
                $request->get('current_second')
            );
        }

        return ResponseService::array(['success' => true, 'session_id' => $sessionId]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function resetUserProgressOnContentModified(Request $request)
    {
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $content = $this->contentService->getById($request->get('content_id'));
        throw_if(!$content, new NotFoundException('Content not found.'));

        $this->userContentProgressService->resetContent(
            $request->get('content_id'),
            $this->userProvider->getCurrentUser()
                ->getId()
        );

        if ($content['type'] != 'assignment') {
            $parentType = $this->getLessonTypeParentType($content['type']);

            $parent = $this->contentService->getByChildIdWhereType($request->get('content_id'), $parentType);
        } else {
            $parent = $this->contentService->getByChildId($request->get('content_id'));
        }

        if ($parent->isEmpty()) {
            return ResponseService::empty();
        }

        $parentTransformed = new ContentTransformer();
        $parent = $parentTransformed->transform(\Arr::first($parent));

        $response = [
            'success' => true,
            'parent' => $parent,
        ];

        return ResponseService::array($response);
    }
}
