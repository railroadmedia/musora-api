<?php

namespace Railroad\MusoraApi\Decorators;

use App\Decorators\Content\TypeDecoratorBase;
use Railroad\Railcontent\Entities\CommentEntity;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;

class OldPlatformLinksDecorator extends \Railroad\Railcontent\Decorators\ModeDecoratorBase
{
    const HTML_HREF_REGEX_PATTERN = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
private $contentService;

    /**
     * @param $contentService
     */
    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function decorate(Collection $entities)
    {
        foreach ($entities as $entityIndex => $entity) {
            if ($entity instanceof ContentEntity) {
                $contentData = $entity['data'] ?? [];
                foreach ($contentData as $index => $data) {
                    $isLesson = in_array(
                        $entity['type'],
                        array_merge(
                            config('railcontent.singularContentTypes', []),
                            config('railcontent.showTypes')[$entity['brand']] ?? []
                        )
                    );

                    if (in_array($data['key'], ['description']) && $isLesson) {
                        if (preg_match_all(self::HTML_HREF_REGEX_PATTERN, $data['value'], $matches)) {
                            $urls = [];
                            foreach ($matches[0] as $match) {

                                $url = $match;
                                $initialRequest = \Request::create($url);
                                if(!in_array($initialRequest->getHttpHost(),['www.drumeo.com', 'www.pianote.com', 'www.singeo.com', 'www.guitareo.com'])){
                                    continue;
                                }
                                $url = str_replace(
                                    ['www.drumeo.com', 'www.pianote.com/members', 'www.singeo.com', 'www.guitareo.com'],
                                    [
                                        'www.musora.com/drumeo',
                                        'www.musora.com/pianote',
                                        'www.musora.com/singeo',
                                        'www.musora.com/guitareo',
                                    ],
                                    $url
                                );
                                $oldRequest = \Request::create($url);
                                $segments = $this->formatNewUrl(...$oldRequest->segments());

                                $newUrl = 'https://'.$oldRequest->getHttpHost().$segments;

                                $urls[$match] = $newUrl;
                            }
//var_dump($data['content_id']);
//                            var_dump($urls);
                            foreach ($urls as $oldUrl => $mwpUrl) {
                                $entities[$entityIndex]['data'][$index]['value'] =
                                    str_replace($oldUrl, $mwpUrl, $entities[$entityIndex]['data'][$index]['value']);
                            }
                        }
                    }
                }
            }
        }

        return $entities;
    }

    public function formatNewUrl(
        $segment1 = null,
        $segment2 = null,
        $segment3 = null,
        $segment4 = null,
        $segment5 = null,
        $segment6 = null,
        $segment7 = null,
        $segment8 = null,
        $segment9 = null,
    ) {
        ContentRepository::$bypassPermissions = true;
        $unifiedUrl = '/'.$segment1.'/';

        if ($segment2 == null) {
            return '';
        }
        if (in_array($segment2, [
            'forums',
            'referral',
            'support',
            'live',
            'schedule',
            'legacy-resources',
            'search',
            'courses',
            'songs',
            'quick-tips',
            'student-reviews',
            'question-and-answer',
            'podcasts',
            'boot-camps',
        ])) {
            $segment2 = ($segment2 == 'boot-camps') ? "bootcamps" : $segment2;

            /* here we treat all the routes that are similar */
            $unifiedUrl = $unifiedUrl.$segment2."/";
            foreach ([$segment3, $segment4, $segment5, $segment6] as $segment) {
                if ($segment) {
                    $unifiedUrl = $unifiedUrl.$segment."/";
                } else {
                    break;
                }
            }

            return $unifiedUrl;
        }
        if ($segment3 == null) {
            if (in_array($segment2, ['packs', 'coaches', 'student-focus'])) {
                $unifiedUrl = $unifiedUrl.$segment2;
            }
            if ($segment2 == 'chat') {
                $unifiedUrl = $unifiedUrl.'live-chat';
            }

            return $unifiedUrl;
        }

        if ($segment4 == null) {
            if ($segment2 == 'coaches') {
                $content =
                    $this->contentService->getBySlugAndType($segment3, 'instructor')
                        ->first();
                if ($content) {
                    $unifiedUrl = $unifiedUrl.'coaches/'.$segment3.'/'.$content['id'];
                }
            } elseif ($segment2 == 'lessons') {
                if (in_array($segment3, ['all', 'subscribed'])) {
                    $unifiedUrl = $unifiedUrl.'lessons/'.$segment3;
                } else {
                    $unifiedUrl = $unifiedUrl.$segment3;
                }
            } elseif ($segment2 == 'semester-packs') {
                $content =
                    $this->contentService->getBySlugAndType($segment3, 'semester-pack')
                        ->first();
                if ($content) {
                    $unifiedUrl = $unifiedUrl.'semester-packs/'.$segment3.'/'.$content['id'];
                }
            } elseif ($segment2 == 'packs') {
                $content =
                    $this->contentService->getBySlugAndType($segment3, 'pack')
                        ->first();
                if ($content) {
                    $unifiedUrl = $unifiedUrl.'packs/'.$segment3.'/'.$content['id'];
                }
            } elseif ($segment2 == 'learning-paths') {
                $content =
                    $this->contentService->getBySlugAndType($segment3, 'learning-path')
                        ->first();
                if ($content) {
                    $unifiedUrl = $unifiedUrl.'method/'.$segment3.'/'.$content['id'];
                }
            } elseif ($segment2 == 'profile') {
                if ($segment3 == 'notifications') {
                    $unifiedUrl = $unifiedUrl.$segment3;
                } elseif ($segment3 == 'lists') {
                    $unifiedUrl = $unifiedUrl.'lists/my-list';
                } elseif (is_numeric($segment3)) {
                    $unifiedUrl = $unifiedUrl.'profile/'.$segment3.'/dashboard';
                }
            }

            return $unifiedUrl;
        }

        if ($segment5 == null && $segment2 == 'learning-paths') {
            $learningPath =
                $this->contentService->getBySlugAndType($segment3, 'learning-path')
                    ->first();
            $learningPathLevel =
                $this->contentService->getBySlugAndType($segment4, 'learning-path-level')
                    ->first();

            if(!$learningPathLevel){
                $learningPathLevel =
                    $this->contentService->getBySlugAndType($segment4, 'unit')
                        ->first();
            }
            if ($learningPath && $learningPathLevel) {
                $unifiedUrl =
                    $unifiedUrl.'method/'.$segment3."/".$learningPath['id'].'/'.$segment4."/".$learningPathLevel['id'];
            }

            return $unifiedUrl;
        }

        if ($segment6 == null) {
            if ($segment2 == 'packs') {
                $pack =
                    $this->contentService->getBySlugAndType($segment3, 'pack')
                        ->first();
                $packBundle =
                    $this->contentService->getBySlugAndType($segment3, 'pack-bundle')
                        ->first();
                if ($pack && $packBundle) {
                    $unifiedUrl =
                        $unifiedUrl.
                        "packs/".
                        $segment3.
                        "/".
                        $pack['id'].
                        "/".
                        $segment3.
                        "/".
                        $packBundle['id'].
                        "/".
                        $segment4.
                        "/".
                        $segment5;
                }
            }

            return $unifiedUrl;
        }

        if ($segment7 == null) {
            if ($segment2 == 'learning-paths') {
                $learningPath =
                    $this->contentService->getBySlugAndType($segment3, 'learning-path')
                        ->first();
                $learningPathLevel =
                    $this->contentService->getBySlugAndType($segment4, 'learning-path-level')
                        ->first();
                if(!$learningPathLevel){
                    $learningPathLevel =
                        $this->contentService->getBySlugAndType($segment4, 'unit')
                            ->first();
                }
                if ($learningPath && $learningPathLevel) {
                    $unifiedUrl =
                        $unifiedUrl.
                        'method/'.
                        $segment3.
                        "/".
                        $learningPath['id'].
                        '/'.
                        $segment4.
                        "/".
                        $learningPathLevel['id'].
                        "/".
                        $segment5.
                        "/".
                        $segment6;
                }
            }

            return $unifiedUrl;
        }

        if ($segment9 == null) {
            if ($segment2 == 'learning-paths') {
                $learningPath = $this->contentService->getBySlugAndType($segment3, 'learning-path')->first();
                $learningPathLevel = $this->contentService->getBySlugAndType($segment4, 'learning-path-level')->first();
                if ($learningPath && $learningPathLevel) {
                    $unifiedUrl = $unifiedUrl . 'method/' . $segment3 . "/" . $learningPath['id'] . '/' .
                        $segment4 . "/" . $learningPathLevel['id'] . "/" . $segment5 . "/" .
                        $segment6 . "/" . $segment7 . "/" . $segment8;
                }
            }

            return $unifiedUrl;
        }

        return $unifiedUrl;
    }
}
