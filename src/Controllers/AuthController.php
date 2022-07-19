<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
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
    public function getAuthUser()
    {
        $user = $this->userProvider->getCurrentUser();
        throw_if(!$user, new UnauthorizedException('Login Required.'));

        $membershipData = $this->userProvider->getCurrentUserMembershipData();
        $profileData = $this->userProvider->getCurrentUserProfileData();
        $experienceData = $this->userProvider->getCurrentUserExperienceData();

        return ResponseService::userData(array_merge($profileData, $experienceData, $membershipData));
    }

    public function deleteAccount(Request $request)
    {
        $newAttributes = [
            'email' => 'musora+deleted_'.Carbon::now()->getTimestamp().'@musora.com',
            'first_name' => null,
            'last_name' => null,
            'display_name' => null,
            'gender' => null,
            'country' => null,
            'region' => null,
            'city' => null,
            'birthday' => null,
            'phone_number' => null,
            'profile_picture_url' => null,
            'timezone' => null,
            'permission_level' => null,
            'drums_gear_photo' => null,
            'biography' => null,
            'piano_gear_photo' => null,
            'drums_gear_set_brands' => null,
            'drums_gear_hardware_brands' => null,
            'drums_gear_stick_brands' => null,
            'drums_gear_cymbal_brands' => null,
            'drums_playing_since_year' => null,
            'piano_gear_piano_brands' => null,
            'piano_gear_keyboard_brands' => null,
            'piano_playing_since_year' => null,

        ];

        $this->userProvider->deleteAccount(auth()->id(), $newAttributes);

        return ResponseService::array([
                                          'success' => true,
                                      ]);
    }
}
