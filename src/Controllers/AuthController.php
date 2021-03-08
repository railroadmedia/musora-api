<?php

namespace Railroad\MusoraApi\Controllers;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Ecommerce\Events\AppSignupStartedEvent;
use Railroad\Ecommerce\Repositories\SubscriptionRepository;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
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
        UserProviderInterface $userProvider
    ) {
        $this->entityManager = $entityManager;
        $this->jwtAuth = $jwtAuth;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
        $this->userFirebaseTokensRepository = $userFirebaseTokensRepository;
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
        if ($request->has('file')) {
            $this->userProvider->setCurrentUserProfilePictureUrl($request->get('file'));
        }

        if ($request->has('phone_number')) {
            $this->userProvider->setCurrentUserPhoneNumber($request->get('phone_number'));
        }

        if ($request->has('display_name')) {
            $updatedUser = $this->userProvider->setCurrentUserDisplayName($request->get('display_name'));
            if (!$updatedUser) {
                return response()->json(
                    [
                        'success' => false,
                        'title' => 'This display name is already in use',
                        'message' => 'Please try again',
                    ],
                    500
                );
            }
        }

        if ($request->has('firebase_token_ios') || $request->has('firebase_token_android')) {
            $this->userProvider->setCurrentUserFirebaseTokens($request->get('firebase_token_ios'), $request->get('firebase_token_android'));
        }

        $membershipData = $this->userProvider->getCurrentUserMembershipData();
        $profileData = $this->userProvider->getCurrentUserProfileData();
        $experienceData = $this->userProvider->getCurrentUserExperienceData();

        return response()->json(array_merge($profileData, $experienceData, $membershipData));

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
        } catch (Exception $e) {
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
    public function getAuthUser()
    {
        $user = $this->userProvider->getCurrentUser();

        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => 'Login Required.',
                ],
                401
            );
        }

        $membershipData = $this->userProvider->getCurrentUserMembershipData();
        $profileData = $this->userProvider->getCurrentUserProfileData();
        $experienceData = $this->userProvider->getCurrentUserExperienceData();

        return response()->json(array_merge($profileData, $experienceData, $membershipData));
    }
}
