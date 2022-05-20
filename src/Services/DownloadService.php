<?php

namespace Railroad\MusoraApi\Services;


use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\Video\ContentVimeoVideoDecorator;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;

class DownloadService
{
    /**
     * @var ContentService
     */
    private $contentService;
    /**
     * @var CommentService
     */
    private $commentService;
    /**
     * @var ContentVimeoVideoDecorator
     */
    private $vimeoVideoDecorator;

    /**
     * DownloadService constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param ContentVimeoVideoDecorator $vimeoVideoDecorator
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        ContentVimeoVideoDecorator $vimeoVideoDecorator
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
    }

    /**
     * Add extra data for download: lessons assignments, vimeo urls, comments, resources
     *
     * @param $content
     *
     * @return array
     */
    public function attachLessonsDataForDownload($content)
    {
        //add video_playback_endpoints for each lesson
        $this->vimeoVideoDecorator->decorate(new Collection($content['lessons']));

        //add related lessons, next/prev lesson, resources on each lesson
        foreach ($content['lessons'] as $lessonIndex => $lesson) {
            $parentChildren = $this->getParentChildTrimmed($content['lessons'], $lesson);

            $content['lessons'][$lessonIndex]['related_lessons'] = $parentChildren;
            $content['lessons'][$lessonIndex]['previous_lesson'] = $content['lessons'][$lessonIndex - 1] ?? null;
            $content['lessons'][$lessonIndex]['next_lesson'] = $content['lessons'][$lessonIndex + 1] ?? null;

            $content['lessons'][$lessonIndex]['resources'] =
                array_merge($lesson['resources'] ?? [], $parent['resources'] ?? []);

            if ($content['type'] == 'learning-path-course') {
                $content['lessons'][$lessonIndex]['course_position'] = $content['course_position'] ?? '0';
                $content['lessons'][$lessonIndex]['level_position'] = $content['level_position'] ?? '1';
            }

            //attach comments for each lesson
            CommentRepository::$availableContentId = $lesson['id'];

            // limit only 10 comments for each lesson in offline mode to avoid mobile app freezing when downloading avatars
            $comments = $this->commentService->getComments(1, 10, '-created_on');
            $content['lessons'][$lessonIndex]['comments'] = (new CommentTransformer())->transform($comments['results']);
            $content['lessons'][$lessonIndex]['total_comments'] = 10;
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
