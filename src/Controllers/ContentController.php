<?php

namespace Railroad\MusoraApi\Controllers;

use App\Decorators\LessonAssignmentDecorator;
use Carbon\Carbon;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

//use Railroad\Mailora\Services\MailService;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Decorators\StripTagDecorator;
use Railroad\MusoraApi\Exceptions\MusoraAPIException;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Requests\ContentMetaRequest;
use Railroad\MusoraApi\Requests\SubmitQuestionRequest;
use Railroad\MusoraApi\Requests\SubmitStudentFocusFormRequest;
use Railroad\MusoraApi\Requests\SubmitVideoRequest;
use Railroad\MusoraApi\Services\DownloadService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Decorators\ModeDecoratorBase;
use Railroad\Railcontent\Decorators\Video\ContentVimeoVideoDecorator;
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
use Railroad\Railcontent\Services\UserPlaylistsService;
use Railroad\Railcontent\Support\Collection;
use ReflectionException;
use Throwable;
use Illuminate\Support\Arr;

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
     * @var ContentVimeoVideoDecorator
     */
    private $vimeoVideoDecorator;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var UserPlaylistsService
     */
    private $userPlaylistsService;

    /**
     * @var MailService
     */
    //    private $mailoraMailService;

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
     * @var ProductProviderInterface
     */
    private $productProvider;

    /**
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param ContentVimeoVideoDecorator $vimeoVideoDecorator
     * @param UserProviderInterface $userProvider
     * @param ContentHierarchyRepository $contentHierarchyRepository
     * @param FullTextSearchService $fullTextSearchService
     * @param StripTagDecorator $stripTagDecorator
     * @param DownloadService $downloadService
     * @param ContentFollowsService $contentFollowsService
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        ContentVimeoVideoDecorator $vimeoVideoDecorator,
        UserProviderInterface $userProvider,
        //        MailService $mailoraMailService,
        ContentHierarchyRepository $contentHierarchyRepository,
        FullTextSearchService $fullTextSearchService,
        StripTagDecorator $stripTagDecorator,
        DownloadService $downloadService,
        ContentFollowsService $contentFollowsService,
        UserPlaylistsService $userPlaylistsService,
        ProductProviderInterface $productProvider,
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
        $this->userProvider = $userProvider;
        //        $this->mailoraMailService = $mailoraMailService;
        $this->contentHierarchyRepository = $contentHierarchyRepository;
        $this->fullTextSearchService = $fullTextSearchService;
        $this->stripTagDecorator = $stripTagDecorator;
        $this->downloadService = $downloadService;
        $this->contentFollowsService = $contentFollowsService;
        $this->userPlaylistsService = $userPlaylistsService;
        $this->productProvider = $productProvider;
    }

    public function getContentOptimised($contentId, Request $request)
    {
        $content = $this->contentService->getById($contentId);
        throw_if(!$content, new NotFoundException('Content not exists.'));

        $lessonContentTypes = array_diff(array_merge(
                                             config('railcontent.showTypes')[config('railcontent.brand')] ?? [],
                                             config('railcontent.singularContentTypes')
                                         ), ['song']);

        $content['resources'] = array_values($content['resources'] ?? []);

        if (in_array($content['type'], $lessonContentTypes)) {
            //overview page
            $parent = Arr::first($this->contentService->getByChildId($content['id']));
            if ($parent) {
                $content = $this->attachDataFromParent($content, $parent);
                $content = $this->attachRelatedLessonsFromParent($parent['id'], $content);
            } else {
                $content = $this->attachSiblingRelatedLessons($content, $request);
            }
        } elseif (in_array($content['type'], [
            'course',
            'learning-path',
            'learning-path-level',
            'learning-path-course',
            'pack',
            'semester-pack',
        ])) {
            $content = $this->attachChildrens($content);
            if (($content['child_count'] ?? 0) == 1) {
                $childrenNameMapping = config('railcontent.children_name_mapping')[config('railcontent.brand')] ?? [];
                $childrenName = $childrenNameMapping[$content['type']];
                $initialContent = clone $content;
                $content = $content[$childrenName][0];
                if (empty($content['data'])) {
                    $content['data'] = $initialContent['data'];
                }
            }
        } elseif ($content['type'] == 'song') {
            $content = $this->attachSongRelatedLessons($request, $content);
        }

        //attach instructor's lessons
        $content = $this->attachInstructorLessons($content, $request);

        //attach instructor's featured lessons
        $content = $this->attachFeaturedLessons($content, $request);

        //attach pack's details
        $content = $this->attachPackData($content);

        //attached comments on the content
        $content = $this->attachComments($content);

        //vimeo endpoints
        $content =
            $this->vimeoVideoDecorator->decorate(new Collection([$content]))
                ->first();

        //strip HTML tags
        $this->stripTagDecorator->decorate(new Collection([$content]));

        // we need extra data for offline mode and a different response structure
        $isDownload = $request->get('download', false);
        if ($isDownload && !empty($content['lessons'] ?? [])) {
            $this->downloadService->attachLessonsDataForDownload($content);

            return ResponseService::contentForDownload($content);
        }

        return ResponseService::content($content);
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

        if ($content['type'] == 'learning-path-lesson') {
            return redirect()->route(
                'mobile.musora-api.learning-path.lesson.show',
                ['lessonId' => $content['id'], 'brand' => $content['brand']]
            );
        } elseif ($content['type'] == 'pack') {
            return redirect()->route(
                'mobile.musora-api.pack.show',
                ['packId' => $content['id'], 'brand' => $content['brand']]
            );
        }

        //get content's parent for related lessons and resources
        $parent = Arr::first(
            $this->contentService->getByChildIdWhereParentTypeIn($contentId, [
                'course',
                'song',
                'learning-path',
                'pack',
                'pack-bundle',
            ])
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
            $instructor = Arr::first(ContentHelper::getFieldValues($content->getArrayCopy(), 'instructor'));
            $requiredFields = ($instructor) ? ['instructor,'.$instructor['id']] : [];

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
                                                                      ['artist,'.$content->fetch('fields.artist')]
            )['results'];

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
                                                       ['style,'.$content->fetch('fields.style')])['results'];

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

        if (isset($content['lessons']) && !in_array($content['type'], config('railcontent.singularContentTypes', []))) {
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
        if (!isset($content['lessons']) &&
            !in_array($content['type'], config('railcontent.singularContentTypes', []))) {
            $content['lessons'] = $this->contentService->getByParentId($content['id']);
        }

        /**
         * content with 'coach' type have lessons saved in different table, so we need to call getFilter method in order to pull them
         */
        if ($content['type'] == 'coach' || $content['type'] == 'instructor') {
            $includedFields = [];
            $includedFields[] = 'instructor,'.$content['id'];
            $instructor =
                $this->contentService->getBySlugAndType($content['slug'], 'coach')
                    ->first();
            if ($instructor) {
                $includedFields[] = 'instructor,'.$instructor['id'];
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
                $request->get('sort', '-published_on'),
                $request->get(
                    'included_types',
                    array_merge(
                        config('railcontent.coachContentTypes', []),
                        config('railcontent.showTypes')[config('railcontent.brand')] ?? []
                    )
                ),
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
                if ($courseLesson['type'] == 'song') {
                    $content['lessons'][$courseLessonIndex]['lesson_count'] =
                        $this->contentService->countByParentIdWhereTypeIn($courseLesson['id'], ['song-part']);
                    if ($content['lessons'][$courseLessonIndex]['lesson_count'] == 1) {
                        $content['lessons'][$courseLessonIndex]['lessons'] =
                            $this->contentService->getByParentId($courseLesson['id']);
                    }
                }
                $duration += $courseLesson->fetch('fields.video.fields.length_in_seconds', 0);
                $totalXp += $courseLesson->fetch('fields.xp', 0);
            }

            $content['duration_in_seconds'] = $duration;
            $content['total_xp'] = $totalXp;

            //attach coach's featured lessons
            $includedFields = [];
            $includedFields[] = 'instructor,'.$content['id'];
            $instructor =
                $this->contentService->getBySlugAndType($content['slug'], 'coach')
                    ->first();
            if ($instructor) {
                $includedFields[] = 'instructor,'.$instructor['id'];
            }

            $content['featured_lessons'] =
                $this->contentService->getFiltered(1, 4, '-published_on', [], [], [], ['is_featured,1'],
                                                   $includedFields,
                                                   [], [])
                    ->results();
        }

        //add parent's instructors and resources to content
        $content['resources'] = array_merge($content['resources'] ?? [], $parent['resources'] ?? []);

        if ($parent) {
            $content['instructor'] = array_unique(
                array_merge(
                    $content['instructor'] ?? [],
                    ContentHelper::getFieldValues($parent->getArrayCopy(), 'instructor')
                ),
                SORT_REGULAR
            );
            $contentRows = array_merge(
                $content['coaches'] ?? [],
                $parent['coaches'] ?? []
            );
            $coachIds = array_unique(array_column($contentRows, 'id'));
            $content['coaches'] = array_intersect_key($contentRows, $coachIds);
            $content['instructor'] = array_intersect_key($content['instructor'], $coachIds);

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
                array_merge(
                    config('railcontent.singularContentTypes', []),
                    config('railcontent.showTypes')[config('railcontent.brand')] ?? []
                )
            )) {
            unset($content['lessons']);
        }

        // we need extra data for offline mode and a different response structure
        $isDownload = $request->get('download', false);
        if ($isDownload && !empty($content['lessons'] ?? [])) {
            $this->downloadService->attachLessonsDataForDownload($content);

            return ResponseService::contentForDownload($content);
        }

        if ($content['type'] == 'learning-path-level') {
            foreach ($content['lessons'] as $index => $course) {
                $content['lessons'][$index]['level_rank'] = $content['level_number'].'.'.$course['course_position'];
            }
            $content['courses'] = $content['lessons'];
            $content['banner_background_image'] = $parent->fetch('data.header_image_url', '');
            $content['banner_button_url'] =
                $content->fetch('next_lesson') ? url()->route('mobile.musora-api.content.show', [
                    $content->fetch(
                        'next_lesson'
                    )['id'],
                ]) : null;
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
            $types =
                array_merge($types, array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? []));
        }

        $requiredFields = $request->get('required_fields', []);
        if ($request->has('show_in_new_feed')) {
            $requiredFields = array_merge($requiredFields, ['show_in_new_feed,'.$request->get('show_in_new_feed')]);
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
            $types =
                array_merge($types, array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? []));
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
            config('musora-api.submit_question_subject.'.$brand, '').
            $currentUser->getDisplayName().
            " (".
            $currentUser->getEmail().
            ")";
        $input['sender-address'] = $currentUser->getEmail();
        $input['sender-name'] = $currentUser->getDisplayName();
        $input['lines'] = [$input['question']];
        $input['unsubscribeLink'] = '';
        $input['alert'] =
            config('musora-api.submit_question_subject.'.$brand, '').
            $currentUser->getDisplayName().
            " (".
            $currentUser->getEmail().
            ")";

        $input['logo'] = config('musora-api.brand_logo_path_for_email.'.$brand);
        $input['type'] = 'layouts/inline/alert';
        $input['recipient'] = config('musora-api.submit_question_recipient.'.$brand);
        $input['success'] = config('musora-api.submit_question_success_message.'.$brand);

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
            "Monthly Collaboration submission from: ".$currentUser->getDisplayName()." (".$currentUser->getEmail().")";
        $input['sender-address'] = $currentUser->getEmail();
        $input['sender-name'] = $currentUser->getDisplayName();
        $input['lines'] = [$input['video']];

        $input['alert'] =
            "Monthly Collaboration submission from: ".$currentUser->getDisplayName()." (".$currentUser->getEmail().")";
        $input['logo'] = config('musora-api.brand_logo_path_for_email.'.$brand);
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
            '<strong>student progress info:</strong> '.
            'https://'.
            'www.musora.com/admin/user-progress-info/'.
            $currentUser->getId(),
        ];
        $inputLines = $request->all();
        foreach ($inputLines as $key => $inputLine) {
            $lines[] = '<strong>'.$key.':</strong> '.$inputLine;
        }

        $input['subject'] =
        $input['alert'] =
            'Student Review Application from:'.$currentUser->getDisplayName().'('.$currentUser->getEmail().')';

        $input['lines'] = $lines;
        $input['logo'] = config('musora-api.brand_logo_path_for_email.'.$brand);
        $input['type'] = 'layouts/inline/alert';
        $input['recipient'] = config('musora_api.submit_student_focus_recipient.'.$brand);
        $input['success'] = config('musora-api.submit_student_focus_success_message.'.$brand);

        return $this->sendSecure($input);
    }

    /**
     * @param $input
     * @return JsonResponse
     * @throws Exception
     */
    public function sendSecure($input)
    {
        return '';
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
        $userId = auth()->id();

        $input = json_decode($request->getContent(), true);

        $skill = $input['skill'] ?? null;
        $topics = $input['topics'] ?? ['noTopic'];

        if (!$skill) {
            $skill = ($topics != ['noTopic']) ? 'beginner' : 'noDifficulty';
        }

        $lessons = [];
        foreach ($topics as $topic) {
            $lessons = array_merge(
                $lessons,
                config('lessonsSkillsAndTopicMapping.topicDifficultyMapping')[$topic][$skill] ?? []
            );
        }

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $userPrimaryPlaylist = $this->userPlaylistsService->updateOrCeate(['user_id' => $userId], [
            'user_id' => $userId,
            'type' => 'primary-playlist',
            'brand' => $request->get('brand'),
            'created_at' => Carbon::now()
                ->toDateTimeString(),
        ]);

        foreach ($lessons as $lesson) {
            $this->userPlaylistsService->addContentToUserPlaylist($userPrimaryPlaylist['id'], $lesson);
        }

        return ResponseService::list(
            new ContentFilterResultsEntity([
                                               'results' => $this->userPlaylistsService->getUserPlaylistContents(
                                                   $userPrimaryPlaylist['id']
                                               ),
                                               'total_results' => $this->userPlaylistsService->countUserPlaylistContents(
                                                   $userPrimaryPlaylist['id']
                                               ),
                                           ]),
            $request
        );
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

        $types = $request->get('included_types', []);
        if (in_array('shows', $types)) {
            $types =
                array_merge($types, array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? []));
        }

        $contentsData = $this->fullTextSearchService->search(
            $request->get('term', null),
            $request->get('page', 1),
            $request->get('limit', 10),
            $types,
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
                            'page' => 1,
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
            $request->get('content_type', config('railcontent.coachContentTypes')),
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
            $includedFields[] = 'instructor,'.$featuredCoache['id'];
        }

        $includedTypes = $request->get(
            'included_types',
            array_merge(
                config('railcontent.coachContentTypes', []),
                config('railcontent.showTypes')[config('railcontent.brand')] ?? []
            )
        );

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

    /**
     * @return JsonResponse
     */
    public function getUpcomingCoaches()
    {
        $upcomingCoaches = config('coaches.upcoming_coaches', []);

        foreach ($upcomingCoaches as $index => $coach) {
            foreach ($coach as $coachFieldIndex => $coachField) {
                $upcomingCoaches[$index][$coachFieldIndex] = strip_tags(html_entity_decode($coachField));
            }
        }

        return ResponseService::array($upcomingCoaches);
    }

    /**
     * @param $id
     * @param mixed $content
     * @return mixed
     */
    private function attachRelatedLessonsFromParent($id, mixed $content)
    : mixed {
        //related lessons
        $parentChildren = $this->contentService->getByParentId($id);
        $content['related_lessons'] = $this->getParentChildTrimmed($parentChildren, $content);

        //previous/next lesson
        $lessonHierarchyContent =
            $parentChildren->where('id', $content['id'])
                ->first();
        $content['next_lesson'] = $parentChildren->getMatchOffset($lessonHierarchyContent, 1);
        $content['previous_lesson'] = $parentChildren->getMatchOffset($lessonHierarchyContent, -1);

        return $content;
    }

    /**
     * @param mixed $content
     * @param Request $request
     * @return mixed
     */
    private function attachSiblingRelatedLessons(mixed $content, Request $request)
    : mixed {
        $sort = 'published_on';

        if ($content['type'] == 'rhythmic-adventures-of-captain-carson' ||
            $content['type'] == 'diy-drum-experiments' ||
            $content['type'] == 'in-rhythm') {
            $sort = 'sort';
        }

        $parentChildren =
            $this->contentService->getFiltered($request->get('page', 1),
                                               $request->get('limit', 10),
                                               '-'.$sort,
                                               [$content['type']])['results'];

        $content['related_lessons'] = $this->getParentChildTrimmed($parentChildren, $content);

        // Alter 'availableContentStatues' so next/prev buttons don't link to lessons with different status.
        // (eg: don't link to archived lessons from non-archived lessons, and vice-versa)
        if ($content->fetch('status') === ContentService::STATUS_PUBLISHED) {
            ContentRepository::$availableContentStatues = [ContentService::STATUS_PUBLISHED];
        }
        if ($content->fetch('status') === ContentService::STATUS_ARCHIVED) {
            ContentRepository::$availableContentStatues = [ContentService::STATUS_ARCHIVED];
        }

        $neighbourSiblings = $this->contentService->getTypeNeighbouringSiblings(
            $content['type'],
            $sort,
            $sort == 'sort' ? $content['sort'] : $content['published_on'],
            1,
            $sort,
            'desc'
        );

        // Revert to previous state
        ContentRepository::$availableContentStatues =
            [ContentService::STATUS_PUBLISHED, ContentService::STATUS_ARCHIVED];

        $content['next_lesson'] = $neighbourSiblings['before']->first();
        $content['previous_lesson'] = $neighbourSiblings['after']->first();

        return $content;
    }

    /**
     * @param Request $request
     * @param mixed $content
     * @return mixed
     */
    private function attachSongRelatedLessons(Request $request, mixed $content)
    : mixed {
        $songsFromSameArtist = $this->contentService->getFiltered($request->get('page', 1),
                                                                  $request->get('limit', 10),
                                                                  '-published_on',
                                                                  [$content['type']],
                                                                  [],
                                                                  [],
                                                                  ['artist,'.$content->fetch('fields.artist')]
        )['results'];

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
                                                   ['style,'.$content->fetch('fields.style')])['results'];

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

        $content['related_lessons'] = array_slice(
            array_merge($songsFromSameArtist->toArray(), $songsFromSameStyle->toArray()),
            0,
            10
        );

        $parentChildren = new Collection($content['related_lessons']);

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

        return $content;
    }

    /**
     * @param mixed $content
     * @param Request $request
     * @return array
     * @throws NonUniqueResultException
     */
    private function attachInstructorLessons(mixed $content, Request $request)
    : mixed {
        if ($content['type'] != 'instructor') {
            return $content;
        }

        $includedFields = [];
        $includedFields[] = 'instructor,'.$content['id'];

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
            $request->get('sort', '-published_on'),
            $request->get(
                'included_types',
                array_merge(
                    config('railcontent.coachContentTypes', []),
                    config('railcontent.showTypes')[config('railcontent.brand')] ?? []
                )
            ),
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
            array_intersect_key($content['lessons_filter_options'], array_flip(['type']));
        $content['total_lessons'] = $lessons->totalResults();

        $duration = 0;
        $totalXp = 0;
        foreach ($content['lessons'] as $courseLessonIndex => $courseLesson) {
            if ($courseLesson['type'] == 'song') {
                $content['lessons'][$courseLessonIndex]['lesson_count'] =
                    $this->contentService->countByParentIdWhereTypeIn($courseLesson['id'], ['song-part']);
                if ($content['lessons'][$courseLessonIndex]['lesson_count'] == 1) {
                    $content['lessons'][$courseLessonIndex]['lessons'] =
                        $this->contentService->getByParentId($courseLesson['id']);
                }
            }
            $duration += $courseLesson->fetch('fields.video.fields.length_in_seconds', 0);
            $totalXp += $courseLesson->fetch('fields.xp', 0);
        }

        $content['duration_in_seconds'] = $duration;
        $content['total_xp'] = $totalXp;

        return $content;
    }

    /**
     * @param mixed $content
     * @return mixed
     */
    private function attachFeaturedLessons(mixed $content, Request $request)
    : mixed {
        if ($content['type'] != 'instructor') {
            return $content;
        }

        $includedFields = [];
        $includedFields[] = 'instructor,'.$content['id'];
        $includedFields = array_merge($request->get('included_fields', []), $includedFields);
        $content['featured_lessons'] = $this->contentService->getFiltered(1, 4, '-published_on', [], [], [],
                                                                          ['is_featured,1'],
                                                                          $includedFields, [],
                                                                          []
        )
            ->results();

        return $content;
    }

    /**
     * @param mixed $content
     * @return mixed
     */
    private function attachComments(mixed $content)
    : mixed {
        CommentRepository::$availableContentId = $content['id'];
        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $content['comments'] = (new CommentTransformer())->transform($comments['results']);
        $content['total_comments'] = $comments['total_comments_and_results'];

        return $content;
    }

    /**
     * @param mixed $content
     * @param mixed $parent
     * @return mixed
     */
    private function attachDataFromParent(mixed $content, mixed $parent)
    : mixed {
        //add parent's instructors and resources to content
        $content['resources'] = array_merge($content['resources'] ?? [], $parent['resources'] ?? []);

        $content['style'] = $content->fetch('fields.style', null) ?? $parent->fetch('fields.style');
        $content['artist'] = $content->fetch('fields.artist', null) ?? $parent->fetch('fields.artist');
        $content['parent'] = $parent;

        //parent
        $content['parent'] = $parent;

        return $content;
    }

    /**
     * @param mixed $content
     * @return mixed
     */
    private function attachChildrens(mixed $content)
    : mixed {
        $childrenNameMapping = config('railcontent.children_name_mapping')[config('railcontent.brand')] ?? [];
        $childrenName = $childrenNameMapping[$content['type']] ?? 'lessons';
        $content["$childrenName"] = $this->contentService->getByParentId($content['id']);
        foreach ($content["$childrenName"] ?? [] as $index => $course) {
            $content["$childrenName"][$index]['lesson_count'] = $course['child_count'];
            if (isset($content['level_number']) && isset($course['position'])) {
                $content["$childrenName"][$index]['level_rank'] = $content['level_number'].'.'.$course['position'];
            }
        }

        return $content;
    }

    /**
     * @param mixed $content
     * @return mixed
     */
    private function attachPackData(mixed $content)
    {
        if (!in_array($content['type'], ['pack', 'semester-pack'])) {
            return $content;
        }

        $content = $this->isOwnedOrLocked($content);

        $content['thumbnail'] = $content->fetch('data.header_image_url');
        $content['pack_logo'] = $content->fetch('data.logo_image_url');

        $content['apple_product_id'] = $this->productProvider->getAppleProductId($content['slug']);
        $content['google_product_id'] = $this->productProvider->getGoogleProductId($content['slug']);

        $packPrice = $this->productProvider->getPackPrice($content['slug']);

        $content['full_price'] = $packPrice['full_price'] ?? 0;
        $content['price'] = $packPrice['price'] ?? 0;

        return $content;
    }

    private function isOwnedOrLocked(&$pack)
    {
        $pack['is_owned'] = false;
        $pack['is_locked'] = true;

        $isOwned = $this->productProvider->currentUserOwnsPack($pack['id']);
        if ($isOwned) {
            $pack['is_owned'] = true;
            $pack['is_locked'] = false;
        }

        if ($pack['is_new']) {
            $pack['is_locked'] = false;
        }

        return $pack;
    }

    /**
     * @param Request $request
     * @param $contentId
     * @return RedirectResponse
     */
    public function jumpToContinueContent(
        Request $request,
        $contentId
    ) {
        $nextContent = $this->contentService->getNextContentForParentContentForUser($contentId, user()->id);
        throw_if(empty($nextContent), new NotFoundException('Content not exists.'));

        return ResponseService::content($nextContent);
    }

    /**
     * @param ContentMetaRequest $request
     * @return JsonResponse
     */
    public function getContentMeta(ContentMetaRequest $request)
    {
        $contentMetaData = [];
        $showType = $request->get('content_type');
        $brand = $request->get('brand', config('railcontent.brand'));

        $metaData = config('railcontent.cataloguesMetadata')[$brand];
        if ($request->has('withCount')) {
            $episodesNumber = $this->contentService->countByTypes(
                [$showType],
                'type'
            );
        }

        if (array_key_exists($showType, $metaData)) {
            $contentMetaData = $metaData[$showType] ?? [];
            $contentMetaData['episodeNumber'] = $episodesNumber[$showType]['total'] ?? '';
        }

        return ResponseService::array($contentMetaData);
    }

}
