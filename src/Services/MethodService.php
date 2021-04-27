<?php

namespace Railroad\MusoraApi\Services;

use Illuminate\Database\DatabaseManager;
use Railroad\MusoraApi\Entities\NextPreviousContentVO;
use Railroad\Railcontent\Services\ContentService;

class MethodService
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var array
     */
    private $userCache = [];

    /**
     * @var array
     */
    private $contentCache = [];

    /**
     * DrumeoMethodService constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param ContentService $contentService
     */
    public function __construct(DatabaseManager $databaseManager, ContentService $contentService)
    {
        $this->databaseManager = $databaseManager;
        $this->contentService = $contentService;
    }

    /**
     * @param $userId
     *
     * @return bool
     */
    public function hasProgress($userId)
    {
        if (isset($this->userCache[$userId]['hasProgress'])) {
            return $this->userCache[$userId]['hasProgress'];
        }

        $hasProgress =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content')
                ->join(
                    'railcontent_user_content_progress',
                    'railcontent_user_content_progress.content_id',
                    '=',
                    'railcontent_content.id'
                )
                ->where('railcontent_user_content_progress.user_id', '=', $userId)
                ->where('railcontent_content.slug', 'drumeo-method')
                ->where('railcontent_content.type', 'learning-path')
                ->exists();

        $this->userCache[$userId]['hasProgress'] = $hasProgress;

        return $hasProgress;
    }

    /**
     * @param $lessonId
     * @param $drumeoMethodLearningPathId
     *
     * @return NextPreviousContentVO
     */
    public function getNextAndPreviousLessons($lessonId, $drumeoMethodLearningPathId)
    {
        $levelHierarchyData =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content_hierarchy')
                ->where('railcontent_content_hierarchy.parent_id', $drumeoMethodLearningPathId)
                ->orderBy('child_position', 'asc')
                ->get(['parent_id', 'child_id', 'child_position']);

        $courseHierarchyData =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content_hierarchy')
                ->whereIn(
                    'railcontent_content_hierarchy.parent_id',
                    $levelHierarchyData->pluck('child_id')
                        ->toArray()
                )
                ->orderBy('child_position', 'asc')
                ->get(['parent_id', 'child_id', 'child_position']);

        $courseHierarchyDataGrouped = $courseHierarchyData->groupBy('parent_id');

        $lessonHierarchyData =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content_hierarchy')
                ->whereIn(
                    'railcontent_content_hierarchy.parent_id',
                    $courseHierarchyData->pluck('child_id')
                        ->toArray()
                )
                ->orderBy('child_position', 'asc')
                ->get(['parent_id', 'child_id', 'child_position'])
                ->groupBy('parent_id');

        // make array of all the lessons in the order of hierarchy
        $lessonIds = [];

        foreach ($levelHierarchyData as $levelHierarchy) {
            foreach ($courseHierarchyDataGrouped[$levelHierarchy->child_id] ?? [] as $courseHierarchy) {
                foreach ($lessonHierarchyData[$courseHierarchy->child_id] ?? [] as $lessonHierarchy) {
                    $lessonIds[] = $lessonHierarchy->child_id;
                }
            }
        }

        $previousLesson = null;
        $nextLesson = null;

        foreach ($lessonIds as $lessonIdIndex => $allLessonId) {
            if ($allLessonId == $lessonId) {
                if (isset($lessonIds[$lessonIdIndex - 1])) {
                    $previousLesson = $this->contentService->getById($lessonIds[$lessonIdIndex - 1]);
                }
                if (isset($lessonIds[$lessonIdIndex + 1])) {
                    $nextLesson = $this->contentService->getById($lessonIds[$lessonIdIndex + 1]);
                }
            }
        }

        return new NextPreviousContentVO($nextLesson, $previousLesson);
    }

    /**
     *
     */
    public function getLearningPathNextLesson($currentLevelId = null, $currentCourseId = null)
    {
        $learningPath =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content')
                ->where('railcontent_content.slug', 'drumeo-method')
                ->where('railcontent_content.type', 'learning-path')
                ->first();

        $learningPathProgress =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content')
                ->join(
                    'railcontent_user_content_progress',
                    'railcontent_user_content_progress.content_id',
                    '=',
                    'railcontent_content.id'
                )
                ->where('railcontent_user_content_progress.user_id', '=', auth()->id())
                ->where('railcontent_content.slug', 'drumeo-method')
                ->where('railcontent_content.type', 'learning-path')
                ->first();

        $levelRank = $learningPathProgress ? $learningPathProgress->higher_key_progress : '1.0';

        $userHigherKeyProgress = explode('.', $levelRank);

        $currentLevelIndex = $userHigherKeyProgress[0];
        $currentCourseIndex = $userHigherKeyProgress[1] + 1;

        $levelHierarchyData =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content_hierarchy')
                ->where('railcontent_content_hierarchy.parent_id', $learningPathProgress->content_id ?? $learningPath->id)
                ->where('railcontent_content_hierarchy.child_position', $currentLevelIndex)
                ->select('child_id as content_id')
                ->first();

        $levelId = $levelHierarchyData->content_id;
        if (($currentLevelId) && ($currentLevelId != $levelId)) {
            return null;
        }

        $courseHierarchyData =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content_hierarchy')
                ->where('railcontent_content_hierarchy.parent_id', $levelId)
                ->where('railcontent_content_hierarchy.child_position', $currentCourseIndex)
                ->select('child_id as content_id')
                ->first();

        $courseId = $courseHierarchyData->content_id;
        if (($currentCourseId) && ($currentCourseId != $courseId)) {
            return null;
        }

        $lessonHierarchyDataRows =
            $this->databaseManager->connection(config('railcontent.database_connection_name'))
                ->table('railcontent_content_hierarchy')
                ->leftJoin(
                    'railcontent_user_content_progress',
                    function ($join) {
                        $join->on(
                            'railcontent_user_content_progress.content_id',
                            '=',
                            'railcontent_content_hierarchy.child_id'
                        )
                            ->where('user_id', '=', auth()->id());
                    }
                )
                ->where('railcontent_content_hierarchy.parent_id', $courseId)
                ->orderBy('child_position', 'asc')
                ->get();

        $nextLessonId = null;
        foreach ($lessonHierarchyDataRows as $lessonHierarchyData) {

            if ($lessonHierarchyData->state !=
                \Railroad\Railcontent\Services\UserContentProgressService::STATE_COMPLETED &&
                $lessonHierarchyData->progress_percent != 100) {
                $nextLessonId = $lessonHierarchyData->child_id;
                break;
            }
        }

        $nextLesson = ($nextLessonId) ? $this->contentService->getById($nextLessonId) : null;

        return $nextLesson;
    }
}
