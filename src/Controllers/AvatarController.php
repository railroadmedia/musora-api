<?php

namespace Railroad\MusoraApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Services\ResponseService;

class AvatarController extends Controller
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @param ImageManager $imageManager
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        ImageManager $imageManager,
        UserProviderInterface $userProvider
    ) {
        $this->imageManager = $imageManager;
        $this->userProvider = $userProvider;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function put(Request $request)
    {
        throw_if(!$request->file('file'), new NotFoundException('File not found.'));

        $image = $this->imageManager->make($request->file('file'));

        $image
            ->interlace()
            ->encode('jpg', 75)
            ->save();

        $target = 'profile_picture_url/' . pathinfo($request->get('target'))['filename'] . '-' . time() . '-' . auth()->id() . '.jpg';

        $success = Storage::disk('musora_web_platform_s3')->put($target, $request->file('file')->getContent());

        if ($success) {
            $this->userProvider->setCurrentUserProfilePictureUrl( config('filesystems.disks.musora_web_platform_s3.cloudfront_access_url').$target);

            $membershipData = $this->userProvider->getCurrentUserMembershipData();
            $profileData = $this->userProvider->getCurrentUserProfileData();
            $experienceData = $this->userProvider->getCurrentUserExperienceData();
            return ResponseService::userData(array_merge($profileData, $experienceData, $membershipData));
        }

        return response()->json(['error' => 'Failed to upload avatar.'], 400);
    }
}
