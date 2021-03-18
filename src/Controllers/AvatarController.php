<?php

namespace Railroad\MusoraApi\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Railroad\MusoraApi\Requests\UploadAvatarRequest;
use Railroad\Railcontent\Controllers\RemoteStorageJsonController;
use Railroad\Railcontent\Services\RemoteStorageService;

class AvatarController extends RemoteStorageJsonController
{
    /**
     * @var RemoteStorageService
     */
    private $remoteStorageService;
    /**
     * @var ImageManager
     */
    private $imageManager;

    public function __construct(
        RemoteStorageService $remoteStorageService,
        ImageManager $imageManager
    ) {
        parent::__construct($remoteStorageService);

        $this->remoteStorageService = $remoteStorageService;
        $this->imageManager = $imageManager;
    }

    public function put(UploadAvatarRequest $request)
    {
        $image = $this->imageManager->make($request->file('file'));

        $image->orientate()
            ->interlace()
            ->encode('jpg', 90)
            ->save();

        $target = 'avatars/' . pathinfo($request->get('target'))['filename'] . '-' . time() . '-' . auth()->id() . '.jpg';

        $request->attributes->set('target', $target);

        return parent::put($request);
    }
}
