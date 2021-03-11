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

        $this->vimeoVideoDecorator->decorate(new Collection([$learningPath]));

        $learningPath['xp'] = $learningPath->fetch('total_xp', $learningPath->fetch('xp', 0));
        $learningPath['banner_button_url'] = null;

        $learningPathNextLesson = $learningPath->fetch('next_lesson', []);
        if (!empty($learningPathNextLesson) &&
            ($learningPathNextLesson->fetch('status') == ContentService::STATUS_PUBLISHED &&
                Carbon::parse($learningPathNextLesson->fetch('published_on'))
                    ->lessThanOrEqualTo(Carbon::now()))) {
            $learningPath['banner_button_url'] = url()->route(
                'mobile.learning-path.lesson.show',
                [
                    $learningPathNextLesson['id'],
                ]
            );
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

        $this->vimeoVideoDecorator->decorate(new Collection([$level]));

        $level['banner_button_url'] = $level->fetch('current_lesson') ? url()->route(
            'mobile.learning-path.lesson.show',
            [
                $level->fetch('current_lesson')['id'],
            ]
        ) : '';

        $level['courses'] = $courses;

        $level['next_lesson'] =
            ($learningPath->fetch('next_lesson_level_id') == $level['id']) ? $learningPath->fetch('next_lesson', null) :
                null;


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

        if ($learningPathNextLesson) {
            $course['next_lesson'] = $learningPathNextLesson;
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
        $thisLesson['prev_lesson'] = $nextPrevLessons->getPreviousLesson();
        $thisLesson['next_lesson'] = $nextPrevLessons->getNextLesson();

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
            $thisLesson['current_course'] = $course;
            $nextCourse['level_rank'] = $thisLesson['level_position'] . '.' . $nextCourse['position'];
            $thisLesson['next_course'] = $nextCourse;
        } else {
            if ($thisLesson['is_last_incomplete_course_from_level']) {
                $allLevels = $this->contentService->getByParentId($learningPath['id']);
                $nextLevel =
                    $allLevels->where('id', '!=', $level['id'])
                        ->where('completed', '=', false)
                        ->first();
                $level['level_number'] = $level['position'];
                $thisLesson['current_level'] = $level;
                $nextLevel['level_number'] = $nextLevel['position'];
                $nextLevel['mobile_app_url'] = url()->route(
                    'mobile.learning-path.level.show',
                    [
                        $learningPath['slug'],
                        $nextLevel['slug'],
                    ]
                );
                $thisLesson['next_level'] = $nextLevel;
            }
        }

        $this->vimeoVideoDecorator->decorate(new Collection([$thisLesson]));

        //add comments
        CommentRepository::$availableContentId = $thisLesson['id'];
        $commentPage = 1;
        $comments = $this->commentService->getComments($commentPage, 10, '-created_on');
        $thisLesson['comments'] = (new CommentTransformer())->transform($comments['results']);
        $thisLesson['total_comments'] = $comments['total_results'];

        //add course's resources to lesson
        if (!empty($thisLesson['resources']) || !empty($course['resources'])) {
            $thisLesson['resources'] = array_merge($thisLesson['resources'] ?? [], $course['resources'] ?? []);
        }

        return ResponseService::content($thisLesson);
    }
}
