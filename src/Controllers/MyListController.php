<?php

namespace Railroad\MusoraApi\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\ConfigService;
use Railroad\Railcontent\Services\ContentService;

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
     * MyListController constructor.
     *
     * @param ContentService $contentService
     * @param ContentRepository $contentRepository
     */
    public function __construct(
        ContentService $contentService,
        ContentRepository $contentRepository
    ) {
        $this->contentService = $contentService;
        $this->contentRepository = $contentRepository;
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
            array_values(config('railcontent.showTypes', []))
        );

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $contentTypes = $request->get('included_types', $contentTypes);
        $requiredFields = $request->get('required_fields', []);
        $includedFields = $request->get('included_fields', []);

        if (!$state) {

            $usersPrimaryPlaylist = array_first(
                $this->contentRepository->getByUserIdTypeSlug(auth()->id(), 'user-playlist', 'primary-playlist')
            );

            if (empty($usersPrimaryPlaylist)) {
                return (new ContentFilterResultsEntity(
                    [
                        'results' => [],
                    ]
                ))->toJsonResponse();
            }

            $lessons = $this->contentService->getByParentIdWhereTypeInPaginated(
                $usersPrimaryPlaylist['id'],
                $contentTypes,
                $request->get('limit', 20),
                ($request->get('page', 1) - 1) * $request->get('limit', 20)
            );

            $totalResults = $this->contentService->countByParentIdWhereTypeIn(
                $usersPrimaryPlaylist['id'],
                $contentTypes
            );

            $lessons = new ContentFilterResultsEntity(['results' => $lessons->toArray(), 'total_results' => $totalResults]);
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
}
