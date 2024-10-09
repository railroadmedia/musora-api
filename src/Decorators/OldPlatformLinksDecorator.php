<?php

namespace Railroad\MusoraApi\Decorators;

use Railroad\Railcontent\Entities\CommentEntity;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;

class OldPlatformLinksDecorator extends \Railroad\Railcontent\Decorators\ModeDecoratorBase
{
    const HTML_HREF_REGEX_PATTERN = '#<a[^>]+href=\"(.*?)\"[^>]*>#';
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
        if (self::$decorationMode !== self::DECORATION_MODE_MAXIMUM) {
            return $entities;
        }
        $initialDecorationMode = self::$decorationMode;
        self::$decorationMode = self::DECORATION_MODE_MINIMUM;
        foreach ($entities as $entityIndex => $entity) {
            $showTypes = [];
            if  (isset($entity['brand'])){
                $showTypes = config('railcontent.showTypes')[$entity['brand']] ?? [];
            }
            if ($entity instanceof ContentEntity) {
                $contentData = $entity['data'] ?? [];
                foreach ($contentData as $index => $data) {
                    $isLessonOrAssignment = in_array(
                        $entity['type'],
                        array_merge(
                            config('railcontent.singularContentTypes', []),
                            $showTypes,
                            ['assignment']
                        )
                    );

                    if (in_array($data['key'], ['description']) && $isLessonOrAssignment) {
                        $data['value'] = $this->mapForumToNewUrl($data['value']);
                        if (preg_match_all(self::HTML_HREF_REGEX_PATTERN, $data['value'], $matches)) {
                            $urls = $this->getUrls($matches[1]);
                            foreach ($urls as $oldUrl => $mwpUrl) {
                                $entities[$entityIndex]['data'][$index]['value'] =
                                    str_replace($oldUrl, $mwpUrl, $entities[$entityIndex]['data'][$index]['value']);
                            }
                        }
                    }
                }
            }

            if ($entity instanceof CommentEntity) {
                $comment = $entity['comment'] ?? '';
                $comment = $this->mapForumToNewUrl($comment);
                if (preg_match_all(self::HTML_HREF_REGEX_PATTERN, $comment, $matches)) {
                    $urls = $this->getUrls($matches[1]);
                    foreach ($urls as $oldUrl => $mwpUrl) {
                        $entities[$entityIndex]['comment'] =
                            str_replace($oldUrl, $mwpUrl, $entities[$entityIndex]['comment']);
                    }
                }

                $replies = $entity['replies'] ?? [];
                foreach ($replies as $index => $reply) {
                    $reply['comment'] = $this->mapForumToNewUrl($reply['comment']);
                    if (preg_match_all(self::HTML_HREF_REGEX_PATTERN, $reply['comment'], $matches)) {
                        $urls = $this->getUrls($matches[1]);
                        foreach ($urls as $oldUrl => $mwpUrl) {
                            $entities[$entityIndex]['replies'][$index]['comment'] =
                                str_replace($oldUrl, $mwpUrl, $entities[$entityIndex]['replies'][$index]['comment']);
                        }
                    }
                }
            }
        }

        self::$decorationMode = $initialDecorationMode;

        return $entities;
    }

    public function formatNewUrl(
        $segments,
        $url
    ) {
        ContentRepository::$bypassPermissions = true;
        $unifiedUrl = '/'.$segments[0].'/';
        if (isset($segments[0]) &&
            ($segments[0] == 'drumshop' ||
                $segments[0] == 'lifetime' ||
                $segments[0] == 'beat' ||
                $segments[0] == 'recitals' ||
                $segments[0] == 'guitar-technique-made-easy-discount')) {
            return $url;
        }

        if (!isset($segments[1]) || $segments[1] == null) {
            return $unifiedUrl;
        }
        $lastSegment = last($segments);
        $numberOfSegments = count($segments);

        if (in_array($lastSegment, [
            'lessons',
            'routines',
            'courses',
            'songs',
            'quick-tips',
            'podcasts',
            'question-and-answer',
            'student-reviews',
            'boot-camps',
            'chords-and-scales',
            'bootcamps',
            'chords-scales',
            'library',
            'recording',
            'play-alongs',
            'archives',
            'spotlight',
            'the-history-of-electronic-drums',
            'backstage-secrets',
            'student-collaborations',
            'live-streams',
            'solos',
            'boot-amps',
            'gear-guides',
            'performances',
            'in-rhythm',
            'challenges',
            'on-the-road',
            'diy-drum-experiments',
            'rhythmic-adventures-of-captain-carson',
            'study-the-greats',
            'rhythms-from-another-planet',
            'tama-drums',
            'paiste-cymbals',
            'behind-the-scenes',
            'exploring-beats',
            'sonor-drums',
            'rudiments',
            'boot-camps',
            'support',
        ])) {
            $url = str_replace(
                [
                    'www.drumeo.com/members/lessons',
                    'www.pianote.com/members',
                    'www.singeo.com/members',
                    'www.guitareo.com',
                    'www.drumeo.com/laravel/public/members',
                ],
                [
                    'www.musora.com/drumeo',
                    'www.musora.com/pianote',
                    'www.musora.com/singeo',
                    'www.musora.com/guitareo',
                    'www.musora.com/drumeo',
                ],
                $url
            );

            return $url;
        }

        if (in_array('forums', $segments)) {
            $url = str_replace(
                [
                    'www.drumeo.com/members/lessons',
                    'www.drumeo.com/laravel/public/members',
                    'www.drumeo.com/members',
                    'www.pianote.com/members',
                    'www.singeo.com/members',
                    'www.singeo.com',
                    'www.guitareo.com',
                    'loops',
                ],
                [
                    'www.musora.com/drumeo',
                    'www.musora.com/drumeo',
                    'www.musora.com/drumeo',
                    'www.musora.com/pianote',
                    'www.musora.com/singeo',
                    'www.musora.com/singeo',
                    'www.musora.com/guitareo',
                    'legacy-resources/loops',
                ],
                $url
            );

            return $url;
        } elseif (is_numeric($lastSegment)) {
            $content = $this->contentService->getById($lastSegment);

            return $content['url'] ?? '';
        } elseif ($numberOfSegments == 3 && $segments[1] == 'packs') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'pack')
                    ->first();

            return $content['url'];
        } elseif ($numberOfSegments == 3 && $segments[1] == 'semester-packs') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'semester-pack')
                    ->first();

            return $content['url'];
        } elseif ($numberOfSegments == 3 && $segments[1] == 'coaches') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'instructor')
                    ->first();

            return $content['url'];
        } elseif ($numberOfSegments == 3 && $segments[1] == 'learning-paths') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'learning-path')
                    ->first();

            return $content['url'];
        } elseif ($numberOfSegments == 4 && $segments[1] == 'packs') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'pack-bundle')
                    ->first();

            return $content['url'];
        } elseif ($numberOfSegments == 5 && $segments[1] == 'packs') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'pack-bundle-lesson')
                    ->first();

            return $content['url'];
        } elseif ($numberOfSegments == 4 && $segments[1] == 'semester-packs') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'semester-pack-lesson')
                    ->first();

            return $content['url'];
        } elseif ($numberOfSegments == 4 && $segments[1] == 'learning-paths') {
            $content =
                $this->contentService->getBySlugAndType($lastSegment, 'learning-path-level')
                    ->first();

            return $content['url'];
        } elseif ($lastSegment == 'loops') {
            $url = str_replace(
                [
                    'www.drumeo.com/members/lessons',
                    'www.drumeo.com/laravel/public/members',
                    'www.pianote.com/members',
                    'www.singeo.com',
                    'www.guitareo.com',
                    'loops',
                ],
                [
                    'www.musora.com/drumeo',
                    'www.musora.com/drumeo',
                    'www.musora.com/pianote',
                    'www.musora.com/singeo',
                    'www.musora.com/guitareo',
                    'legacy-resources/loops',
                ],
                $url
            );

            return $url;
        }

        return $unifiedUrl;
    }

    private function mapForumToNewUrl(string $source): string
    {
        return str_replace(
            [
                '"/members/forums',
                '"/members/',
                '"/pianote/forums',
                'forums.drumeo.com/index.php?',
            ],
            [
                '"'.route('forums.show-categories'),
                '"'.config('app.url').'/'.brand().'/',
                '"'.route('forums.show-categories'),
                route('forums.show-categories'),
            ],
            $source
        );
    }

    /**
     * @param $matches
     * @return array
     */
    private function getUrls($matches)
    : array {
        $urls = [];
        foreach ($matches as $match) {
            $url = $match;
            if(!filter_var($url, FILTER_VALIDATE_URL)){
                continue;
            }

            // we only need to format the URL if it's one of our domains
            $parsedUrl = parse_url($url);
            $host = $parsedUrl['host'] ?? null;
            if (!in_array($host, [
                    'www.drumeo.com',
                    'www.pianote.com',
                    'www.singeo.com',
                    'www.guitareo.com',
                    'forums.drumeo.com',
                ])) {
                continue;
            }

            $oldRequest = \Request::create($url);
            if (!empty($oldRequest->segments())) {
                $segments = $this->formatNewUrl($oldRequest->segments(), $url);
                if ($oldRequest->getQueryString()) {
                    $segments = $segments.'?'.$oldRequest->getQueryString();
                }
                $urls[$match] = $segments;
            }
        }

        return $urls;
    }
}
