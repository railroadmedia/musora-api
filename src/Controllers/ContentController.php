<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Mailora\Services\MailService;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Decorators\StripTagDecorator;
use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\MusoraApi\Requests\SubmitQuestionRequest;
use Railroad\MusoraApi\Requests\SubmitStudentFocusFormRequest;
use Railroad\MusoraApi\Requests\SubmitVideoRequest;
use Railroad\MusoraApi\Services\DownloadService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentHierarchyRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ConfigService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Services\FullTextSearchService;
use Railroad\Railcontent\Support\Collection;

class ContentController extends Controller
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
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var MailService
     */
    private $mailoraMailService;

    /**
     * @var ContentHierarchyRepository
     */
    private $contentHierarchyRepository;

    /**
     * @var FullTextSearchService
     */
    private $fullTextSearchService;

    /**
     * @var StripTagDecorator
     */
    private $stripTagDecorator;

    /**
     * @var DownloadService
     */
    private $downloadService;

    /**
     * ContentController constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param VimeoVideoSourcesDecorator $vimeoVideoDecorator
     * @param UserProviderInterface $userProvider
     * @param MailService $mailoraMailService
     * @param ContentHierarchyRepository $contentHierarchyRepository
     * @param FullTextSearchService $fullTextSearchService
     * @param StripTagDecorator $stripTagDecorator
     * @param DownloadService $downloadService
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        VimeoVideoSourcesDecorator $vimeoVideoDecorator,
        UserProviderInterface $userProvider,
        MailService $mailoraMailService,
        ContentHierarchyRepository $contentHierarchyRepository,
        FullTextSearchService $fullTextSearchService,
        StripTagDecorator $stripTagDecorator,
        DownloadService $downloadService
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
        $this->userProvider = $userProvider;
        $this->mailoraMailService = $mailoraMailService;
        $this->contentHierarchyRepository = $contentHierarchyRepository;
        $this->fullTextSearchService = $fullTextSearchService;
        $this->stripTagDecorator = $stripTagDecorator;
        $this->downloadService = $downloadService;
    }

    /**
     * @param $contentId
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getContent($contentId, Request $request)
    {
        $content = $this->contentService->getById($contentId);

        if (!$content) {
            return response()->json();
        }

        //get content's parent for related lessons and resources
        $parent = array_first(
            $this->contentService->getByChildIdWhereParentTypeIn(
                $contentId,
                ['course', 'song', 'learning-path', 'pack', 'pack-bundle']
            )
        );

        $parentChildren = $parent['lessons'] ?? $this->contentService->getFiltered(
                $request->get('page', 1),
                $request->get('limit', 10),
                '-published_on',
                [$content['type']],
                [],
                [],
                [],
                [],
                [],
                [],
                false,
                false,
                false
            )['results'];

        $parentChildrenTrimmed = $this->getParentChildTrimmed($parentChildren, $content);
        $content['related_lessons'] = $parentChildrenTrimmed;

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;
        $content = $this->attachNextPrevLesson($parent, $content, $parentChildren);

        //attached comments on the content
        CommentRepository::$availableContentId = $content['id'];
        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $content['comments'] = (new CommentTransformer())->transform($comments['results']);
        $content['total_comments'] = $comments['total_results'];

        if (!array_key_exists('lessons', $content) &&
            !in_array($content['type'], config('railcontent.singularContentTypes', []))) {
                $content['lessons'] = $this->contentService->getByParentId($content['id']);
        }

        if ($content['type'] == 'coach') {
            $requestRequiredFields = $request->get('required_fields', []);
            $requiredFields = array_merge($requestRequiredFields, ['instructor,' . $content['id']]);
            $includedFields = $request->get('included_fields', []);
            $requiredUserState = $request->get('required_user_states', []);
            $includedUserState = $request->get('included_user_states', []);

            ContentRepository::$availableContentStatues =
                $request->get('statuses', [ContentService::STATUS_PUBLISHED, ContentService::STATUS_SCHEDULED]);
            ContentRepository::$pullFutureContent = $request->has('future');

            $lessons = $this->contentService->getFiltered(
                $request->get('page', 1),
                $request->get('limit', 'null'),
                '-published_on',
                ['coach-stream'],
                [],
                [],
                $requiredFields,
                $includedFields,
                $requiredUserState,
                $includedUserState,
                false
            );

            $content['lessons'] = $lessons->results();
            $content['lessons_filter_options'] = ['content_type' => ['coach-stream']];

            $duration = 0;
            $totalXp = 0;
            foreach ($content['lessons'] as $courseLesson) {
                $duration += $courseLesson->fetch('fields.video.fields.length_in_seconds', 0);
                $totalXp += $courseLesson->fetch('fields.xp', 0);
            }

            $content['duration_in_seconds'] = $duration;
            $content['total_xp'] = $totalXp;
        }

        $content =
            $this->vimeoVideoDecorator->decorate(new Collection([$content]))
                ->first();

        $content['resources'] = array_merge($content['resources'] ?? [], $parent['resources'] ?? []);

        $this->stripTagDecorator->decorate(new Collection([$content]));

        $isDownload = $request->get('download', false);
        if ($isDownload && !empty($content['lessons'])) {

            $this->downloadService->attachLessonsDataForDownload($content);

            return ResponseService::contentForDownload($content);
        }

        return ResponseService::content($content);
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

    /**
     * @param $parent
     * @param $content
     * @param $parentChildren
     * @return mixed
     */
    private function attachNextPrevLesson($parent, $content, $parentChildren)
    {
        if ($parent) {
            $content['parent'] = $parent;
            $content['parent_id'] = $parent['id'];
            $parentChildren = new Collection($parentChildren);
            $lessonContent =
                $parentChildren->where('id', $content['id'])
                    ->first();

            $nextIncompleteLesson =
                $parentChildren->where('completed', '=', false)
                    ->where('id', '!=', $content['id']);
            $content['parent']['current_lesson'] = $nextIncompleteLesson->first() ?? null;

            $nextChild = $parentChildren->getMatchOffset($lessonContent, 1);
            $previousChild = $parentChildren->getMatchOffset($lessonContent, -1);
        } else {
            if ($content['type'] != 'coach') {
                ContentRepository::$availableContentStatues = [ContentService::STATUS_PUBLISHED];

                $neighbourSiblings = $this->contentService->getTypeNeighbouringSiblings(
                    $content['type'],
                    'published_on',
                    $content['published_on']
                    ??
                    Carbon::now()
                        ->toDateTimeString()
                );

                $nextChild = $neighbourSiblings['after']->first();
                $previousChild = $neighbourSiblings['before']->first();
            }
        }

        $content['next_lesson'] = $nextChild ?? null;
        $content['previous_lesson'] = $previousChild ?? null;

        return $content;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function filterContents(Request $request)
    {
        ContentRepository::$availableContentStatues =
            $request->get('statuses', [ContentService::STATUS_PUBLISHED, ContentService::STATUS_SCHEDULED]);
        ContentRepository::$pullFutureContent = $request->has('future');
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $types = $request->get('included_types', []);
        if (in_array('shows', $types)) {
            $types = array_merge($types, array_values(config('railcontent.showTypes', [])));
        }

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $includedFields = $request->get('included_fields', []);
        $requiredFields = $request->get('required_fields', []);
        if ($request->has('show_in_new_feed')) {
            $requiredFields = array_merge($requiredFields, ['show_in_new_feed,' . $request->get('show_in_new_feed')]);
        }

        $requiredUserState = $request->get('required_user_states', []);
        $includedUserState = $request->get('included_user_states', []);

        $sortedBy = '-published_on';
        foreach ($types as $type) {
            if (array_key_exists($type, config('railcontent.cataloguesMetadata'))) {
                $sortedBy = config('railcontent.cataloguesMetadata')[$type]['sortBy'] ?? $sortedBy;
            }
        }

        $sorted = $request->get('sort', $sortedBy);

        $results = new ContentFilterResultsEntity(['results' => []]);

        if (!empty($types)) {

            ConfigService::$fieldOptionList = [
                'instructor',
                'topic',
                'difficulty',
                'style',
            ];

            $results = $this->contentService->getFiltered(
                $page,
                $limit,
                $sorted,
                $types,
                [],
                [],
                $requiredFields,
                $includedFields,
                $requiredUserState,
                $includedUserState,
                true
            );
        }

        return ResponseService::catalogue($results, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getInProgressContent(Request $request)
    {
        ContentRepository::$availableContentStatues = $request->get('statuses', [ContentService::STATUS_PUBLISHED]);
        ContentRepository::$pullFutureContent = false;
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $types = $request->get('included_types', []);
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
        $includedFields = $request->get('included_fields', []);
        $requiredFields = $request->get('required_fields', []);
        $sort = $request->get('sort', '-progress');

        if (in_array('shows', $types)) {
            $types = array_merge($types, array_values(config('railcontent.showTypes', [])));
        }

        $results = new ContentFilterResultsEntity(['results' => []]);

        if (!empty($types)) {
            $results = $this->contentService->getFiltered(
                $page,
                $limit,
                $sort,
                $types,
                [],
                [],
                $requiredFields,
                $includedFields,
                ['started'],
                [],
                true
            );
        }

        return ResponseService::catalogue($results, $request);
    }

    /**
     * @param Request $request
     * @return array|mixed|ContentEntity[]|Collection
     */
    public function getLiveSchedule(Request $request)
    {
        ContentRepository::$availableContentStatues = [
            ContentService::STATUS_PUBLISHED,
            ContentService::STATUS_SCHEDULED,
        ];

        ContentRepository::$pullFutureContent = true;

        $liveEvents = $this->contentService->getWhereTypeInAndStatusAndPublishedOnOrdered(
            config('railcontent.liveContentTypes'),
            ContentService::STATUS_SCHEDULED,
            Carbon::now()
                ->subHours(24)
                ->toDateTimeString(),
            '>',
            'published_on',
            'asc'
        )
            ->sortByFieldValue('live_event_start_time')
            ->toArray();

        return ResponseService::scheduleContent($liveEvents);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getAllSchedule(Request $request)
    {
        $scheduleEvents =
            $this->contentService->getContentForCalendar(null, false)
                ->toArray();

        return ResponseService::scheduleContent($scheduleEvents);
    }

    /**
     * @param SubmitQuestionRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function submitQuestion(SubmitQuestionRequest $request)
    {
        $input = $request->all();
        $currentUser = $this->userProvider->getCurrentUser();
        $brand = config('railcontent.brand', '');

        $input['subject'] =
            config('musora-api.submit_question_subject.' . $brand, '') .
            $currentUser->getDisplayName() .
            " (" .
            $currentUser->getEmail() .
            ")";
        $input['sender-address'] = $currentUser->getEmail();
        $input['sender-name'] = $currentUser->getDisplayName();
        $input['lines'] = [$input['question']];
        $input['unsubscribeLink'] = '';
        $input['alert'] =
            config('musora-api.submit_question_subject.' . $brand, '') .
            $currentUser->getDisplayName() .
            " (" .
            $currentUser->getEmail() .
            ")";

        $input['logo'] = config('musora-api.brand_logo_path_for_email.' . $brand);
        $input['type'] = 'layouts/inline/alert';
        $input['recipient'] = config('musora-api.submit_question_recipient.' . $brand);
        $input['success'] = config('musora-api.submit_question_success_message.' . $brand);

        return $this->sendSecure($input);
    }

    /**
     * @param SubmitVideoRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function submitVideo(SubmitVideoRequest $request)
    {
        $input = $request->all();
        $currentUser = $this->userProvider->getCurrentUser();
        $brand = config('railcontent.brand', '');

        $input['subject'] =
            "Monthly Collaboration submission from: " .
            $currentUser->getDisplayName() .
            " (" .
            $currentUser->getEmail() .
            ")";
        $input['sender-address'] = $currentUser->getEmail();
        $input['sender-name'] = $currentUser->getDisplayName();
        $input['lines'] = [$input['video']];

        $input['alert'] =
            "Monthly Collaboration submission from: " .
            $currentUser->getDisplayName() .
            " (" .
            $currentUser->getEmail() .
            ")";
        $input['logo'] = config('musora-api.brand_logo_path_for_email.' . $brand);
        $input['type'] = 'layouts/inline/alert';
        $input['success'] =
            "Our team will combine your video with the other student videos to create next months episode. Collaborations are typically released on the first of each month.";

        return $this->sendSecure($input);
    }

    /**
     * @param SubmitStudentFocusFormRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function submitStudentFocusForm(SubmitStudentFocusFormRequest $request)
    {
        $currentUser = $this->userProvider->getCurrentUser();
        $brand = config('railcontent.brand', '');
        $lines = [
            '<strong>student progress info:</strong> ' .
            'https://' .
            'www.musora.com/admin/user-progress-info/' .
            $currentUser->getId(),
        ];
        $inputLines = $request->all();
        foreach ($inputLines as $key => $inputLine) {
            $lines[] = '<strong>' . $key . ':</strong> ' . $inputLine;
        }

        $input['subject'] =
        $input['alert'] =
            'Student Review Application from:' . $currentUser->getDisplayName() . '(' . $currentUser->getEmail() . ')';

        $input['lines'] = $lines;
        $input['logo'] = config('musora-api.brand_logo_path_for_email.' . $brand);
        $input['type'] = 'layouts/inline/alert';
        $input['recipient'] = config('musora_api.submit_student_focus_recipient.' . $brand);
        $input['success'] = config('musora-api.submit_student_focus_success_message.' . $brand);

        return $this->sendSecure($input);
    }

    /**
     * @param $input
     * @return JsonResponse
     * @throws Exception
     */
    public function sendSecure($input)
    {
        try {
            $this->mailoraMailService->sendSecure($input);
        } catch (Exception $exception) {
            return response()->json(
                [
                    'title' => 'Submission Failed',
                    'message' => $exception->getMessage(),
                ],
                500
            );
        }

        return response()->json(
            [
                'success' => true,
                'title' => 'Thanks for your submission!',
                'message' => $input['success'],
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws DBALException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function addLessonsToUserList(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $skill = $input['skill'];
        $topics = $input['topics'] ?? ['noTopic'];

        if (!$skill) {
            $skill = ($topics != ['noTopic']) ? 'beginner' : 'noDifficulty';
        }

        $userId = auth()->id();

        $lessons = [];
        foreach ($topics as $topic) {
            $lessons = array_merge(
                $lessons,
                config('lessonsSkillsAndTopicMapping.topicDifficultyMapping')[$topic][$skill] ?? []
            );
        }

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $userPrimaryPlaylist =
            $this->contentService->getByUserIdTypeSlug($userId, 'user-playlist', 'primary-playlist')
                ->first();

        if (!$userPrimaryPlaylist) {
            $userPrimaryPlaylist = $this->contentService->create(
                'primary-playlist',
                'user-playlist',
                ContentService::STATUS_PUBLISHED,
                null,
                config('railcontent.brand'),
                $userId,
                Carbon::now()
                    ->toDateTimeString()
            );
        }

        foreach ($lessons as $lesson) {
            $this->contentHierarchyRepository->updateOrCreateChildToParentLink(
                $userPrimaryPlaylist['id'],
                $lesson,
                null
            );
        }

        return $this->contentService->getFiltered(
            1,
            10,
            '-published_on',
            [],
            [],
            [$userPrimaryPlaylist['id']],
            [],
            [],
            [],
            [],
            false
        )
            ->toJsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function search(Request $request)
    {
        ContentRepository::$availableContentStatues =
            $request->get('statuses', ContentRepository::$availableContentStatues);

        $contentsData = $this->fullTextSearchService->search(
            $request->get('term', null),
            $request->get('page', 1),
            $request->get('limit', 10),
            $request->get('included_types', []),
            $request->get('statuses', []),
            $request->get('sort', '-score'),
            $request->get('date_time_cutoff', null),
            $request->get('brands', null)
        );

        return ResponseService::catalogue(
            new ContentFilterResultsEntity(
                [
                    'results' => $contentsData['results'],
                    'total_results' => $contentsData['total_results'],
                ]
            ),
            $request
        );
    }

}
