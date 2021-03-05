<?php

namespace Railroad\MusoraApi\Services;

use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;

class DownloadService{
    /**
     * @var ContentService
     */
    private $contentService;
    /**
     * @var CommentService
     */
    private $commentService;
    /**
     * @var VimeoVideoSourcesDecorator
     */
    private $vimeoVideoDecorator;

    /**
     * DownloadService constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param VimeoVideoSourcesDecorator $vimeoVideoDecorator
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        VimeoVideoSourcesDecorator $vimeoVideoDecorator
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
    }

    /**
     * Add extra data for download: lessons assignments, vimeo urls, comments, resources
     * @param $content
     *
     * @return array
     */
    public function attachLessonsDataForDownload($content)
    {
        //add video_playback_endpoints for each lesson
        $this->vimeoVideoDecorator->decorate(new Collection($content['lessons']));

        //attach comments for each lesson
        $this->commentService->attachCommentsToContents($content['lessons']);

        //add related lessons, next/prev lesson, resources on each lesson
        foreach ($content['lessons'] as $lessonIndex => $lesson) {
            $parentChildren = $this->getParentChildTrimmed($content['lessons'], $lesson);

            $content['lessons'][$lessonIndex]['related_lessons'] = $parentChildren;
            $content['lessons'][$lessonIndex]['previous_lesson'] = $parentChildren[$lessonIndex - 1] ?? null;
            $content['lessons'][$lessonIndex]['next_lesson'] = $parentChildren[$lessonIndex + 1] ?? null;

            $content['lessons'][$lessonIndex]['resources'] = array_merge($lesson['resources'] ?? [], $parent['resources'] ?? []);
        }

        return $content;
    }

    /**
     * @param $parentChildren
     * @param $content
     * @return array
     */
    private function getParentChildTrimmed($parentChildren, $content)
    : array {
        $parentChildrenTrimmed = [];

        foreach ($parentChildren as $parentChildIndex => $parentChild) {
            if (((count($parentChildren) - $parentChildIndex) <= 10 && count($parentChildrenTrimmed) < 10) &&
                ($parentChild['id'] != $content['id'])) {
                $parentChildrenTrimmed[] = $parentChild;
            }
        }

        return $parentChildrenTrimmed;
    }
}
