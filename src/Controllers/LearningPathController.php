<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\MusoraApi\Services\DownloadService;
use Railroad\MusoraApi\Services\MethodService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentHierarchyRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;

class LearningPathController extends Controller
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
     * @var VimeoVideoSourcesDecorator
     */
    private $vimeoVideoDecorator;
    /**
     * @var ContentHierarchyRepository
     */
    private $contentHierarchyRepository;
    /**
     * @var MethodService
     */
    private $methodService;
    /**
     * @var DownloadService
     */
    private $downloadService;

    /**
     * LearningPathController constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param VimeoVideoSourcesDecorator $vimeoVideoDecorator
     * @param ContentHierarchyRepository $contentHierarchyRepository
     * @param MethodService $methodService
     * @param DownloadService $downloadService
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        VimeoVideoSourcesDecorator $vimeoVideoDecorator,
        ContentHierarchyRepository $contentHierarchyRepository,
        MethodService $methodService,
        DownloadService $downloadService
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
        $this->contentHierarchyRepository = $contentHierarchyRepository;
        $this->methodService = $methodService;
        $this->downloadService = $downloadService;
    }

    /**
     * @param $learningPathSlug
     * @return array|JsonResponse
     */
    public function showLearningPath($learningPathSlug)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues = false;
        ContentRepository::$pullFutureContent = true;

        $learningPath =
            $this->contentService->getBySlugAndType($learningPathSlug, 'learning-path')
                ->first();

        if (empty($learningPath)) {
            return response()->json(
                $learningPath
            );
        }

        $learningPath['length_in_seconds'] = $learningPath->fetch('fields.video.fields.length_in_seconds');

        $this->vimeoVideoDecorator->decorate(new Collection([$learningPath]));

        $learningPath['lesson_rank'] = $learningPath->fetch('next_lesson_rank');
        $learningPath['next_lesson_url'] = $learningPath->fetch('next_lesson_url_app');
        $learningPath['xp'] = $learningPath->fetch('total_xp', $learningPath->fetch('xp', 0));
        $learningPath['banner_button_url'] = null;

        $learningPathNextLesson = $learningPath->fetch('next_lesson', []);

        if (!empty($learningPathNextLesson)) {
            $learningPath['next_lesson_url'] = url()->route(
                'mobile.learning-path.lesson.show',
                [
                    $learningPathNextLesson['id'],
                ]
            );
            $learningPath['next_lesson_id'] = $learningPathNextLesson['id'];
            $learningPath['next_lesson_title'] = $learningPathNextLesson->fetch('fields.title');
            $learningPath['next_lesson_thumbnail_url'] = $learningPathNextLesson->fetch('data.thumbnail_url', null);
            $learningPath['next_lesson_length_in_seconds'] =
                $learningPathNextLesson->fetch('fields.video.fields.length_in_seconds', null);
            $learningPath['next_lesson_is_added_to_primary_playlist'] =
                $learningPathNextLesson->fetch('is_added_to_primary_playlist', false);
            $learningPath['next_lesson_publish_date'] = $learningPathNextLesson->fetch('published_on');
            $learningPath['next_lesson_status'] = $learningPathNextLesson->fetch('status');

            if ($learningPathNextLesson->fetch('status') == ContentService::STATUS_PUBLISHED &&
                Carbon::parse($learningPathNextLesson->fetch('published_on'))
                    ->lessThanOrEqualTo(Carbon::now())) {
                $learningPath['banner_button_url'] = url()->route(
                    'mobile.learning-path.lesson.show',
                    [
                        $learningPathNextLesson['id'],
                    ]
                );
            }
        }
        $learningPath['banner_background_image'] = $learningPath->fetch('data.header_image_url', '');

        if ($learningPath['slug'] == 'pianote-method') {
            $learningPath['levels'] = $learningPath['units'];
            unset($learningPath['units']);
        }

        foreach ($learningPath['levels'] as $level) {
            $level['mobile_app_url'] = url()->route(
                'mobile.learning-path.level.show',
                [
                    $learningPath['slug'],
                    $level['slug'],
                ]
            );
        }

        return ResponseService::content($learningPath);
    }

    /**
     * @param $learningPathSlug
     * @param $levelSlug
     * @return JsonResponse
     */
    public function showLevel($learningPathSlug, $levelSlug)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues = false;
        ContentRepository::$pullFutureContent = true;

        $level =
            $this->contentService->getBySlugAndType($levelSlug, 'learning-path-level')
                ->first();

        if (empty($level)) {
            return response()->json(
                $level
            );
        }

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $learningPath =
            $this->contentService->getBySlugAndType($learningPathSlug, 'learning-path')
                ->first();

        if (empty($learningPath)) {
            return response()->json(
                $learningPath
            );
        }

        $courses = $this->contentService->getByParentId($level['id']);

        foreach ($courses as $course) {
            $course['level_rank'] = $level['level_number'] . '.' . $course['position'];
        }

        //  $this->addedToPrimaryPlaylistDecorator->decorate($courses);
        $this->vimeoVideoDecorator->decorate(new Collection([$level]));

        $level['banner_button_url'] = $level->fetch('current_lesson') ? url()->route(
            'mobile.learning-path.lesson.show',
            [
                $level->fetch('current_lesson')['id'],
            ]
        ) : '';

        $level['courses'] = $courses;

        $learningPathNextLesson =
            ($learningPath->fetch('next_lesson_level_id') == $level['id']) ? $learningPath->fetch('next_lesson', []) :
                [];

        $level['next_lesson_id'] = null;
        if (!empty($learningPathNextLesson)) {
            $level['next_lesson_url'] = url()->route(
                'mobile.learning-path.lesson.show',
                [
                    $learningPathNextLesson['id'],
                ]
            );
            $level['next_lesson_id'] = $learningPathNextLesson['id'];
            $level['next_lesson_title'] = $learningPathNextLesson->fetch('fields.title');
            $level['next_lesson_thumbnail_url'] = $learningPathNextLesson->fetch('data.thumbnail_url', null);
            $level['next_lesson_length_in_seconds'] =
                $learningPathNextLesson->fetch('fields.video.fields.length_in_seconds', null);
            $level['next_lesson_is_added_to_primary_playlist'] =
                $learningPathNextLesson->fetch('is_added_to_primary_playlist', false);
            $level['next_lesson_publish_date'] = $learningPathNextLesson->fetch('published_on', '');
            $level['next_lesson_status'] = $learningPathNextLesson->fetch('status');
        }

        $level['level_rank'] = $learningPath->fetch('level_rank');
        $level['lesson_rank'] = $learningPath->fetch('next_lesson_rank');

        $level['progress_percent'] = $level['user_progress'][auth()->id()]['progress_percent'] ?? 0;

        $level['xp'] = $level->fetch('total_xp');

        $level['banner_background_image'] = $learningPath->fetch('data.header_image_url', '');

        return ResponseService::content($level);
    }

    /**
     * @param $courseId
     * @param Request $request
     * @return array|JsonResponse
     * @throws NonUniqueResultException
     */
    public function showCourse($courseId, Request $request)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues = false;
        ContentRepository::$pullFutureContent = true;

        $course = $this->contentService->getById($courseId);

        if (empty($course)) {
            return response()->json(
                $course
            );
        }

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $level =
            $this->contentService->getByChildIdWhereParentTypeIn($courseId, ['learning-path-level'])
                ->first();

        $levelCourses =
            $this->contentService->getByParentId($level['id'])
                ->keyBy('id');

        if (empty($level)) {
            return response()->json(
                $level
            );
        }

        $learningPath =
            $this->contentService->getByChildIdWhereParentTypeIn($level['id'], ['learning-path'])
                ->first();

        if (empty($learningPath)) {
            return response()->json(
                $learningPath
            );
        }

        $lessons = $this->contentService->getByParentId($courseId);

        $course['lessons'] = $lessons;

        $course['banner_button_url'] = $course->fetch('current_lesson') ? url()->route(
            'mobile.learning-path.lesson.show',
            [
                $course->fetch('current_lesson')['id'],
            ]
        ) : '';

        $course['banner_background_image'] = $learningPath->fetch('data.header_image_url', '');

        $learningPathNextLesson =
            ($learningPath->fetch('next_lesson_course_id') == $courseId) ? $learningPath->fetch('next_lesson', []) : [];

        $course['next_lesson_id'] = null;
        if (!empty($learningPathNextLesson) && ($learningPathNextLesson['sort'] != 0)) {
            $course['next_lesson_url'] = url()->route(
                'mobile.learning-path.lesson.show',
                [
                    $learningPathNextLesson['id'],
                ]
            );
            $course['next_lesson_id'] = $learningPathNextLesson['id'];
            $course['next_lesson_title'] = $learningPathNextLesson->fetch('fields.title');
            $course['next_lesson_thumbnail_url'] = $learningPathNextLesson->fetch('data.thumbnail_url', null);
            $course['next_lesson_length_in_seconds'] =
                $learningPathNextLesson->fetch('fields.video.fields.length_in_seconds', null);
            $course['next_lesson_is_added_to_primary_playlist'] =
                $learningPathNextLesson->fetch('is_added_to_primary_playlist', false);
            $course['next_lesson_publish_date'] = $learningPathNextLesson->fetch('published_on', '');
            $course['next_lesson_status'] = $learningPathNextLesson->fetch('status');
        }

        $course['level_rank'] = $learningPath->fetch('level_rank');
        $course['level_position'] = $level['sort'] + 1;
        $course['lesson_rank'] = ($course->fetch('current_lesson', [])['sort'] ?? 0) + 1;
        $course['xp'] = $course->fetch('total_xp');
        $course['course_position'] = $levelCourses[$course['id']]['position'];

        if ($request->has('download')) {
            $this->downloadService->attachLessonsDataForDownload($course);

            return ResponseService::contentForDownload($course);
        }

        return ResponseService::content($course);
    }

    /**
     * @param $lessonId
     * @return array|JsonResponse
     * @throws NonUniqueResultException
     */
    public function showLesson($lessonId)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues = false;
        ContentRepository::$pullFutureContent = true;

        $thisLesson = $this->contentService->getById($lessonId);

        if (empty($thisLesson)) {
            return response()->json($thisLesson);
        }

        $course =
            $this->contentService->getByChildIdWhereParentTypeIn($lessonId, ['learning-path-course'])
                ->first();

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        if (empty($course)) {
            return response()->json($course);
        }

        $level =
            $this->contentService->getByChildIdWhereParentTypeIn($course['id'], ['learning-path-level'])
                ->first();
        if (empty($level)) {
            return response()->json($level);
        }

        $learningPath =
            $this->contentService->getByChildIdWhereParentTypeIn($level['id'], ['learning-path'])
                ->first();

        if (empty($learningPath)) {
            return response()->json($learningPath);
        }

        $courseHierarchy = $this->contentHierarchyRepository->getByChildIdParentId($level['id'], $course['id']);

        $relatedLessons = $this->contentService->getByParentId($course['id']);

        $thisLesson['related_lessons'] = $relatedLessons;
        $thisLesson['level_rank'] = $learningPath->fetch('level_rank');
        $thisLesson['level_position'] = $level['sort'] + 1;
        $thisLesson['course_position'] = $courseHierarchy['child_position'];
        $thisLesson['xp'] = $thisLesson->fetch('total_xp');

        $nextPrevLessons = $this->methodService->getNextAndPreviousLessons($lessonId, $learningPath['id']);

        $prevLesson = $nextPrevLessons->getPreviousLesson();
        $nextLesson = $nextPrevLessons->getNextLesson();

        $thisLesson['prev_lesson_url'] = ($prevLesson) ? url()->route(
            'mobile.learning-path.lesson.show',
            [
                $prevLesson['id'],
            ]
        ) : null;
        $thisLesson['prev_lesson_id'] = $prevLesson['id'] ?? null;
        $thisLesson['next_lesson_url'] = ($nextLesson) ? url()->route(
            'mobile.learning-path.lesson.show',
            [
                $nextLesson['id'],
            ]
        ) : null;

        $thisLesson['next_lesson_id'] = $nextLesson['id'] ?? null;

        if ($nextLesson) {
            $thisLesson['next_lesson_title'] = $nextLesson->fetch('fields.title') ?? '';
            $thisLesson['next_lesson_thumbnail_url'] = $nextLesson->fetch('data.thumbnail_url') ?? '';
            $thisLesson['next_lesson_length_in_seconds'] =
                $nextLesson->fetch('fields.video.fields.length_in_seconds') ?? '';
            $thisLesson['next_lesson_is_added_to_primary_playlist'] =
                $nextLesson->fetch('is_added_to_primary_playlist', false);
        }

        //check if is last incomplete lesson from course or last incomplete course from level
        $levelCourses = $this->contentService->getByParentId($level['id']);
        $thisLesson['is_last_incomplete_lesson_from_course'] =
            $thisLesson['related_lessons']->where('completed', '=', false)
                ->count() <= 1;
        $thisLesson['is_last_incomplete_course_from_level'] =
            $levelCourses->where('completed', '=', false)
                ->count() <= 1;

        if ($thisLesson['is_last_incomplete_lesson_from_course'] &&
            !$thisLesson['is_last_incomplete_course_from_level']) {
            $nextCourse =
                $levelCourses->where('id', '!=', $course['id'])
                    ->where('completed', '=', false)
                    ->first();
            $thisLesson['current_course_title'] = $course->fetch('fields.title') ?? '';
            $thisLesson['current_course_xp'] = $course->fetch('total_xp');
            $thisLesson['current_course_thumbnail_url'] = $course->fetch('data.thumbnail_url') ?? '';
            $thisLesson['next_course_id'] = $nextCourse->fetch('id') ?? null;
            $thisLesson['next_course_title'] = $nextCourse->fetch('fields.title') ?? '';
            $thisLesson['next_course_thumbnail_url'] = $nextCourse->fetch('data.thumbnail_url') ?? '';
            $thisLesson['next_course_is_added_to_primary_playlist'] =
                $nextCourse->fetch('is_added_to_primary_playlist', false);
            $thisLesson['next_course_lesson_count'] = $nextCourse->fetch('lesson_count');
            $thisLesson['next_course_mobile_app_url'] = $nextCourse->fetch('mobile_app_url');
            $thisLesson['next_course_level_rank'] = $thisLesson['level_position'] . '.' . $nextCourse['sort'];

        } else {
            if ($thisLesson['is_last_incomplete_course_from_level']) {
                $allLevels = $this->contentService->getByParentId($learningPath['id']);
                $nextLevel =
                    $allLevels->where('id', '!=', $level['id'])
                        ->where('completed', '=', false)
                        ->first();
                $thisLesson['current_level_title'] = $level->fetch('fields.title') ?? '';
                $thisLesson['current_level_xp'] = $level->fetch('total_xp');
                $thisLesson['current_level_number'] = $level['position'];
                $thisLesson['current_level_thumbnail_url'] = $level->fetch('data.thumbnail_url') ?? '';
                $thisLesson['next_level_id'] = $nextLevel->fetch('id') ?? null;
                $thisLesson['next_level_title'] = $nextLevel->fetch('fields.title') ?? '';
                $thisLesson['next_level_thumbnail_url'] = $nextLevel->fetch('data.thumbnail_url') ?? '';
                $thisLesson['next_level_is_added_to_primary_playlist'] =
                    $nextLevel->fetch('is_added_to_primary_playlist', false);
                $thisLesson['next_level_mobile_app_url'] = $nextLevel['mobile_app_url'];
                $thisLesson['next_level_number'] = $nextLevel['position'];
                $thisLesson['next_level_published_on'] = $nextLevel->fetch('published_on');
            }
        }

        $this->vimeoVideoDecorator->decorate(new Collection([$thisLesson]));

        CommentRepository::$availableContentId = $thisLesson['id'];

        $commentPage = 1;

        $comments = $this->commentService->getComments($commentPage, 10, '-created_on');

        $thisLesson['comments'] = (new CommentTransformer())->transform($comments['results']);
        $thisLesson['total_comments'] = $comments['total_results'];

        if (!empty($thisLesson['resources']) || !empty($course['resources'])) {
            $thisLesson['resources'] = array_merge($thisLesson['resources'] ?? [], $course['resources'] ?? []);
        }

        return ResponseService::content($thisLesson);
    }

}
