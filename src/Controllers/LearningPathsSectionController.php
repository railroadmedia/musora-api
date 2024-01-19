<?php

namespace Railroad\MusoraApi\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Helpers\ButtonDataHelper;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\Railcontent\Services\ContentService;

class LearningPathsSectionController extends Controller
{
    private ProductProviderInterface $productProvider;
    private ContentService $contentService;

    /**
     * @param ProductProviderInterface $productProvider
     * @param ContentService $contentService
     */
    public function __construct(
        ProductProviderInterface $productProvider,
        ContentService $contentService
    ) {
        $this->productProvider = $productProvider;
        $this->contentService = $contentService;
    }

    public function getLearningPaths(Request $request)
    {
        $learningPaths = $this->productProvider->getLearningPaths();
        foreach ($learningPaths as $learningPath) {
            $learningPath['button'] =
                ButtonDataHelper::getButtonData($learningPath['ctaUrl'], $learningPath['ctaText']);
            $learningPath['mobile_img'] = $learningPath['mobile_img'] ?? $learningPath['desktop_img'];
            $learningPath['tablet_img'] = $learningPath['tablet_img'] ?? $learningPath['desktop_img'];
            $learningPath['type'] = $learningPath['display_order'] == 1 ? 'primary' : 'secondary';
            if ($learningPath['trailer']) {
                $vimeoId = (int)substr(parse_url($learningPath['trailer'], PHP_URL_PATH), 7);
                $trailer = $this->productProvider->getVimeoEndpoints($vimeoId);
                if ($trailer) {
                    $learningPath['trailer'] = [
                        'vimeo_video_id' => $trailer['vimeo_video_id'] ?? null,
                        'video_playback_endpoints' => $trailer['video_playback_endpoints'] ?? [],
                        'length_in_seconds' => $trailer['length_in_seconds'] ?? 0,
                    ];
                }
            }
        }

        return ResponseService::array($learningPaths);
    }

}
