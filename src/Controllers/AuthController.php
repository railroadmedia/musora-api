<?php

namespace Railroad\MusoraApi\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Ecommerce\Events\AppSignupStartedEvent;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Exceptions\MusoraAPIException;
use Railroad\MusoraApi\Exceptions\UnauthorizedException;
use Railroad\MusoraApi\Requests\CreateIntercomUserRequest;
use Railroad\MusoraApi\Services\ResponseService;
use Throwable;

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
     * @return array
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
            $this->userProvider->setCurrentUserDisplayName($request->get('display_name'));
        }

        if ($request->has('avatar_url')) {
            $this->userProvider->setCurrentUserProfilePictureUrl($request->get('avatar_url'));
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

        return ResponseService::userData(array_merge($profileData, $experienceData, $membershipData));
    }

    /**
     * @param CreateIntercomUserRequest $request
     * @return JsonResponse
     * @throws MusoraAPIException
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
            throw new MusoraAPIException('Intercom exception when create intercom user. Message:' . $e->getMessage(), 'Intercom exception.', $e->getCode());
        }

        return ResponseService::array([
            'success' => true,
        ]);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function getAuthUser(Request $request)
    {
        $user = $this->userProvider->getCurrentUser();
        throw_if(!$user, new UnauthorizedException('Login Required.'));

        $membershipData = $this->userProvider->getCurrentUserMembershipData($request->get('app'));
        $profileData = $this->userProvider->getCurrentUserProfileData($request->get('app'));
        $experienceData = $this->userProvider->getCurrentUserExperienceData();

        return ResponseService::userData(array_merge($profileData, $experienceData, $membershipData));
    }

    /**
     * @return JsonResponse
     */
    public function deleteAccount()
    {
        $this->userProvider->deleteAccount();

        return ResponseService::array([
                                          'success' => true,
                                      ]);
    }

    /**
     * @return mixed
     */
    public function getTempToken()
    {
        return $this->userProvider->getAuthKey();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserAfterRevenuecatPurchase(Request $request)
    {
        $email = $request->get('email');
        $pass = $request->get('password');
        $revenuecatOriginalAppUserId = $request->get('original_app_user_id');
        $user = $this->userProvider->getUserAfterRevenuecatPurchase($email, $pass, $revenuecatOriginalAppUserId);
        if (!$user) {
            return ResponseService::array(['error' => 'User not found']);
        }
        $token = $user->createToken('ios');
        $user->withAccessToken($token);

        return ResponseService::array(['token' => $token->plainTextToken, 'user' => $user]);
    }
}
