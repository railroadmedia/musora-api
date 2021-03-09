<?php

namespace Railroad\MusoraApi\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Ecommerce\Events\AppSignupStartedEvent;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Requests\CreateIntercomUserRequest;

class AuthController extends Controller
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * AuthController constructor.
     *
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        UserProviderInterface $userProvider
    ) {
        $this->userProvider = $userProvider;
    }

    /**
     * @param Request $request
     * @return JsonResponse
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
            $this->userProvider->setCurrentUserFirebaseTokens(
                $request->get('firebase_token_ios'),
                $request->get('firebase_token_android')
            );
        }

        $membershipData = $this->userProvider->getCurrentUserMembershipData();
        $profileData = $this->userProvider->getCurrentUserProfileData();
        $experienceData = $this->userProvider->getCurrentUserExperienceData();

        return response()->json(array_merge($profileData, $experienceData, $membershipData));

    }

    /**
     * @param CreateIntercomUserRequest $request
     * @return JsonResponse
     */
    public function createIntercomUser(CreateIntercomUserRequest $request)
    {
        try {
            event(
                new AppSignupStartedEvent(
                    [
                        'email' => $request->get('email'),
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
     * @return JsonResponse
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
