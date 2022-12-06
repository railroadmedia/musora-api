<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Decorators\StripTagDecorator;
use Railroad\Railcontent\Decorators\Video\ContentVimeoVideoDecorator;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Services\DownloadService;
use Railroad\MusoraApi\Services\MethodService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
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
     * @var ContentVimeoVideoDecorator
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
     * @var StripTagDecorator
     */
    private $stripTagDecorator;

    /**
     * LearningPathController constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param ContentVimeoVideoDecorator $vimeoVideoDecorator
     * @param ContentHierarchyRepository $contentHierarchyRepository
     * @param MethodService $methodService
     * @param DownloadService $downloadService
     * @param StripTagDecorator $stripTagDecorator
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        ContentVimeoVideoDecorator $vimeoVideoDecorator,
        ContentHierarchyRepository $contentHierarchyRepository,
        MethodService $methodService,
        DownloadService $downloadService,
        StripTagDecorator $stripTagDecorator
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
        $this->contentHierarchyRepository = $contentHierarchyRepository;
        $this->methodService = $methodService;
        $this->downloadService = $downloadService;
        $this->stripTagDecorator = $stripTagDecorator;
    }

    /**
     * @param $learningPathSlug
     * @return array|JsonResponse
     */
    public function showLearningPath($learningPathSlug)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$pullFutureContent = true;
        ContentRepository::$availableContentStatues =
            [ContentService::STATUS_PUBLISHED, ContentService::STATUS_ARCHIVED];

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

        $learningPath =
            $this->contentService->getBySlugAndType($learningPathSlug, 'learning-path')
                ->first();

        throw_if(!($learningPath), new NotFoundException('Learning path not found.'));

        $this->vimeoVideoDecorator->decorate(new Collection([$learningPath]));

        $learningPath['xp'] = $learningPath->fetch('total_xp', $learningPath->fetch('xp', 0));
        $learningPath['banner_button_url'] = null;

        $learningPathNextLesson = $learningPath->fetch('current_lesson', []);
        if (!empty($learningPathNextLesson) &&
            ($learningPathNextLesson->fetch('status') == ContentService::STATUS_PUBLISHED &&
                Carbon::parse($learningPathNextLesson->fetch('published_on'))
                    ->lessThanOrEqualTo(Carbon::now()))) {
            if ($learningPath['slug'] != 'foundations-2019') {
                $learningPath['banner_button_url'] = url()->route('mobile.musora-api.learning-path.lesson.show', [
                    $learningPathNextLesson['id'],
                ]);
            } else {
                $learningPath['banner_button_url'] = url()->route('mobile.musora-api.learning-paths.unit-part.show', [
                    $learningPathNextLesson['id'],
                ]);
            }
        }

        $learningPath['banner_background_image'] = $learningPath->fetch('data.header_image_url', '');
        if ($learningPath['slug'] == 'foundations-2019') {
            $learningPath['units'] = $this->contentService->getByParentIdWhereTypeIn($learningPath['id'], ['unit']);
        } else {
            $learningPath['levels'] = $this->contentService->getByParentIdWhereTypeIn($learningPath['id'], [
                config(
                    'railcontent.content_hierarchy'
                )[$learningPath['brand']][$learningPath['type']],
            ]);
        }

        $levels = $learningPath['units'] ?? $learningPath['levels'];

        foreach ($levels ?? [] as $level) {
            $level['mobile_app_url'] = url()->route('mobile.musora-api.learning-path.level.show', [
                $learningPath['slug'],
                $level['slug'],
            ]);
        }

        if (($learningPath['slug'] == 'pianote-method') && (isset($learningPath['units']))) {
            $learningPath['levels'] = $learningPath['units'];
            unset($learningPath['units']);
        }

        return ResponseService::content($learningPath);
    }

    /**
     * @param $learningPathSlug
     * @param $levelSlug
     * @return JsonResponse
     */
    public function showLevel($learningPathSlug, $levelSlug, Request $request)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues =
            [ContentService::STATUS_PUBLISHED, ContentService::STATUS_ARCHIVED];
        ContentRepository::$pullFutureContent = true;

        $level =
            $this->contentService->getBySlugAndType($levelSlug, 'learning-path-level')
                ->first();

        if (!$level) {
            $level =
                $this->contentService->getBySlugAndType($levelSlug, 'unit')
                    ->first();
        }

        throw_if(!$level, new NotFoundException('Level not found.'));

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

        $learningPath =
            $this->contentService->getBySlugAndType($learningPathSlug, 'learning-path')
                ->first();

        throw_if(!$learningPath, new NotFoundException('Learning path not found.'));

        $courses = $this->contentService->getByParentId($level['id']);
        if ($level['type'] == 'learning-path-level') {
            foreach ($courses as $course) {
                $course['level_rank'] = $level['level_number'].'.'.$course['position'];
            }
            $level['courses'] = $courses;
        } else {
            $totalLengthInSeconds = 0;
            $totalXp = 0;

            foreach ($level['lessons'] ?? $courses as $lesson) {
                $totalLengthInSeconds += $lesson->fetch('fields.video.fields.length_in_seconds', 0);
                $totalXp += $lesson->fetch('total_xp', $lesson->fetch('fields.xp', 0));
                $lesson['mobile_app_url'] = url()->route('mobile.musora-api.learning-paths.unit-part.show', [
                    $lesson['id'],
                ]);
            }

            $level['total_length_in_seconds'] = $totalLengthInSeconds;
            $level['total_xp'] = $totalXp;
        }
        if ($level['type'] == 'unit') {
            $level['next_lesson'] =
                ($learningPath->fetch('current_lesson') &&
                    $learningPath['current_lesson']['parent_id'] == $level['id']) ?
                    $learningPath->fetch('next_lesson', null) : null;
        } else {
            $level['next_lesson'] =
                ($learningPath->fetch('next_lesson_level_id') == $level['id']) ?
                    $learningPath->fetch('next_lesson', null) : null;
        }

        $level['xp'] = $level->fetch('total_xp');

        $level['banner_background_image'] = $learningPath->fetch('data.header_image_url', '');

        $this->vimeoVideoDecorator->decorate(new Collection([$level]));

        if ($request->has('download')) {
            $this->downloadService->attachLessonsDataForDownload($level);

            return ResponseService::contentForDownload($level);
        }

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
        ContentRepository::$availableContentStatues =
            [ContentService::STATUS_PUBLISHED, ContentService::STATUS_ARCHIVED];
        ContentRepository::$pullFutureContent = true;

        $course = $this->contentService->getById($courseId);
        throw_if(!$course, new NotFoundException('Course not found.'));

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

        $level =
            $this->contentService->getByChildIdWhereParentTypeIn($courseId, ['learning-path-level'])
                ->first();
        throw_if(!$level, new NotFoundException('Level not found.'));

        $learningPath =
            $this->contentService->getByChildIdWhereParentTypeIn($level['id'], ['learning-path'])
                ->first();
        throw_if(!$learningPath, new NotFoundException('Learning path not found.'));

        $lessons = $this->contentService->getByParentId($courseId);

        $course['lessons'] = $lessons;

        $course['banner_background_image'] = $learningPath->fetch('data.header_image_url', '');

        $course['next_lesson'] =
            ($learningPath->fetch('next_lesson_course_id') == $courseId) ? $learningPath->fetch('next_lesson', null) :
                null;

        $course['level_position'] = $level['level_number'];

        $levelCourses =
            $this->contentService->getByParentId($level['id'])
                ->keyBy('id');

        $course['course_position'] = $levelCourses[$course['id']]['position'];

        $course['xp'] = $course->fetch('total_xp');

        if ($request->has('download')) {
            $this->downloadService->attachLessonsDataForDownload($course);

            return ResponseService::contentForDownload($course);
        }

        return ResponseService::content($course);
    }

    /**
     * @param $lessonId
     * @return array
     * @throws NonUniqueResultException
     * @throws \Throwable
     */
    public function showLesson($lessonId)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues = false;
        ContentRepository::$pullFutureContent = true;

        $thisLesson = $this->contentService->getById($lessonId);
        throw_if(!$thisLesson, new NotFoundException('Lesson not found.'));

        $course =
            $this->contentService->getByChildIdWhereParentTypeIn($lessonId, ['learning-path-course'])
                ->first();
        throw_if(!$course, new NotFoundException('Course not found.'));

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

        $level =
            $this->contentService->getByChildIdWhereParentTypeIn($course['id'], ['learning-path-level'])
                ->first();
        throw_if(!$level, new NotFoundException('Level not found.'));

        $learningPath =
            $this->contentService->getByChildIdWhereParentTypeIn($level['id'], ['learning-path'])
                ->first();
        throw_if(!$learningPath, new NotFoundException('Learning path not found.'));

        $courseHierarchy = $this->contentHierarchyRepository->getByChildIdParentId($level['id'], $course['id']);

        $relatedLessons = $this->contentService->getByParentId($course['id']);

        $thisLesson['related_lessons'] = $relatedLessons;
        $thisLesson['level_position'] = $level['sort'] + 1;
        $thisLesson['course_position'] = $courseHierarchy['child_position'];
        $thisLesson['xp'] = $thisLesson->fetch('total_xp');

        $nextPrevLessons = $this->methodService->getNextAndPreviousLessons($lessonId, $learningPath['id']);
        $thisLesson['prev_lesson'] = $thisLesson['previous_lesson'] = $nextPrevLessons->getPreviousLesson();
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
            $nextCourse['level_rank'] = $thisLesson['level_position'].'.'.$nextCourse['position'];
            $thisLesson['next_course'] = $nextCourse;
        } else {
            if ($thisLesson['is_last_incomplete_course_from_level']) {
                $allLevels = $this->contentService->getByParentId($learningPath['id']);

                $nextLevel =
                    $allLevels->where('id', '!=', $level['id'])
                        ->where('completed', '=', false)
                        ->first();

                $thisLesson['current_level'] = $level;

                if ($nextLevel) {
                    $nextLevel['level_number'] = $nextLevel['position'];
                    $nextLevel['mobile_app_url'] = url()->route('mobile.musora-api.learning-path.level.show', [
                        $learningPath['slug'],
                        $nextLevel['slug'],
                    ]);
                    $thisLesson['next_level'] = $nextLevel;
                }
            }
        }

        $thisLesson['instructor'] = $level->fetch('*fields.instructor');

        $this->stripTagDecorator->decorate(new Collection([$thisLesson]));
        $this->vimeoVideoDecorator->decorate(new Collection([$thisLesson]));

        //add comments
        CommentRepository::$availableContentId = $thisLesson['id'];
        $commentPage = 1;
        $comments = $this->commentService->getComments($commentPage, 10, '-created_on');
        $thisLesson['comments'] = (new CommentTransformer())->transform($comments['results']);
        $thisLesson['total_comments'] = $comments['total_comments_and_results'];

        //add course's resources to lesson
        if (!empty($thisLesson['resources']) || !empty($course['resources'])) {
            $thisLesson['resources'] = array_merge($thisLesson['resources'] ?? [], $course['resources'] ?? []);
        }

        return ResponseService::content($thisLesson);
    }

    /**
     * @param $unitPartId
     * @return array|JsonResponse
     * @throws NonUniqueResultException
     */
    public function showUnitPart($unitPartId)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues = false;
        ContentRepository::$pullFutureContent = true;

        $thisLesson = $this->contentService->getById($unitPartId);

        if (empty($thisLesson)) {
            return response()->json($thisLesson);
        }

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

        $unit =
            $this->contentService->getByChildIdWhereParentTypeIn($unitPartId, ['unit'])
                ->first();

        if (empty($unit)) {
            return response()->json($unit);
        }

        $learningPath =
            $this->contentService->getByChildIdWhereParentTypeIn($unit['id'], ['learning-path'])
                ->first();

        if (empty($learningPath)) {
            return response()->json($learningPath);
        }

        $unitLessons = $this->contentService->getByParentId($unit['id']);

        $isLastIncompleteUnitPart = true;
        foreach ($unitLessons as $index => $lesson) {
            if (($lesson->fetch('completed', false) == false) && ($lesson['id'] != $thisLesson['id'])) {
                $isLastIncompleteUnitPart = false;
            }
            if ($lesson['id'] == $thisLesson['id']) {
                $nextChild = ($index < count($unitLessons) - 1) ? $unitLessons[$index + 1] : null;
                $previousChild = ($index > 0) ? $unitLessons[$index - 1] : null;
            }
        }

        $thisLesson['next_lesson'] = $nextChild ?? null;
        $thisLesson['previous_lesson'] = $previousChild ?? null;

        if ($isLastIncompleteUnitPart) {
            $learningPathUnits = $this->contentService->getByParentId($learningPath['id']);
            foreach ($learningPathUnits as $index => $learningPathUnit) {
                if (($learningPathUnit['id'] == $unit['id']) && (array_key_exists($index + 1, $learningPathUnits))) {
                    $thisLesson['next_unit'] = $learningPathUnits[$index + 1];
                }
            }
        }

        $thisLesson['related_lessons'] = $unitLessons;

        $thisLesson['xp'] = $thisLesson->fetch('total_xp');

        $thisLesson['instructors'] = $unit->fetch('*fields.instructor');

        $this->vimeoVideoDecorator->decorate(new Collection([$thisLesson]));

        CommentRepository::$availableContentId = $thisLesson['id'];
        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $thisLesson['comments'] = $this->stripTagDecorator->decorate(new Collection($comments['results']));

        $thisLesson['total_comments'] = $comments['total_comments_and_results'];

        $this->stripTagDecorator->decorate(new Collection($thisLesson['comments']));
        $this->stripTagDecorator->decorate(new Collection([$thisLesson]));

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

        if (!empty($thisLesson['resources']) || !empty($unit['resources'])) {
            $thisLesson['resources'] = array_merge($thisLesson['resources'] ?? [], $unit['resources'] ?? []);
        }

        return ResponseService::content($thisLesson);
    }
}
