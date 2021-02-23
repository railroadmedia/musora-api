<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Ecommerce\Entities\Subscription;
use Railroad\Ecommerce\Events\AppSignupStartedEvent;
use Railroad\Ecommerce\Repositories\SubscriptionRepository;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\Usora\Entities\UserFirebaseTokens;
use Railroad\Usora\Managers\UsoraEntityManager;
use Railroad\Usora\Repositories\UserFirebaseTokensRepository;
use Railroad\Usora\Repositories\UserRepository;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserFirebaseTokensRepository
     */
    private $userFirebaseTokensRepository;

    /**
     * @var ProductProviderInterface
     */
    private $productProvider;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    const LOGIN_INVALID_USERNAME = 'invalid_username';
    const LOGIN_INCORRECT_PASSWORD = 'incorrect_password';
    const LOGIN_EMPTY_USERNAME = 'empty_username';
    const LOGIN_EMPTY_PASSWORD = 'empty_password';

    /**
     * AuthController constructor.
     *
     * @param UsoraEntityManager $entityManager
     * @param JWTAuth $jwtAuth
     * @param SubscriptionRepository $subscriptionRepository
     * @param UserRepository $userRepository
     * @param UserFirebaseTokensRepository $userFirebaseTokensRepository
     * @param ProductProviderInterface $productProvider
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        UsoraEntityManager $entityManager,
        JWTAuth $jwtAuth,
        SubscriptionRepository $subscriptionRepository,
        UserRepository $userRepository,
        UserFirebaseTokensRepository $userFirebaseTokensRepository,
        ProductProviderInterface $productProvider,
        UserProviderInterface $userProvider
    ) {
        $this->entityManager = $entityManager;
        $this->jwtAuth = $jwtAuth;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
        $this->userFirebaseTokensRepository = $userFirebaseTokensRepository;
        $this->productProvider = $productProvider;
        $this->userProvider = $userProvider;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateUser(Request $request)
    {
        $user = $this->userProvider->getCurrentUser();

        if ($request->has('file')) {
            $user->setProfilePictureUrl($request->get('file'));
        }

        if ($request->has('phone_number')) {
            $user->setPhoneNumber($request->get('phone_number'));
        }

        if ($request->has('firebase_token_ios')) {

            $userFirebaseTokens = $this->userFirebaseTokensRepository->findOneBy(
                [
                    'user' => $user,
                    'type' => UserFirebaseTokens::TYPE_IOS,
                    'token' => $request->get('firebase_token_ios'),
                ]
            );

            if (!$userFirebaseTokens) {

                $userFirebaseToken = new UserFirebaseTokens();
                $userFirebaseToken->setUser($user);
                $userFirebaseToken->setBrand(config('railcontent.brand'));
                $userFirebaseToken->setToken($request->get('firebase_token_ios'));
                $userFirebaseToken->setType(UserFirebaseTokens::TYPE_IOS);
                $userFirebaseToken->setCreatedAt(Carbon::now());

                $this->entityManager->persist($userFirebaseToken);
                $this->entityManager->flush();
            }
        }

        if ($request->has('firebase_token_android')) {

            $userFirebaseTokens = $this->userFirebaseTokensRepository->findOneBy(
                [
                    'user' => $user,
                    'type' => UserFirebaseTokens::TYPE_ANDROID,
                    'token' => $request->get('firebase_token_android'),
                ]
            );

            if (!$userFirebaseTokens) {

                $userFirebaseToken = new UserFirebaseTokens();
                $userFirebaseToken->setUser($user);
                $userFirebaseToken->setBrand('drumeo');
                $userFirebaseToken->setToken($request->get('firebase_token_android'));
                $userFirebaseToken->setType(UserFirebaseTokens::TYPE_ANDROID);
                $userFirebaseToken->setCreatedAt(Carbon::now());
                $this->entityManager->persist($userFirebaseToken);
                $this->entityManager->flush();

            }
        }

        $displayName = $request->get('display_name');
        if ($displayName) {
            $inUseDisplayName = $this->userRepository->findBy(['displayName' => $displayName]);
            if ($inUseDisplayName && ($displayName != $user->getDisplayName())) {
                return response()->json(
                    [
                        'success' => false,
                        'title' => 'This display name is already in use',
                        'message' => 'Please try again',
                    ],
                    500
                );
            }

            $user->setDisplayName($displayName);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $membershipSubscription = $this->subscriptionRepository->getUserSubscriptionForProducts(
            $user->getId(),
            $this->productProvider->getMembershipProductIds(),
            true
        );

        $isAppleAppSubscriber = false;
        $isGoogleAppSubscriber = false;

        if ($membershipSubscription) {
            $isAppleAppSubscriber = $membershipSubscription->getType() == Subscription::TYPE_APPLE_SUBSCRIPTION;
            $isGoogleAppSubscriber = $membershipSubscription->getType() == Subscription::TYPE_GOOGLE_SUBSCRIPTION;
        }

        $userXP = $this->userProvider->getUserXp($user->getId());
        $xpRank = $this->userProvider->getExperienceRank($userXP);

        // the \Railroad\Usora\Services\ResponseService::userArray($user)->toArray() differs greatly from currently used response format
        $profileData = [
            'id' => $user->getId(),
            'wordpressId' => $user->getLegacyDrumeoWordpressId(),
            'ipbId' => $user->getLegacyDrumeoIpbId(),
            'email' => $user->getEmail(),
            'permission_level' => $user->getPermissionLevel(),
            'login_username' => $user->getEmail(),
            'display_name' => $user->getDisplayName(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'gender' => $user->getGender(),
            'country' => $user->getCountry(),
            'region' => $user->getRegion(),
            'city' => $user->getCity(),
            'birthday' => ($user->getBirthday()) ?
                $user->getBirthday()
                    ->toDateTimeString() : '',
            'phone_number' => $user->getPhoneNumber(),
            'bio' => $user->getBiography(),
            'created_at' => $user->getCreatedAt()
                ->toDateTimeString(),
            'updated_at' => $user->getUpdatedAt()
                ->toDateTimeString(),
            'avatarUrl' => $user->getProfilePictureUrl(),
            'totalXp' => $userXP,
            'xpRank' => $xpRank,
            'isAppleAppSubscriber' => $isAppleAppSubscriber,
            'isGoogleAppSubscriber' => $isGoogleAppSubscriber,
        ];

        return response()->json($profileData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createIntercomUser(Request $request)
    {
        $email = $request->get('email');

        if (!$email) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => 'Email is required.',
                ],
                422
            );
        }

        try {
            event(
                new AppSignupStartedEvent(
                    [
                        'email' => $email,
                    ]
                )
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => 'Intercom exception when create intercom user. Message:' . $e->getMessage(),
                ]
            );
        }

        return response()->json(
            [
                'success' => true,
            ]
        );
    }

    /**
     * @param Request $request
     * @return Fractal
     * @throws JWTException
     */
    public function getAuthUser(Request $request)
    {
        $user = $this->userRepository->findOneBy(['id' => auth()->id()]);

        $userXP = $this->userProvider->getUserXp($user->getId());
        $xpRank = $this->userProvider->getExperienceRank($userXP);

        $membershipInfo = $this->userProvider->getMembershipInfo($user->getId());

        $profileData = [
            'id' => $user->getId(),
            'wordpressId' => $user->getLegacyDrumeoWordpressId(),
            'ipbId' => $user->getLegacyDrumeoIpbId(),
            'email' => $user->getEmail(),
            'permission_level' => $user->getPermissionLevel(),
            'login_username' => $user->getEmail(),
            'display_name' => $user->getDisplayName(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'gender' => $user->getGender(),
            'country' => $user->getCountry(),
            'region' => $user->getRegion(),
            'city' => $user->getCity(),
            'birthday' => ($user->getBirthday()) ?
                $user->getBirthday()
                    ->toDateTimeString() : '',
            'phone_number' => $user->getPhoneNumber(),
            'bio' => $user->getBiography(),
            'created_at' => $user->getCreatedAt()
                ->toDateTimeString(),
            'updated_at' => $user->getUpdatedAt()
                ->toDateTimeString(),
            'avatarUrl' => $user->getProfilePictureUrl(),
            'totalXp' => $userXP,
            'xpRank' => $xpRank,
        ];

        return response()->json(array_merge($profileData, $membershipInfo));
    }
}
