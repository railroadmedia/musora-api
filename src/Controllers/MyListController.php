<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\ConfigService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Services\UserPlaylistsService;

class MyListController extends Controller
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var ContentRepository
     */
    private $contentRepository;

    /**
     * @var UserPlaylistsService
     */
    private $userPlaylistsService;

    /**
     * MyListController constructor.
     *
     * @param ContentService $contentService
     * @param ContentRepository $contentRepository
     */
    public function __construct(
        ContentService $contentService,
        ContentRepository $contentRepository,
        UserPlaylistsService $userPlaylistsService
    ) {
        $this->contentService = $contentService;
        $this->contentRepository = $contentRepository;
        $this->userPlaylistsService = $userPlaylistsService;
    }

    /** Pull my list content
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMyLists(Request $request)
    {
        ContentRepository::$availableContentStatues =
            [ContentService::STATUS_PUBLISHED, ContentService::STATUS_ARCHIVED, ContentService::STATUS_SCHEDULED];

        ContentRepository::$pullFutureContent = true;

        $state = $request->get('state');

        $oldFieldOptions = ConfigService::$fieldOptionList;
        ConfigService::$fieldOptionList=[
            'instructor',
            'topic',
            'difficulty',
            'style'];

        $contentTypes = array_merge(
            config('railcontent.appUserListContentTypes', []),
            array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? [])
        );

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $contentTypes = $request->get('included_types', $contentTypes);
        $requiredFields = $request->get('required_fields', []);
        $includedFields = $request->get('included_fields', []);

        if (!$state) {
            $userPrimaryPlaylist = $this->userPlaylistsService->getUserPlaylist(auth()->id(), 'primary-playlist',  $request->get('brand') ?? config('railcontent.brand'));
            if (empty($userPrimaryPlaylist)) {
                return (new ContentFilterResultsEntity([
                                                           'results' => [],
                                                       ]))->toJsonResponse();
            }

            $userPrimaryPlaylistId = $userPrimaryPlaylist[0]['id'];
            $lessons = new ContentFilterResultsEntity([
                                                          'results' => $this->userPlaylistsService->getUserPlaylistContents($userPrimaryPlaylistId, $contentTypes,$limit, $page),
                                                          'total_results' => $this->userPlaylistsService->countUserPlaylistContents($userPrimaryPlaylistId),
                                                      ]);
        } else {
            $contentTypes = array_diff($contentTypes, ['course-part']);

            $lessons = $this->contentService->getFiltered(
                $page,
                $limit,
                $request->get('sort', '-progress'),
                $contentTypes,
                [],
                [],
                $requiredFields,
                $includedFields,
                $request->get('required_user_states', [$state]),
                $request->get('included_user_states', [])
            );
        }

        ConfigService::$fieldOptionList = $oldFieldOptions;

        return ResponseService::list($lessons, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getPlaylistLessons(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', null);

        $contentTypes = array_merge(
            config('railcontent.appUserListContentTypes', []),
            array_values(config('railcontent.showTypes', [])[config('railcontent.brand')] ?? [])
        );

        $lessons = new ContentFilterResultsEntity([
                                                      'results' => $this->userPlaylistsService->getUserPlaylistContents($request->get('playlist_id'), $contentTypes,$limit, $page),
                                                      'total_results' => $this->userPlaylistsService->countUserPlaylistContents($request->get('playlist_id')),
                                                  ]);

        $request->merge(['limit' => $limit]);

        return ResponseService::list($lessons, $request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePlaylistContent(Request $request){

        $this->userPlaylistsService->changePlaylistContent(
            $request->get('playlist_id'),
            $request->get('content_id'),
            $request->get('position'),
            $request->get('extra_data'),
            $request->get('start_second'),
            $request->get('end_second')
        );

        return $this->getPlaylistLessons($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItemToPlaylist(Request $request)
    {
        $this->userPlaylistsService->addItemToPlaylist(
            $request->get('playlist_id'),
            $request->get('content_id'),
            $request->get('position'),
            $request->get('extra_data'),
            $request->get('start_second'),
            $request->get('end_second'),
            $request->get('import_all_assignments', false)
        );

        return $this->getPlaylistLessons($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function removeItemFromPlaylist(Request $request)
    {
        $deleted = $this->userPlaylistsService->removeItemFromPlaylist($request->get('user_playlist_item_id'));

        return ResponseService::array([
                                          'success' => ($deleted > 0),
                                      ]);
    }
}
