<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\Railcontent\Services\ContentService;

class CohortPackController extends Controller
{
    private ProductProviderInterface $productProvider;
    private ContentService $contentService;

    /**
     * @param ProductProviderInterface $productProvider
     */
    public function __construct(
        ProductProviderInterface $productProvider,
        ContentService $contentService
    ) {
        $this->productProvider = $productProvider;
        $this->contentService = $contentService;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getTemplate(Request $request)
    {
        $data = $this->productProvider->getCohortTemplate($request->get('slug'));

        return ResponseService::cohort($data);
    }

    public function getBanner(Request $request)
    {
        $activeCohort = $this->productProvider->getActiveCohort();

        $hasProduct = $this->productProvider->userOwnProduct($activeCohort['product_id'] ?? 0);

        $cohortBanner = [];
        if ($activeCohort && $hasProduct) {
            $contentId = $activeCohort['content_id'];
            if ($contentId > 0) {
                $content = $this->contentService->getById($contentId);

                $cohortBanner = [
                    'cohort_id' => $activeCohort['id'],
                    'course_url' => ($content) ? $content->fetch('url', '') : '',
                    'light_mode_logo' => $activeCohort['light_mode_logo'],
                    'dark_mode_logo' => $activeCohort['dark_mode_logo'],
                    'continue_visible' => false,
                    'close_visible' => Carbon::parse($activeCohort['cohort_end_date']) < Carbon::now(),
                ];
                if ($content) {
                    $cohortBanner['course'] = [
                        'page_type' => 'PackOverview',
                        'page_params' => [
                            'id' => $contentId,
                            'type' => (($content['bundle_count'] ?? 0) > 1) ? 'Bundles' : 'Lesson',
                        ],
                    ];
                }
                $nextLesson = $this->contentService->getNextCohortLesson($contentId, user()->id);

                if ($nextLesson) {
                    $cohortBanner['lesson_url'] = $nextLesson->fetch('url');
                    $cohortBanner['lesson'] = [
                        'page_type' => 'Lesson',
                        'page_params' => [
                            'id' => $nextLesson->fetch('id'),
                        ],
                    ];
                    $cohortBanner['published_on'] = $nextLesson->fetch('published_on');
                    $cohortBanner['published_on_in_timezone'] = $nextLesson->fetch('published_on_in_timezone');
                    $cohortBanner['title'] = $nextLesson->fetch('title');
                    $cohortBanner['thumbnail'] = $nextLesson->fetch('data.thumbnail_url');
                    $cohortBanner['continue_visible'] = true;
                }
                if ($content['completed']) {
                    $cohortBanner['completed'] = true;
                    $cohortBanner['continue_visible'] = false;
                }
            }
        }

        return ResponseService::array($cohortBanner);
    }

}
