<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Mailora\Services\MailService;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Decorators\StripTagDecorator;
use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\MusoraApi\Exceptions\MusoraAPIException;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Requests\SubmitQuestionRequest;
use Railroad\MusoraApi\Requests\SubmitStudentFocusFormRequest;
use Railroad\MusoraApi\Requests\SubmitVideoRequest;
use Railroad\MusoraApi\Services\DownloadService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Decorators\ModeDecoratorBase;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentHierarchyRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Requests\ContentFollowRequest;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ConfigService;
use Railroad\Railcontent\Services\ContentFollowsService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Services\FullTextSearchService;
use Railroad\Railcontent\Support\Collection;
use ReflectionException;
use Throwable;

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
     * @var ContentFollowsService
     */
    private $contentFollowsService;

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
        DownloadService $downloadService,
        ContentFollowsService $contentFollowsService
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
        $this->contentFollowsService = $contentFollowsService;
    }

    /**
     * @param $contentId
     * @param Request $request
     * @return array
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    public function getContent($contentId, Request $request)
    {
        $content = $this->contentService->getById($contentId);
        throw_if(!$content, new NotFoundException('Content not exists.'));

        //get content's parent for related lessons and resources
        $parent = array_first(
            $this->contentService->getByChildIdWhereParentTypeIn($contentId,
                ['course', 'song', 'learning-path', 'pack', 'pack-bundle'])
        );

        $lessons = $content['lessons'] ?? ($parent['lessons'] ?? false);

        ContentRepository::$availableContentStatues = $request->get('statuses', [ContentService::STATUS_PUBLISHED]);

        $pullFutureContent = ContentRepository::$pullFutureContent;
        ContentRepository::$pullFutureContent = $request->has('future');

        $sorted = '-published_on';
        if (array_key_exists($content['type'], config('railcontent.cataloguesMetadata'))) {
            $sorted = config('railcontent.cataloguesMetadata')[$content['type']]['sortBy'] ?? $sorted;
        }

        //related lessons for a coach stream should be specific to the current coach
        $requiredFields = [];
        $includedFields = [];

        if ($content['type'] == 'coach-stream') {
            $instructor = array_first(ContentHelper::getFieldValues($content->getArrayCopy(), 'instructor'));
            $requiredFields = ($instructor) ? ['instructor,' . $instructor['id']] : [];

            $lessons = $this->contentService->getFiltered(
                1,
                10,
                $sorted,
                [$content['type']],
                [],
                [],
                $requiredFields,
                $includedFields,
                [],
                [],
                false,
                false,
                false
            )['results'];
        } elseif ($content['type'] == 'song') {
            $songsFromSameArtist = $this->contentService->getFiltered($request->get('page', 1),
                $request->get('limit', 10),
                '-published_on',
                [$content['type']],
                [],
                [],
                ['artist,' . $content->fetch('fields.artist')])['results'];

            // remove requested song if in related lessons, part one of two
            foreach ($songsFromSameArtist as $songFromSameArtistIndex => $songFromSameArtist) {
                if ($content['id'] == $songFromSameArtist['id']) {
                    unset($songsFromSameArtist[$songFromSameArtistIndex]);
                }
            }

            $songsFromSameArtist = $songsFromSameArtist->sortByFieldValue('title');

            $songsFromSameStyle = new Collection();

            if (count($songsFromSameArtist) < 10) {
                $songsFromSameStyle =
                    $this->contentService->getFiltered(1,
                        19,
                        '-published_on',
                        [$content['type']],
                        [],
                        [],
                        ['style,' . $content->fetch('fields.style')])['results'];

                // remove requested song if in related lessons, part two of two (because sometimes in $songsFromSameStyle)
                foreach ($songsFromSameStyle as $songFromSameStyleIndex => $songFromSameStyle) {
                    if ($content['id'] == $songFromSameStyle['id']) {
                        unset($songsFromSameStyle[$songFromSameStyleIndex]);
                    }
                }

                $songsFromSameStyle = $songsFromSameStyle->sortByFieldValue('title');

                foreach ($songsFromSameStyle as $songFromSameStyleIndex => $songFromSameStyle) {
                    foreach ($songsFromSameArtist as $songFromSameArtistIndex => $songFromSameArtist) {
                        if ($songFromSameStyle['id'] == $songFromSameArtist['id']) {
                            unset($songsFromSameStyle[$songFromSameStyleIndex]);
                        }
                    }
                }
            }

            $lessons = array_slice(
                array_merge($songsFromSameArtist->toArray(), $songsFromSameStyle->toArray()),
                0,
                10
            );
        }

        //neighbour siblings will be used as related lessons (for top level content should have lessons with the same type)
        // attach next and previous lessons to content
        if ($parent && !$lessons) {
            $parentChildren = $this->contentService->getByParentId($parent['id']);
        } elseif (!$parent && !$lessons) {
            $orderByDirection = substr($sorted, 0, 1) !== '-' ? 'asc' : 'desc';
            $orderByColumn = trim($sorted, '-');

            // reverse the next/prev show's buttons to be same as on the website
            if ($content['type'] == 'rhythmic-adventures-of-captain-carson' ||
                $content['type'] == 'diy-drum-experiments' ||
                $content['type'] == 'in-rhythm') {
                $orderByDirection = 'desc';
            }

            $neighbourSiblings = $this->contentService->getTypeNeighbouringSiblings(
                $content['type'],
                $orderByColumn,
                $orderByColumn == 'sort' ? $content['sort'] : $content['published_on'],
                1,
                $orderByColumn,
                $orderByDirection
            );

            $content['next_lesson'] = $neighbourSiblings['before']->first();
            $content['previous_lesson'] = $neighbourSiblings['after']->first();

            $parentChildren = $this->contentService->getFiltered(
                1,
                10,
                $sorted,
                [$content['type']],
                [],
                [],
                $requiredFields,
                $includedFields,
                [],
                [],
                false,
                false,
                false
            )['results'];
        } else {
            $parentChildren = new Collection($lessons);

            $currentContentIterator =
                $parentChildren->where('id', '=', $content['id'])
                    ->keys()
                    ->first() ?? 1;

            $content['next_lesson'] =
                $parentChildren->only($currentContentIterator + 1)
                    ->first();
            $content['previous_lesson'] =
                $parentChildren->only($currentContentIterator - 1)
                    ->first();
        }

        ContentRepository::$pullFutureContent = $pullFutureContent;
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $content['related_lessons'] = $this->getParentChildTrimmed($parentChildren, $content);

        if (array_key_exists('lessons', $content)) {
            $content['lessons'] = new Collection($content['lessons']);
            $content['next_lesson'] =
                $content['lessons']->where('completed', '=', false)
                    ->first();
        }

        //attached comments on the content
        CommentRepository::$availableContentId = $content['id'];
        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $content['comments'] = (new CommentTransformer())->transform($comments['results']);
        $content['total_comments'] = $comments['total_comments_and_results'];

        //attached lessons to the content if not exists already
        if (!array_key_exists('lessons', $content) &&
            !in_array($content['type'], config('railcontent.singularContentTypes', []))) {
            $content['lessons'] = $this->contentService->getByParentId($content['id']);
        }

        /**
         * content with 'coach' type have lessons saved in different table, so we need to call getFilter method in order to pull them
         */
        if ($content['type'] == 'coach' || $content['type'] == 'instructor') {
            $includedFields = [];
            $includedFields[] = 'instructor,' . $content['id'];
            $instructor =
                $this->contentService->getBySlugAndType($content['slug'], 'coach')
                    ->first();
            if ($instructor) {
                $includedFields[] = 'instructor,' . $instructor['id'];
            }

            $requiredFields = $request->get('required_fields', []);
            $includedFields = array_merge($request->get('included_fields', []), $includedFields);
            $requiredUserState = $request->get('required_user_states', []);
            $includedUserState = $request->get('included_user_states', []);

            ContentRepository::$availableContentStatues =
                $request->get('statuses', [ContentService::STATUS_PUBLISHED, ContentService::STATUS_SCHEDULED]);
            ContentRepository::$pullFutureContent = $request->has('future');

            $lessons = $this->contentService->getFiltered(
                $request->get('page', 1),
                $request->get('limit', 10),
                $request->get('sort','-published_on'),
                $request->get('included_types', []),
                [],
                [],
                $requiredFields,
                $includedFields,
                $requiredUserState,
                $includedUserState,
                true
            );

            $content['lessons'] = $lessons->results();
            $content['lessons_filter_options'] = $lessons->filterOptions();
            $content['lessons_filter_options_v2'] =
                array_intersect_key($content['lessons_filter_options'], array_flip(['content_type']));
            $content['total_lessons'] = $lessons->totalResults();

            $duration = 0;
            $totalXp = 0;
            foreach ($content['lessons'] as $courseLessonIndex => $courseLesson) {
                if($courseLesson['type'] == 'song'){
                    $content['lessons'][$courseLessonIndex]['lesson_count'] = $this->contentService->countByParentIdWhereTypeIn($courseLesson['id'],['song-part']);
                    if($content['lessons'][$courseLessonIndex]['lesson_count'] == 1) {
                        $content['lessons'][$courseLessonIndex]['lessons'] = $this->contentService->getByParentId($courseLesson['id']);
                    }
                }
                $duration += $courseLesson->fetch('fields.video.fields.length_in_seconds', 0);
                $totalXp += $courseLesson->fetch('fields.xp', 0);
            }

            $content['duration_in_seconds'] = $duration;
            $content['total_xp'] = $totalXp;

            //attach coach's featured lessons
            $includedFields = [];
            $includedFields[] = 'instructor,' . $content['id'];
            $instructor =
                $this->contentService->getBySlugAndType($content['slug'], 'coach')
                    ->first();
            if ($instructor) {
                $includedFields[] = 'instructor,' . $instructor['id'];
            }

            $content['featured_lessons'] =
                $this->contentService->getFiltered(1, 4, '-published_on', [], [], [], ['is_featured,1'],
                    $includedFields, [], [])
                    ->results();
        }

        //add parent's instructors and resources to content
        $content['resources'] = array_merge($content['resources'] ?? [], $parent['resources'] ?? []);

        if ($parent) {
            $content['instructor'] = array_merge(
                $content['instructor'] ?? [],
                ContentHelper::getFieldValues($parent->getArrayCopy(), 'instructor')
            );

            $content['coaches'] = array_merge(
                $content['coaches'] ?? [],
                $parent['coaches'] ?? []
            );

            $content['style'] = $content->fetch('fields.style', null) ?? $parent->fetch('fields.style');
            $content['artist'] = $content->fetch('fields.artist', null) ?? $parent->fetch('fields.artist');
            $content['parent'] = $parent;
        }

        $content =
            $this->vimeoVideoDecorator->decorate(new Collection([$content]))
                ->first();

        $this->stripTagDecorator->decorate(new Collection([$content]));

        //singular content types and shows types should not return assignments as lessons
        if (!empty($content['lessons'] ?? []) && in_array(
                $content['type'],
                array_merge(config('railcontent.singularContentTypes', []), config('railcontent.showTypes', []))
            )) {
            unset($content['lessons']);
        }

        // we need extra data for offline mode and a different response structure
        $isDownload = $request->get('download', false);
        if ($isDownload && !empty($content['lessons'] ?? [])) {

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
        $matched = false;

        foreach ($parentChildren as $parentChildIndex => $parentChild) {

            if ((count($parentChildren) - $parentChildIndex) <= 10 &&
                count($parentChildrenTrimmed) < 10 &&
                $parentChild['id'] != $content['id']) {
                $parentChildrenTrimmed[] = $parentChild;
            } elseif ($matched && count($parentChildrenTrimmed) < 10) {
                $parentChildrenTrimmed[] = $parentChild;
            }

            if ($parentChild['id'] == $content['id']) {
                $matched = true;
            }
        }

        return $parentChildrenTrimmed;
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
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;

        $types = $request->get('included_types', []);
        if (in_array('shows', $types)) {
            $types = array_merge($types, array_values(config('railcontent.showTypes', [])));
        }

        $requiredFields = $request->get('required_fields', []);
        if ($request->has('show_in_new_feed')) {
            $requiredFields = array_merge($requiredFields, ['show_in_new_feed,' . $request->get('show_in_new_feed')]);
        }

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
                'focus',
                'genre',
            ];

            if ($types == ['song']) {
                ConfigService::$fieldOptionList = [
                    'topic',
                    'difficulty',
                    'style',
                    'artist',
                ];
            }

            $results = $this->contentService->getFiltered(
                $request->get('page', 1),
                $request->get('limit', 10),
                $sorted,
                $types,
                [],
                [],
                $requiredFields,
                $request->get('included_fields', []),
                $request->get('required_user_states', []),
                $request->get('included_user_states', []),
                ($types == ['coach-stream']) ? false : true,
                false,
                true,
                $request->get('only_subscribed', false)
            );

        }

        return ResponseService::catalogue($results, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getInProgressContent(Request $request)
    {
        ContentRepository::$availableContentStatues = $request->get('statuses', [ContentService::STATUS_PUBLISHED]);
        ContentRepository::$pullFutureContent = false;
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $types = $request->get('included_types', []);
        if (in_array('shows', $types)) {
            $types = array_merge($types, array_values(config('railcontent.showTypes', [])));
        }

        $results = new ContentFilterResultsEntity(['results' => []]);

        if (!empty($types)) {
            $results = $this->contentService->getFiltered(
                $request->get('page', 1),
                $request->get('limit', 10),
                $request->get('sort', '-progress'),
                $types,
                [],
                [],
                $request->get('required_fields', []),
                $request->get('included_fields', []),
                ['started'],
                [],
                true
            );
        }

        return ResponseService::catalogue($results, $request);
    }

    /**
     * @param Request $request
     * @return array
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
            throw new MusoraAPIException($exception->getMessage(), 'Submission Failed', 500);
        }

        return ResponseService::array([
            'success' => true,
            'title' => 'Thanks for your submission!',
            'message' => $input['success'],
        ]);
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

        $skill = $input['skill'] ?? null;
        $topics = $input['topics'] ?? ['noTopic'];

        if (!$skill) {
            $skill = ($topics != ['noTopic']) ? 'beginner' : 'noDifficulty';
        }

        $userId =
            $this->userProvider->getCurrentUser()
                ->getId();

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

        $lessons = $this->contentService->getFiltered(
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
        );

        return ResponseService::list($lessons, $request);
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
            new ContentFilterResultsEntity([
                'results' => $contentsData['results'],
                'total_results' => $contentsData['total_results'],
            ]),
            $request
        );
    }

    /**
     * @param $slug
     * @return array
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    public function getDeepLinkForCoach($slug)
    {
        $content = $this->contentService->getBySlugAndType($slug, 'instructor');
        throw_if($content->isEmpty(), new NotFoundException('Content not exists.'));

        $request = new Request();
        $request->merge([
            'statuses' => [ContentService::STATUS_PUBLISHED, ContentService::STATUS_SCHEDULED],
            'future' => true,
            'limit' => 10,
            'page' => 1
        ]);

        return $this->getContent($content->first()['id'], $request);
    }

    /**
     * @param ContentFollowRequest $request
     * @return mixed
     */
    public function followContent(ContentFollowRequest $request)
    {
        $response = $this->contentFollowsService->follow(
            $request->input('content_id'),
            auth()->id()
        );

        return ResponseService::array($response, ($response) ? 200 : 500);
    }

    /**
     * @param ContentFollowRequest $request
     * @return mixed
     */
    public function unfollowContent(ContentFollowRequest $request)
    {
        $this->contentFollowsService->unfollow(
            $request->input('content_id'),
            auth()->id()
        );

        return ResponseService::empty(204);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLessonsForFollowedCoaches(Request $request)
    {
        $contentData = $this->contentFollowsService->getLessonsForFollowedCoaches(
            $request->get('brand', config('railcontent.brand')),
            $request->get('content_type', []),
            $request->get('statuses', [ContentService::STATUS_PUBLISHED]),
            $request->get('page', 1),
            $request->get('limit', 10),
            $request->get('sort', '-published_on')
        );

        return ResponseService::catalogue($contentData, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getFollowedContent(Request $request)
    {
        $followedContents = $this->contentFollowsService->getUserFollowedContent(
            auth()->id(),
            $request->get('brand', config('railcontent.brand')),
            $request->get('content_type'),
            $request->get('page', 1),
            $request->get('limit', 10)
        );

        return ResponseService::catalogue(
            $followedContents,
            $request
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getFeaturedLessons(Request $request)
    {
        $featuredCoaches =
            $this->contentService->getFiltered(1, 'null', 'slug', ['instructor'], [], [], [], ['is_featured,1']);

        $includedFields = [];
        foreach ($featuredCoaches->results() as $featuredCoache) {
            $includedFields[] = 'instructor,' . $featuredCoache['id'];
            $instructor =
                $this->contentService->getBySlugAndType($featuredCoache['slug'], 'coach')
                    ->first();
            if ($instructor) {
                $includedFields[] = 'instructor,' . $instructor['id'];
            }
        }

        $includedTypes = $request->get('included_types',
            array_merge(config('railcontent.coachContentTypes', []), config('railcontent.showTypes', [])));

        //latest featured lessons - Show the latest lessons from all the featured coaches.
        ContentRepository::$availableContentStatues = [ContentService::STATUS_PUBLISHED];
        ContentRepository::$pullFutureContent = false;

        $latestLessons = $this->contentService->getFiltered(
            $request->get('page', 1),
            $request->get('limit', 10),
            '-published_on',
            $includedTypes,
            [],
            [],
            [],
            $includedFields,
            [],
            [],
            false,
            false,
            true
        );

        return ResponseService::catalogue(
            $latestLessons,
            $request
        );
    }

}
