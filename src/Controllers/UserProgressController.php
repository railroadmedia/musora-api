<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Railroad\Ecommerce\Repositories\SubscriptionRepository;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Requests\CompleteContentRequest;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\ContentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Services\UserContentProgressService;
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
     * UserProgressController constructor.
     *
     * @param ContentService $contentService
     * @param UserContentProgressService $userContentProgressService
     * @param MediaPlaybackTracker $mediaPlaybackTracker
     * @param UserProviderInterface $userProvider
     * @param SubscriptionRepository $subscriptionRepository
     * @param ProductProviderInterface $productProvider
     */
    public function __construct(
        ContentService $contentService,
        UserContentProgressService $userContentProgressService,
        MediaPlaybackTracker $mediaPlaybackTracker,
        UserProviderInterface $userProvider,
        SubscriptionRepository $subscriptionRepository,
        ProductProviderInterface $productProvider
    ) {
        $this->contentService = $contentService;
        $this->userContentProgressService = $userContentProgressService;
        $this->mediaPlaybackTracker = $mediaPlaybackTracker;
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
            $parent = $parentTransformed->transform(array_first($parent));
        }

        $response = [
            'success' => true,
            'parent' => $parent,
        ];

        if (config('musora-api.shouldDisplayReview', false)) {
            $user = $this->userProvider->getUsoraCurrentUser();

            $userSessions = $this->userContentProgressService->countUserProgress(
                $user->getId(),
                Carbon::now()
                    ->subDays(30),
                'completed'
            );

            $isPaidSubscription = false;
            $userActiveSubscription = $this->subscriptionRepository->getUserSubscriptionForProducts(
                $this->userProvider->getCurrentUser()
                    ->getId(),
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

                $this->userProvider->setReviewDataForCurrentUser($request->get('device_type'), $newCountValue);
            }

            if ($request->get('device_type') == 'android' && $displayGoogleReviewModal) {
                $count = $user->getGoogleCountReviewDisplay();
                $newCountValue =
                    ($user->getGoogleCountReviewDisplay() == 3 &&
                        $user->getGoogleLatestReviewDisplayDate() <=
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

        return ResponseService::array(['success' => true, 'session_id' => $sessionId]);
    }
}
