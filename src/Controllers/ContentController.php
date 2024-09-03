<?php

namespace Railroad\MusoraApi\Controllers;

use App\Decorators\Content\PackBundleDecorator;
use App\Decorators\Content\PackDecorator;
use Carbon\Carbon;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Railroad\Mailora\Services\MailService;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Decorators\StripTagDecorator;
use Railroad\MusoraApi\Exceptions\MusoraAPIException;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Exceptions\PlaylistException;
use Railroad\MusoraApi\Helpers\ButtonDataHelper;
use Railroad\MusoraApi\Requests\ArtistCollectionRequest;
use Railroad\MusoraApi\Requests\ContentMetaRequest;
use Railroad\MusoraApi\Requests\GenreCollectionRequest;
use Railroad\MusoraApi\Requests\SubmitQuestionRequest;
use Railroad\MusoraApi\Requests\SubmitStudentFocusFormRequest;
use Railroad\MusoraApi\Requests\SubmitVideoRequest;
use Railroad\MusoraApi\Services\DownloadService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\Decorator;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Decorators\ModeDecoratorBase;
use Railroad\Railcontent\Decorators\Video\ContentVimeoVideoDecorator;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
use Railroad\Railcontent\Enums\RecommenderSection;
use Railroad\Railcontent\Events\PlaylistItemLoaded;
use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentHierarchyRepository;
use Railroad\Railcontent\Repositories\ContentPermissionRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Requests\ContentFollowRequest;
use Railroad\Railcontent\Services\ArtistService;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentFollowsService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Services\FullTextSearchService;
use Railroad\Railcontent\Services\GenreService;
use Railroad\Railcontent\Services\MethodService;
use Railroad\Railcontent\Services\UserContentProgressService;
use Railroad\Railcontent\Services\UserPlaylistsService;
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
     * @var ProductProviderInterface
     */
    private $productProvider;
    /**
     * @var MethodService
     */
    private $methodService;

    private ContentPermissionRepository $contentPermissionRepository;
    private UserContentProgressService $userContentProgressService;
    private ArtistService $artistService;
    private GenreService $genreService;

    /**
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param ContentVimeoVideoDecorator $vimeoVideoDecorator
     * @param UserProviderInterface $userProvider
     * @param MailService $mailoraMailService
     * @param ContentHierarchyRepository $contentHierarchyRepository
     * @param FullTextSearchService $fullTextSearchService
     * @param StripTagDecorator $stripTagDecorator
     * @param DownloadService $downloadService
     * @param ContentFollowsService $contentFollowsService
     * @param UserPlaylistsService $userPlaylistsService
     * @param ProductProviderInterface $productProvider
     * @param MethodService $methodService
     * @param ContentPermissionRepository $contentPermissionRepository
     * @param UserContentProgressService $userContentProgressService
     * @param ArtistService $artistService
     * @param GenreService $genreService
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        ContentVimeoVideoDecorator $vimeoVideoDecorator,
        UserProviderInterface $userProvider,
        MailService $mailoraMailService,
        ContentHierarchyRepository $contentHierarchyRepository,
        FullTextSearchService $fullTextSearchService,
        StripTagDecorator $stripTagDecorator,
        DownloadService $downloadService,
        ContentFollowsService $contentFollowsService,
        UserPlaylistsService $userPlaylistsService,
        ProductProviderInterface $productProvider,
        MethodService $methodService,
        ContentPermissionRepository $contentPermissionRepository,
        UserContentProgressService $userContentProgressService,
        ArtistService $artistService,
        GenreService $genreService
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
        $this->userPlaylistsService = $userPlaylistsService;
        $this->productProvider = $productProvider;
        $this->methodService = $methodService;
        $this->contentPermissionRepository = $contentPermissionRepository;
        $this->userContentProgressService = $userContentProgressService;
        $this->artistService = $artistService;
        $this->genreService = $genreService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getRecommended(Request $request)
    {
        $userID = user()->id;
        $brand = $request->get('brand');
        $limit = $request->get('limit', 100);
        $filter = $request->get('filter', '');
        $page = $request->get('page', 1);
        $sections = match (strtolower($filter)) {
            'songs', 'song' => [RecommenderSection::Song],
            // everything but songs
            'lessons', 'lesson' => array_filter(RecommenderSection::cases(), function ($section) {
                return $section != RecommenderSection::Song;
            }),
            default => [],
        };
        $recommendedContent = $this->contentService->getRecommendedContent(
                       $userID,
                       $brand,
                       $sections,
            pageSize:  $limit,
            page:      $page,
        );

        return ResponseService::catalogue(
            $recommendedContent,
            $request
        );
    }

    public function getContentOptimised($contentId, Request $request, $playlistItemId = null, $withException = false)
    {
        array_push(ContentRepository::$availableContentStatues, ContentService::STATUS_ARCHIVED);
        $originalStatuses = ContentRepository::$availableContentStatues;
        array_push(ContentRepository::$availableContentStatues, ContentService::STATUS_UNLISTED);
        $pullFutureContent = ContentRepository::$pullFutureContent;
        if ($request->has('future')) {
            ContentRepository::$pullFutureContent = true;
        }
        if ($request->has('get_enrollment_content')) {
            ContentRepository::$getEnrollmentContent = $request->get('get_enrollment_content');
        }

        $content = $this->contentService->getById($contentId);
        if($withException) {
            throw_if(!$content, new NotFoundException('Content not exists.'));
        }

        ContentRepository::$availableContentStatues = $originalStatuses;
        if (!$content) {
            $userId = user()?->id;
            Log::warning("No content with id $contentId exists. (userId:$userId)");

            return response()->json(
                [
                    'success' => false,
                    'errors' => [
                        [
                            'title' => 'Entity not found.',
                            'detail' => 'No content with id '.$contentId.' exists.',
                        ],
                    ],
                ],
                404
            );
        }

        //Display all cohort lessons(including lessons that haven't been released) on the apps too
        if(($content['type'] == 'pack-bundle' || $content['type'] == 'pack') && (user()?->isEnrolledIntoCohort(\Arr::pluck($content['permissions'], 'permission_id')))){
            ContentRepository::$pullFutureContent = true;
        }

        $decoratorsEnabled = Decorator::$typeDecoratorsEnabled;
        Decorator::$typeDecoratorsEnabled = false;

        if ($playlistItemId) {
            $content['user_playlist_item_id'] = $playlistItemId;
        }

        $lessonContentTypes = array_diff(
            array_merge(
                config(
                    'railcontent.showTypes'
                )[config(
                    'railcontent.brand'
                )] ?? [],
                config('railcontent.singularContentTypes', []),
                [
                    'unit-part',
                    'assignment',
                ]
            ),
            [
                'song',
                'song-tutorial',
                'play-along',
                'play-along-part',
            ]
        );

        $content['resources'] = array_values($content['resources'] ?? []);

        if (in_array($content['type'], $lessonContentTypes)) {
            $parent =
                $this->contentService->getByChildId($content['id'])
                    ->first();

            if ($parent) {
                Decorator::$typeDecoratorsEnabled = true;
                ModeDecoratorBase::$decorationMode = ModeDecoratorBase::DECORATION_MODE_MAXIMUM;
                $collectionForDecoration = new Collection();
                $collectionForDecoration = $collectionForDecoration->merge([$parent]);
                $collectionForDecoration = Decorator::decorate($collectionForDecoration, 'content');
                Decorator::$typeDecoratorsEnabled = $decoratorsEnabled;

                $content = $this->attachDataFromParent($content, $parent);
                $parent['lessons'] = $this->contentService->getByParentId($parent['id']);
                $parent['lesson_count'] =
                    (!isset($parent['lesson_count'])) ? $parent['child_count'] ?? count($parent['lessons']) :
                        $parent['lesson_count'];
                $content = $this->addParentData($content, $parent);
                $content = $this->attachRelatedLessonsFromParent($parent, $content);
            } else {
                $content = $this->attachSiblingRelatedLessons($content, $request);
            }
        } elseif (in_array($content['type'], [
            'course',
            'learning-path',
            'learning-path-level',
            'learning-path-course',
            'pack',
            'pack-bundle',
            'semester-pack',
            'song',
            'song-tutorial',
            'unit',
            'play-along',
            'challenge',
        ])) {
            $content = $this->attachChildrens($content);

            //attach pack's details
            $content = $this->attachPackData($content);
            $content = $this->attachSiblingRelatedLessons($content, $request);

            if ($content['type'] == 'pack-bundle') {
                $parent =
                    $this->contentService->getByChildId($content['id'])
                        ->first();

                $parent = $this->attachPackData($parent);

                $content = $this->addParentData($content, $parent);
            }

            if (($content['child_count'] ?? 0) == 1) {
                $childrenNameMapping = config('railcontent.children_name_mapping')[config('railcontent.brand')] ?? [];
                $childrenName = $childrenNameMapping[$content['type']] ?? null;
                if ($childrenName) {
                    $initialContent = clone $content;
                    $content = $content[$childrenName][0];

                    $content = $this->addParentData($content, $initialContent);
                    $content = $this->attachDataFromParent($content, $initialContent);
                    $content = $this->attachChildrens($content);

                    $collectionForDecoration = new Collection();
                    $collectionForDecoration = $collectionForDecoration->merge([$content, $initialContent]);

                    Decorator::$typeDecoratorsEnabled = true;
                    ModeDecoratorBase::$decorationMode = ModeDecoratorBase::DECORATION_MODE_MAXIMUM;
                    $collectionForDecoration = Decorator::decorate($collectionForDecoration, 'content');
                    Decorator::$typeDecoratorsEnabled = $decoratorsEnabled;

                    $content['data'] = array_merge($content['data'] ?? [], $initialContent['data'] ?? []);
                    $content['fields'] = array_merge($content['fields'], $initialContent->fetch('*fields.style', []));
                    $content['related_lessons'] = $initialContent['related_lessons'];
                    $content['resources'] = array_values($content['resources']);
                }
            }
        } else {
            $content = $this->attachSiblingRelatedLessons($content, $request);
        }

        //attach song related lessons
        $content = $this->attachSongRelatedLessons($request, $content);

        //attach instructor's lessons
        $content = $this->attachInstructorLessons($content, $request);

        //attach instructor's featured lessons
        $content = $this->attachFeaturedLessons($content, $request);

        //attached low/high/original video ranges
        $content = $this->attachRanges($content);

        //vimeo endpoints
        $content =
            $this->vimeoVideoDecorator->decorate(new Collection([$content]))
                ->first();

        //strip HTML tags
        $this->stripTagDecorator->decorate(new Collection([$content]));

        $collectionForDecoration = new Collection();
        $collectionForDecoration = $collectionForDecoration->merge($content['related_lessons']);
        if (isset($content['parent'])) {
            $collectionForDecoration = $collectionForDecoration->merge([$content['parent']]);
        }
        if (isset($content['lessons'])) {
            $collectionForDecoration = $collectionForDecoration->merge($content['lessons']);
        }

        Decorator::$typeDecoratorsEnabled = true;
        ModeDecoratorBase::$decorationMode = ModeDecoratorBase::DECORATION_MODE_MAXIMUM;
        $collectionForDecoration = Decorator::decorate($collectionForDecoration, 'content');

        if ($playlistItemId) {
            $collectionForDecoration = new Collection();
            $collectionForDecoration = $collectionForDecoration->merge([$content]);
            $collectionForDecoration = Decorator::decorate($collectionForDecoration, 'playlist-item');
        }

        if ($content['type'] == 'assignment') {
            $content['parent'] =
                $this->vimeoVideoDecorator->decorate(new Collection([$content['parent']]))
                    ->first();
        }

        Decorator::$typeDecoratorsEnabled = $decoratorsEnabled;

        //attached comments on the content
        $content = $this->attachComments($content);

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
            return redirect()->route('mobile.musora-api.learning-path.lesson.show',
                                     ['lessonId' => $content['id'], 'brand' => $content['brand']]);
        } elseif ($content['type'] == 'pack') {
            return redirect()->route('mobile.musora-api.pack.show',
                                     ['packId' => $content['id'], 'brand' => $content['brand']]);
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
        ContentRepository::$pullFutureContent = $request->get('future', $pullFutureContent);

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
                $songsFromSameStyle = $this->contentService->getFiltered(
                    1,
                    19,
                    '-published_on',
                    [$content['type']],
                    [],
                    [],
                    ['style,'.$content->fetch('fields.style')]
                )['results'];

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

            $content['featured_lessons'] = $this->contentService->getFiltered(1, 4, '-published_on', [], [], [],
                                                                              ['is_featured,1'],
                                                                              $includedFields, [],
                                                                              []
            )
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
        $oldStatuses = ContentRepository::$availableContentStatues;
        $oldPullFutureContent = ContentRepository::$pullFutureContent;

        ContentRepository::$availableContentStatues = $request->get('statuses', $oldStatuses);
        ContentRepository::$pullFutureContent = $request->get('future') ? 1 : $oldPullFutureContent;

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;
        Decorator::$typeDecoratorsEnabled = false;
        ContentRepository::$pullFilterResultsOptionsAndCount = false;
        ContentRepository::$catalogMetaAllowableFilters = ['instructor', 'topic', 'style', 'artist'];
        $filterVersion = (config('railcontent.filter_version') == 'V2') ? 'V2' : '';

        if ($request->has('count_filter_items')) {
            ContentRepository::$countFilterOptionItems = $request->has('count_filter_items');
        }

        $types = $request->get('included_types', []);
        if (in_array('shows', $types)) {
            $types =
                array_merge($types, array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? []));
        }

        $requiredFields = $request->get('required_fields', []);
        if ($request->has('show_in_new_feed')) {
            $requiredFields = array_merge($requiredFields, ['show_in_new_feed,'.$request->get('show_in_new_feed')]);
        }

        if ($request->has('term')) {
            $requiredFields = array_merge($requiredFields, ['name,%'.$request->get('term').'%,string,like']);
            if ($request->get('sort') == '-score') {
                $request->merge(['sort' => 'published_on']);
            }
        }

        $included_fields = $request->get('included_fields', []);
        $group_by = $request->get('group_by', false);
        if ($request->has('title')) {
            if (in_array('instructor', $types)) {
                $requiredFields = array_merge($requiredFields, ['name,%'.$request->get('title').'%,string,like']);
            } elseif ($group_by == 'artist') {
                $requiredFields = array_merge($requiredFields, ['artist,%'.$request->get('title').'%,string,like']);
            } elseif ($group_by == 'style') {
                $requiredFields = array_merge($requiredFields, ['style,%'.$request->get('title').'%,string,like']);
            } elseif ($group_by == 'instructor') {
                $instructors = $this->contentService->getWhereTypeInAndStatusAndField(
                    ['instructor'],
                    ['published'],
                    'name',
                    '%'.$request->get('title').'%',
                    'string',
                    'LIKE'
                );
                if ($instructors->isEmpty()) {
                    $included_fields[] = 'instructor,0,integer,=';
                }
                foreach ($instructors->pluck('id') ?? [] as $instructor) {
                    $included_fields[] = 'instructor,'.$instructor.',integer,=';
                }
            } else {
                if (!empty($filterVersion)) {
                    $instructors = $this->contentService->getWhereTypeInAndStatusAndField(
                        ['instructor'],
                        'published',
                        'name',
                        '%'.$request->get('title').'%',
                        'string',
                        'LIKE'
                    );

                    $instructorIds = implode(
                        '-',
                        $instructors->pluck('id')
                            ->toArray()
                    );
                    $included_fields[] =
                        'title|artist|album|genre|instructor,%'.$request->get('title').'%,string,like,'.$instructorIds;
                } else {
                    $included_fields[] = 'title,%'.$request->get('title').'%,string,like';
                }
            }
        }

        $sortedBy = '-published_on';
        $catalogMetaAllowableFilters = ContentRepository::$catalogMetaAllowableFilters;

        foreach ($types as $type) {
            $type = $this->getContentTypeForMetaData($type);
            if (array_key_exists($type, config('railcontent.cataloguesMetadata.'.config('railcontent.brand')))) {
                $sortedBy = config('railcontent.cataloguesMetadata')[$type]['sortBy'] ?? $sortedBy;
                $catalogMetaAllowableFilters = config(
                    'railcontent.cataloguesMetadata.'.
                    config('railcontent.brand').
                    '.'.
                    $type.
                    '.allowableFiltersMobile'.
                    $filterVersion,
                    ContentRepository::$catalogMetaAllowableFilters
                );
            }
        }
        if (count($types) > 1) {
            $catalogMetaAllowableFilters = config(
                'railcontent.cataloguesMetadata.'.
                config('railcontent.brand').
                '.all.allowableFiltersMobile'.
                $filterVersion,
                ContentRepository::$catalogMetaAllowableFilters
            );
        }
        ContentRepository::$catalogMetaAllowableFilters = $catalogMetaAllowableFilters;

        if ($request->has('isAllNew')) {
            $requiredFields[] =
                'published_on,'.
                Carbon::now()
                    ->subMonth(3)
                    ->toDateTimeString().
                ',date,>=';
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
                $included_fields,
                $request->get('required_user_states', []),
                $request->get('included_user_states', []),
                $request->get('with_filters', true),
                false,
                $request->get('with_paginations', true),
                $request->get('only_subscribed', false),
                false,
                $request->get('group_by', false),
            );
        }

        $collectionForDecoration = new Collection();
        $collectionForDecoration = $collectionForDecoration->merge($results->results());

        Decorator::$typeDecoratorsEnabled = true;
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;
        $collectionForDecoration = Decorator::decorate($collectionForDecoration, 'content');

        if ($request->get('group_by', false)) {
            Decorator::decorate($collectionForDecoration, 'group');
        }

        return ResponseService::catalogue($results, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getInProgressContent(Request $request)
    {
        $oldStatuses = ContentRepository::$availableContentStatues;
        $oldPullFutureContent = ContentRepository::$pullFutureContent;
        ContentRepository::$availableContentStatues = $request->get('statuses', $oldStatuses);
        ContentRepository::$pullFutureContent = $request->get('future', $oldPullFutureContent);
        ContentRepository::$catalogMetaAllowableFilters = ['type', 'instructor'];
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $types = $request->get('included_types', []);
        if (in_array('shows', $types)) {
            $types =
                array_merge($types, array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? []));
        }
        $filterVersion = (config('railcontent.filter_version') == 'V2') ? 'V2' : '';
        if (count($types) == 1) {
            ContentRepository::$catalogMetaAllowableFilters = config(
                'railcontent.cataloguesMetadata.'.
                config('railcontent.brand').
                '.in-progress.allowableFiltersMobile'.
                $filterVersion,
                ContentRepository::$catalogMetaAllowableFilters
            );
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
                $request->get('with_filters', true),
                false,
                $request->get('with_paginations', true),
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
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllScheduleOptimised(Request $request)
    {
        $scheduleEvents = $this->contentService->getScheduledContent(
            $request->get('brand'),
            $request->get('limit'),
            $request->get('page', 1)
        );

        return ResponseService::catalogue($scheduleEvents, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getComingSoon(Request $request)
    {
        ContentRepository::$availableContentStatues = [ContentService::STATUS_PUBLISHED];
        $newSongs = $this->contentService->getFiltered(
            $request->get('page', 1),
            $request->get('limit', 10),
            "published_on",
            includedTypes: ['song'],
            getFutureContentOnly: true,
        );
        return ResponseService::catalogue($newSongs, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLeaving(Request $request)
    {
        $removed = $this->contentService->getNextQuarterRemoved(
            $request->get('page', 1),
            $request->get('limit', 10),
        );
        return ResponseService::catalogue($removed, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getReturning(Request $request)
    {
        $comingSoon = $this->contentService->getNextQuarterReturning(
            $request->get('page', 1),
            $request->get('limit', 10),
        );

        return ResponseService::catalogue($comingSoon, $request);
    }

    public function getLiveScheduleOptimised(Request $request)
    {
        ContentRepository::$availableContentStatues = [
            ContentService::STATUS_PUBLISHED,
            ContentService::STATUS_SCHEDULED,
        ];

        ContentRepository::$pullFutureContent = true;

        $liveEvents = $this->contentService->getWhereTypeInAndStatusInAndPublishedOnOrderedAndPaginated(
            config('railcontent.liveContentTypes'),
            [ContentService::STATUS_SCHEDULED],
            Carbon::now()
                ->subHours(24)
                ->toDateTimeString(),
            '>',
            'live_event_start_time',
            'asc',
            [],
            $request->get('limit'),
            $request->get('page')
        );

        return ResponseService::catalogue($liveEvents, $request);
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
        $brand = $request->get('brand', config('railcontent.brand', ''));

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
        $brand = $request->get('brand', config('railcontent.brand', ''));
        $lines = [
            '<strong>student progress info:</strong> '.
            'https://'.
            'admin.musora.com/admin/user-progress-info/'.
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
        $input['recipient'] = config('mailora.'.$brand.'.submit-student-focus-recipient', "support@musora.com");
        $input['success'] = config('musora-api.submit_student_focus_success_message.'.$brand);
        $input['sender'] = $currentUser->getEmail();

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
     * @param $parent
     * @param mixed $content
     * @return mixed
     */
    private function attachRelatedLessonsFromParent($parent, mixed $content)
    : mixed {
        //related lessons
        $parentChildren = $parent['lessons'] ?? [];

        //add length_in_seconds
        foreach ($parentChildren as $index => $child) {
            $parentChildren[$index]['length_in_seconds'] = $child->fetch('fields.video.fields.length_in_seconds', 0);
        }
        $content['related_lessons'] = $this->getParentChildTrimmed($parentChildren, $content);

        //previous/next lesson
        $lessonHierarchyContent =
            $parentChildren->where('id', $content['id'])
                ->first();
        $content['next_lesson'] = $parentChildren->getMatchOffset($lessonHierarchyContent, 1);
        $content['previous_lesson'] = $parentChildren->getMatchOffset($lessonHierarchyContent, -1);
        if ($content['type'] == 'learning-path-lesson') {
            $learningPath = \Arr::last($content->getParentContentData());
            $nextPrevLessons = $this->methodService->getNextAndPreviousLessons($content['id'], $learningPath->id);
            $content['next_lesson'] = $nextPrevLessons->getNextLesson();
            $content['previous_lesson'] = $nextPrevLessons->getPreviousLesson();
        }

        return $content;
    }

    /**
     * @param mixed $content
     * @param Request $request
     * @return mixed
     */
    private function attachSiblingRelatedLessons(mixed $content, Request $request)
    : mixed {
        $originalStatuses = ContentRepository::$availableContentStatues;
        $sort = 'published_on';

        if ($content['type'] == 'rhythmic-adventures-of-captain-carson' ||
            $content['type'] == 'diy-drum-experiments' ||
            $content['type'] == 'in-rhythm') {
            $sort = 'sort';
        }

        ContentRepository::$availableContentStatues = array_diff(ContentRepository::$availableContentStatues, [ContentService::STATUS_UNLISTED]);
        $parentChildren = $this->contentService->getFiltered(
            $request->get('page', 1),
            $request->get('limit', 10),
            '-'.$sort,
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
        ContentRepository::$availableContentStatues = $originalStatuses;

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
        if (!in_array($content['type'], ['song', 'song-tutorial'])) {
            return $content;
        }

        $songsFromSameArtist = $this->contentService->getFiltered(
            $request->get('page', 1),
            $request->get('limit', 10),
            'title',
            [$content['type']],
            [],
            [],
            ['artist,'.$content->fetch('fields.artist')],
            [],
            [],
            [],
            false
        )['results'];

        // remove requested song if in related lessons, part one of two
        foreach ($songsFromSameArtist as $songFromSameArtistIndex => $songFromSameArtist) {
            if ($content['id'] == $songFromSameArtist['id']) {
                unset($songsFromSameArtist[$songFromSameArtistIndex]);
            }
        }

        $songsFromSameStyle = new Collection();

        if (count($songsFromSameArtist) < 10) {
            $styles = $content->fetch('*fields.style', []);
            $styleField = [];
            foreach ($styles as $style) {
                $styleField[] = 'style,'.$style['value'];
            }

            $type = $content['type'];
            if ($content['type'] == 'song-part') {
                $type = 'song';
            }

            $songsFromSameStyle = $this->contentService->getFiltered(
                1,
                19,
                'title',
                [$type],
                [],
                [],
                [],
                $styleField,
                [],
                [],
                false
            )['results'];

            // remove requested song if in related lessons, part two of two (because sometimes in $songsFromSameStyle)
            foreach ($songsFromSameStyle as $songFromSameStyleIndex => $songFromSameStyle) {
                if ($content['id'] == $songFromSameStyle['id']) {
                    unset($songsFromSameStyle[$songFromSameStyleIndex]);
                }
            }

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

        if (empty($content['related_lessons'])) {
            return $this->attachSiblingRelatedLessons($content, $request);
        }
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

        if ($request->filled('title')) {
            $requiredFields = array_merge($requiredFields, ['title,%'.$request->get('title').'%,string,like']);
        }

        if ($request->has('count_filter_items')) {
            ContentRepository::$countFilterOptionItems = $request->has('count_filter_items');
        }

        ContentRepository::$availableContentStatues =
            $request->get('statuses', [ContentService::STATUS_PUBLISHED, ContentService::STATUS_SCHEDULED]);
        ContentRepository::$pullFutureContent = $request->has('future');
        $filterVersion = (config('railcontent.filter_version') == 'V2') ? 'V2' : '';
        ContentRepository::$catalogMetaAllowableFilters = config(
            'railcontent.cataloguesMetadata.'.
            config('railcontent.brand').
            '.coach-lessons.allowableFiltersMobile'.
            $filterVersion,
            ContentRepository::$catalogMetaAllowableFilters
        );

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

        if (array_key_exists('type', $content['lessons_filter_options_v2'])) {
            $content['lessons_filter_options_v2']['content_type'] = $content['lessons_filter_options_v2']['type'];
        }
        $content['lessons_filter_options_v2']['progress'] = ['All', 'In Progress', 'Completed'];
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
        $content['featured_lessons'] =
            $this->contentService->getFiltered(1, 4, '-published_on', [], [], [], ['is_featured,1'], $includedFields,
                                               [], [])
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
        if (!$parent) {
            return $content;
        }
        //add parent's instructors and resources to content
        $content['resources'] = array_merge($content['resources'] ?? [], $parent['resources'] ?? []);
        $content['instructors'] =
            array_values(array_unique(array_merge($content['instructors'] ?? [], $parent['instructors'] ?? [])));

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
        $duration = 0;
        $totalXp = 0;
        $chilrenCount = 0;
        foreach ($content["$childrenName"] ?? [] as $index => $course) {
            if ($course['type'] == 'assignment') {
                unset($content["$childrenName"][$index]);
                continue;
            }
            $duration += $course->fetch('fields.video.fields.length_in_seconds', 0);
            $totalXp += $course->fetch('fields.xp', 0);
            $content["$childrenName"][$index]['length_in_seconds'] =
                $course->fetch('fields.video.fields.length_in_seconds', 0);
            $content["$childrenName"][$index]['lesson_count'] = $course['child_count'];
            if (isset($content['level_number']) && isset($course['hierarchy_position_number'])) {
                $content["$childrenName"][$index]['level_rank'] =
                    $content['level_number'].'.'.$course['hierarchy_position_number'];
            }
            $chilrenCount++;
        }

        $content['length_in_seconds'] = $content->fetch('fields.video.fields.length_in_seconds', $duration);
        if (!isset($content['lesson_count'])) {
            $content['lesson_count'] = $content['child_count'] = $chilrenCount;
        }
        $content['total_xp'] = $totalXp;

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

    /**
     * @param mixed $content
     * @param mixed $parent
     * @return mixed
     */
    private function addParentData(mixed $content, mixed $parent)
    {
        if (in_array($parent['type'], ['pack', 'semester-pack'])) {
            //            $content['is_owned'] = $parent['is_owned'];
            //            $content['is_locked'] = $parent['is_locked'];

            $content['thumbnail'] = $parent['thumbnail'] ?? '';
            $content['pack_logo'] = $parent['pack_logo'] ?? '';

            $content['apple_product_id'] = $parent['apple_product_id'] ?? null;
            $content['google_product_id'] = $parent['google_product_id'] ?? null;

            $content['full_price'] = $parent['full_price'] ?? 0;
            $content['price'] = $parent['price'] ?? 0;
        }

        $contentRows = array_merge(
            $content['coaches'] ?? [],
            $parent['coaches'] ?? []
        );

        $coachIds = array_unique(array_column($contentRows, 'id'));

        $content['instructor'] = array_intersect_key($content['instructor'] ?? [], $coachIds);

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

        if ($pack['is_new'] ?? false) {
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
        if (!$nextContent) {
            $userId = user()->id;

            Log::warning(
                "No content with id $contentId exists. (userId:$userId  - method:: jumpToContinueContent:$contentId)"
            );

            return response()->json(
                [
                    'success' => false,
                    'errors' => [
                        [
                            'title' => 'Not found',
                            'detail' => 'Content not exists.',
                        ],
                    ],
                ],
                404
            );
        }

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

        $contentType =
            ($showType == 'live') ? 'live-streams' : (($showType == 'student-review') ? 'student-reviews' : $showType);
        if (array_key_exists($contentType, $metaData)) {
            $contentMetaData = $metaData[$contentType] ?? [];
            $contentMetaData['episodeNumber'] = $episodesNumber[$contentType]['total'] ?? '';
        }
        if (array_key_exists('trailer1', $contentMetaData)) {
            $trailer1 = $this->productProvider->getVimeoEndpoints($contentMetaData['trailer1']);
            if ($trailer1) {
                $contentMetaData['trailer1'] = [
                    'vimeo_video_id' => $trailer1['vimeo_video_id'] ?? null,
                    'video_playback_endpoints' => $trailer1['video_playback_endpoints'] ?? [],
                    'length_in_seconds' => $trailer1['length_in_seconds'] ?? 0,
                ];
            }
        }
        if (array_key_exists('trailer2', $contentMetaData)) {
            $trailer2 = $this->productProvider->getVimeoEndpoints($contentMetaData['trailer2']);
            if ($trailer2) {
                $contentMetaData['trailer2'] = [
                    'vimeo_video_id' => $trailer2['vimeo_video_id'] ?? null,
                    'video_playback_endpoints' => $trailer2['video_playback_endpoints'] ?? [],
                    'length_in_seconds' => $trailer2['length_in_seconds'] ?? 0,
                ];
            }
        }

        if(!\user()->hasSongsAccess($brand) && isset($contentMetaData['tabs'])) {
            $contentMetaData['tabs'] = array_values(
                array_filter($contentMetaData['tabs'], function ($tab) {
                    return $tab['name'] != 'Songs';
                })
            );
        }

        return ResponseService::array($contentMetaData);
    }

    public function getGuitarQuestMap()
    {
        PackBundleDecorator::$skip = true;
        PackDecorator::$skip = true;
        PackBundleDecorator::$skip = true;

        ContentRepository::$pullFutureContent = true;

        $levelName = [
            'Song in an hour',
            'The Jam Session',
            'Music Video Shoot',
            'Campfire Chords',
            'Selling Out',
            'The Punk Show',
            'The Recording Studio',
            'Play Anything',
            'The Final Boss',
        ];

        $packSlug = 'guitar-quest';
        $pack =
            $this->contentService->getBySlugAndType($packSlug, 'pack')
                ->first();
        throw_if(empty($pack), new NotFoundException('Content not exists.'));

        $packBundles = $this->contentService->getByParentId($pack['id']);
        $totalCompletedLevels = 0;
        $totalCompletedLessons = 0;

        foreach ($packBundles as $index => $packBundle) {
            $packLessons = $this->contentService->getByParentId($packBundle['id']);
            foreach ($packLessons as $lesson) {
                if ($lesson->fetch('progress_state') == 'completed') {
                    $totalCompletedLessons += 1;
                }
            }

            $levels[] = [
                'id' => $packBundle['id'],
                'name' => $levelName[$index] ?? '',
                'thumb_url' => 'https://d122ay5chh2hr5.cloudfront.net/guitarquest/assets/level-'.($index + 1).'.png',
                'completed' => $packBundle['completed'],
            ];

            if ($packBundle->fetch('progress_state') == 'completed') {
                $totalCompletedLevels += 1;
            }
        }

        $response = [
            'levels' => $levels,
            'total_completed_challenges' => $totalCompletedLevels,
            'total_completed_lessons' => $totalCompletedLessons,
        ];

        return ResponseService::array($response);
    }

    /**
     * @param $content
     * @return mixed
     */
    public function attachRanges($content)
    {
        $ranges = ['low', 'original', 'high'];
        $rangeIds = [];

        foreach ($ranges as $range) {
            if ($content[$range.'_video']) {
                $fetchFieldTemplate = 'fields.%s_video.fields.youtube_video_id';
                $fetchFieldString = sprintf($fetchFieldTemplate, $range);
                $rangeIds[$range] = $content->fetch($fetchFieldString);
            }
        }
        $content['ranges'] = $rangeIds;

        return $content;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getRoutinesTrailer(Request $request)
    {
        ContentRepository::$bypassPermissions = true;
        ContentRepository::$availableContentStatues = false;
        ContentRepository::$pullFutureContent = true;

        //  $content = $this->getContentOptimised(config('musora-api.routine_trailer'), $request);
        $content = $this->productProvider->getVimeoEndpoints(config('musora-api.routine_trailer'));

        $response = [
            'vimeo_video_id' => $content['vimeo_video_id'] ?? null,
            'video_playback_endpoints' => $content['video_playback_endpoints'] ?? [],
            'length_in_seconds' => $content['length_in_seconds'] ?? 0,
        ];

        return ResponseService::array($response);
    }

    public function getVimeoData(Request $request, $vimeoId)
    {
        $content = $this->productProvider->getVimeoEndpoints($vimeoId);
        $response = [
            'vimeo_video_id' => $content['vimeo_video_id'] ?? null,
            'video_playback_endpoints' => $content['video_playback_endpoints'] ?? [],
            'length_in_seconds' => $content['length_in_seconds'] ?? 0,
        ];

        return ResponseService::array($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getHomepageBanner(Request $request)
    {
        $carouselSlides = $this->productProvider->carousel($request->get('is_workouts_page') ?? false);
        $response = [];

        if (config('musora-api.api.version') == 'v3' ||
            config('musora-api.api.version') == 'v4' ||
            config('musora-api.api.version') == 'v5') {
            foreach ($carouselSlides as $index => $slide) {
                $response['slide_'.$index] = [
                    'name' => $slide['title'],
                    //'title' => $slide['subtitle'] ,
                    'logo' => $slide['logo'],
                    'description' => $slide['description'],
                    'video_src' => $slide['video_src'],
                    'desc_color' => $slide['desc_color'],
                    'title_color' => $slide['title_color'],
                    'subtitle_color' => $slide['subtitle_color'],
                    'btn_light_mode' => $slide['btn_light_mode'],
                    'thumbnail_url' => $slide['mobile_img'] ?? $slide['img'],
                    'tablet_thumbnail_url' => $slide['tablet_img'] ?? $slide['img'],
                    'draft' => $slide['draft'] ?? false,
                    'difficulty' => $slide['skill_level'] ?? null,

                ];
                if (!empty($slide['subtitle'])) {
                    $response['slide_'.$index]['title'] = $slide['subtitle'];
                }
                if (($slide['trailer'])) {
                    $response['slide_'.$index]['trailer'] = $slide['trailer'];
                }
                if (($slide['trailer_button_1'])) {
                    $response['slide_'.$index]['trailer_button_1'] = $slide['trailer_button_1'];
                }
                if ($slide['primary_cta_url']) {
                    $response['slide_'.$index]['first_button'] =
                        ButtonDataHelper::getButtonData($slide['primary_cta_url'], $slide['primary_cta_text']);
                }
                if ($slide['cta_url'] && !($slide['primary_cta_url'])) {
                    $response['slide_'.$index]['first_button'] =
                        ButtonDataHelper::getButtonData($slide['cta_url'], $slide['cta_text']);
                }
                if ($slide['secondary_cta_url'] || $slide['secondary_cta_text']) {
                    $response['slide_'.$index]['second_button'] =
                        ButtonDataHelper::getButtonData($slide['secondary_cta_url'], $slide['secondary_cta_text']);
                }
            }

            return ResponseService::array($response);
        }

        foreach ($carouselSlides as $index => $slide) {
            $response['slide_'.$index] = [
                'name' => $slide['title'],
                'thumbnail_url' => $slide['tablet_img'] ?? $slide['img'],
                'tablet_thumbnail_url' => $slide['tablet_img'] ?? $slide['img'],
            ];
            if (!empty($slide['subtitle'])) {
                $response['slide_'.$index]['title'] = $slide['subtitle'];
            }
            if (($slide['trailer'])) {
                $response['slide_'.$index]['trailer'] = $slide['trailer'];
            }

            if ($slide['primary_cta_url']) {
                $firstButton = ButtonDataHelper::getButtonData($slide['primary_cta_url'], $slide['primary_cta_text']);
                $response['slide_'.$index] = array_merge($response['slide_'.$index], $firstButton);
            }

            if ($slide['cta_url']) {
                $firstButton = ButtonDataHelper::getButtonData($slide['cta_url'], $slide['cta_text']);
                $response['slide_'.$index] = array_merge($response['slide_'.$index], $firstButton);
            }
        }

        return ResponseService::array($response);
    }

    private function getContentTypeForMetaData($type)
    {
        switch ($type) {
            case 'course':
                return 'courses';
            case 'song':
                return 'songs';
            case 'rudiment':
                return 'rudiments';
            case 'play-along':
                return 'play-alongs';
            case 'instructor':
                return 'coaches';
        }

        return $type;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getPlaylistItem(Request $request)
    {
        $oldFutureContent = ContentRepository::$pullFutureContent;

        $currentUser = $this->userProvider->getCurrentUser();

        ContentRepository::$pullFutureContent = true;

        $playlistContent = $this->userPlaylistsService->getPlaylistItemById($request->get('user_playlist_item_id'));
        throw_if(!$playlistContent, new NotFoundException('Playlist item not exists.'));

        $playlist = $this->userPlaylistsService->getPlaylist($playlistContent['user_playlist_id']);
        throw_if(
            ($playlist == -1),
            new PlaylistException("You don’t have access to this playlist", 'Private Playlist')
        );
        if ($playlist == -2) {
            $playlist = $this->userPlaylistsService->getPlaylist($playlistContent['user_playlist_id'], false);
            $userDisplayName = $playlist['user']['display_name'] ?? '';
            throw new PlaylistException(
                "You've previously blocked the user who owns this playlist. Unblock ".
                $userDisplayName.
                " to access this playlist.", 'Blocked Playlist'
            );
        }
        throw_if(!$playlist, new PlaylistException("Playlist doesn't exist.", "Playlist doesn't exist."));

        try {
            $content = $this->getContentOptimised($playlistContent['content_id'], $request, $playlistContent['id'], withException: true);
        } catch (\Exception $e) {
            $playlistLessons =
                $this->userPlaylistsService->getUserPlaylistContents($playlistContent['user_playlist_id']);

            $playlistItem =
                $playlistLessons->where('user_playlist_item_id', $request->get('user_playlist_item_id'))
                    ->first();

            $message = $playlistItem['need_access_message'] ?? 'No access';
            $extraData = [
                "item_title" => $playlistItem['title'],
                "item_type" => $playlistItem['type'],
                "thumbnail_url" => $playlistItem['thumbnail_url'] ?? '',
                "parent" => $playlistItem['parent'] ?? null,
                "learn_more_link" => "https://musora.helpscoutdocs.com/article/1034-musora-membership-options#membershiptype",
            ];
            throw new PlaylistException($message, "Content Unavailable", $extraData);
        }

        $content['start_second'] = $playlistContent['start_second'] ?? null;
        $content['end_second'] = $playlistContent['end_second'] ?? null;
        $content['user_playlist_item_id'] = $playlistContent['id'] ?? null;
        $content['user_playlist_item_position'] = $playlistContent['position'] ?? null;
        $content['user_playlist_item_extra_data'] = $playlistContent['extra_data'] ?? null;
        $content['content_name'] = $playlistContent['content_name'] ?? null;
        $content['playlist_item_name'] = $playlistContent['playlist_item_name'] ?? null;

        if (!empty($playlistContent['extra_data'])) {
            foreach (json_decode($playlistContent['extra_data'], true) as $key => $value) {
                $content[$key] = $value;
            }
        }

        if (($content['type'] == 'song') && (count($content['assignments'] ?? []) > 0)) {
            $content['length_in_seconds'] = $content['assignments'][0]['length_in_seconds'] ?? 0;
        }

        if (!isset($content['parent']) || ($content['type'] == 'assignment')) {
            unset($content['related_lessons']);
        }

        if (isset($content['parent']) &&
            (isset($content['parent']['child_count']) && ($content['parent']['child_count'] == 1)) &&
            ($content['type'] != 'assignment')) {
            unset($content['parent']);
        }

        if (isset($content['parent'])) {
            $decoratorsEnabled = Decorator::$typeDecoratorsEnabled;
            Decorator::$typeDecoratorsEnabled = false;
            $parent = $this->contentService->getById($content['parent']['id']);
            if ($parent->getParentContentData()) {
                $route = [];
                $parentContentData = $parent->getParentContentData();
                foreach ($parentContentData as $parent) {
                    switch ($parent->type) {
                        case 'learning-path':
                            $route[] = 'Method';
                            break;
                        case 'learning-path-level':
                            $route[] = 'L'.$parent->position;
                            break;
                        case 'song':
                            break;
                        case 'play-along':
                            break;
                        case 'edge-pack':
                            break;
                        default:
                            $parentE = $this->contentService->getById($parent->id);
                            $route[] = $parentE['title'] ?? '';
                            break;
                    }
                }
                $content['parent']['route'] = $route;
            }
            Decorator::$typeDecoratorsEnabled = $decoratorsEnabled;
        }
        ContentRepository::$pullFutureContent = $oldFutureContent;

        event(
            new PlaylistItemLoaded(
                $playlistContent['user_playlist_id'], $playlistContent['id'], $playlistContent['position']
            )
        );

        return ResponseService::array($content);
    }

    /**
     * @param Request $request
     * @param $playlistId
     * @return JsonResponse
     * @throws Throwable
     */
    public function jumpToPlay(Request $request, $playlistId)
    {
        $playbackItemId = $this->productProvider->getPlaybackItemId($playlistId);
        if (!$playbackItemId) {
            return ResponseService::empty();
        }

        $request->merge(['user_playlist_item_id' => $playbackItemId]);

        return $this->getPlaylistItem($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function report(Request $request)
    {
        $currentUser = $this->userProvider->getCurrentUser();
        $brand = $request->get('brand', config('railcontent.brand', ''));
        $contentId = $request->get('content_id');
        $content = $this->contentService->getById($contentId);
        $issue = $request->get('issue');

        $lines = [
            '<strong>Lesson:</strong> <a href="'.$content['url'].'">'.$content['title'].'</a>',
        ];
        $lines[] = '<strong>'.'User'.':</strong> '.$currentUser->getDisplayName().' ('.$currentUser->getEmail().')';
        $lines[] = '<strong>'.'Issue'.':</strong> '.$issue;

        $input['subject'] = $input['alert'] = 'Lesson Error Report';

        $input['lines'] = $lines;
        $input['logo'] = config('musora-api.brand_logo_path_for_email.'.$brand);
        $input['type'] = 'layouts/inline/alert';
        $input['recipient'] = "support@musora.com";
        $input['success'] = 'Your issue has been reported. A mentor will review your issue soon.';
        $input['sender'] = $currentUser->getEmail();

        return $this->sendSecure($input);
    }

    public function genreCollectionPage(GenreCollectionRequest $request)
    {
        $types = $request->get('included_types');
        $genre = $request->get('style');
        $brand = $request->get('brand', config('railcontent.brand', ''));
        if ($request->has('count_filter_items')) {
            ContentRepository::$countFilterOptionItems = $request->has('count_filter_items');
        }
        $catalogMetaAllowableFilters = ContentRepository::$catalogMetaAllowableFilters;
        $filterVersion = (config('railcontent.filter_version') == 'V2') ? 'V2' : '';
        if (in_array('shows', $types)) {
            $types =
                array_merge($types, array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? []));
        }
        foreach ($types as $type) {
            $type = $this->getContentTypeForMetaData($type);
            if (array_key_exists($type, config('railcontent.cataloguesMetadata.'.$brand))) {
                $catalogMetaAllowableFilters = config(
                    'railcontent.cataloguesMetadata.'.$brand.'.'.$type.'.allowableFiltersMobile'.$filterVersion,
                    ContentRepository::$catalogMetaAllowableFilters
                );
            }
        }
        if (count($types) > 1) {
            $catalogMetaAllowableFilters = config(
                'railcontent.cataloguesMetadata.'.$brand.'.all.allowableFiltersMobile'.$filterVersion,
                ContentRepository::$catalogMetaAllowableFilters
            );
        }
        ContentRepository::$catalogMetaAllowableFilters = $catalogMetaAllowableFilters;

        $requiredFields = $request->get('required_fields', []);
        $requiredFields = array_merge($requiredFields, ['style,'.$genre]);
        if ($request->has('title')) {
            $requiredFields = array_merge($requiredFields, ['title,%'.$request->get('title').'%,string,like']);
        }

        $lessons = $this->contentService->getFiltered(
            $request->get('page', 1),
            $request->get('limit', 12),
            $request->get('sort', '-popularity'),
            $types,
            $request->get('slug_hierarchy', []),
            $request->get('required_parent_ids', []),
            $requiredFields,
            $request->get('included_fields', []),
            $request->get('required_user_states', []),
            $request->get('included_user_states', []),
            $request->get('with_filters', true)
        );
        $genreData = $this->genreService->getByName($genre);

        $content = new ContentEntity();
        $content['name'] = $genre;
        $content['type'] = 'style';
        $content['thumbnail_url'] =
            $genreData['head_shot_picture_url']
            ??
            config('railcontent.default_avatar_style')[config('railcontent.brand', 'drumeo')];
        $content['lessons'] = $lessons['results'];
        $content['lessons_filter_options'] = $lessons['filter_options'];
        if ($request->get('with_filters', true)) {
            $content['lesson_count'] = $lessons['total_results'];
        }
        $content['total_results'] = $lessons['total_results'];

        return ResponseService::content($content);
    }

    public function artistCollectionPage(ArtistCollectionRequest $request)
    {
        $types = $request->get('included_types');
        $artist = $request->get('artist');
        $brand = $request->get('brand', config('railcontent.brand', ''));
        if ($request->has('count_filter_items')) {
            ContentRepository::$countFilterOptionItems = $request->has('count_filter_items');
        }
        $catalogMetaAllowableFilters = ContentRepository::$catalogMetaAllowableFilters;
        if (in_array('shows', $types)) {
            $types =
                array_merge($types, array_values(config('railcontent.showTypes')[config('railcontent.brand')] ?? []));
        }
        $filterVersion = (config('railcontent.filter_version') == 'V2') ? 'V2' : '';
        foreach ($types as $type) {
            $type = $this->getContentTypeForMetaData($type);
            if (array_key_exists($type, config('railcontent.cataloguesMetadata.'.$brand))) {
                $catalogMetaAllowableFilters = config(
                    'railcontent.cataloguesMetadata.'.$brand.'.'.$type.'.allowableFiltersMobile'.$filterVersion,
                    ContentRepository::$catalogMetaAllowableFilters
                );
            }
        }
        if (count($types) > 1) {
            $catalogMetaAllowableFilters = config(
                'railcontent.cataloguesMetadata.'.$brand.'.all.allowableFiltersMobile'.$filterVersion,
                ContentRepository::$catalogMetaAllowableFilters
            );
        }
        ContentRepository::$catalogMetaAllowableFilters = $catalogMetaAllowableFilters;

        $requiredFields = $request->get('required_fields', []);
        $requiredFields = array_merge($requiredFields, ['artist,'.$artist]);
        if ($request->has('title')) {
            $requiredFields = array_merge($requiredFields, ['title,%'.$request->get('title').'%,string,like']);
        }

        $lessons = $this->contentService->getFiltered(
            $request->get('page', 1),
            $request->get('limit', 12),
            $request->get('sort', '-popularity'),
            $types,
            $request->get('slug_hierarchy', []),
            $request->get('required_parent_ids', []),
            $requiredFields,
            $request->get('included_fields', []),
            $request->get('required_user_states', []),
            $request->get('included_user_states', []),
            $request->get('with_filters', true)
        );
        $totalPlays = $this->userContentProgressService->countByArtistTypesUserProgress(
            ['song'],
            $artist
        );
        $artistData = $this->artistService->getByName($artist);

        $content = new ContentEntity();
        $content['name'] = $artist;
        $content['type'] = 'artist';
        $content['thumbnail_url'] =
            $artistData['head_shot_picture_url']
            ??
            config('railcontent.default_avatar_style')[config('railcontent.brand', 'drumeo')];
        $content['plays'] = $totalPlays;
        $content['lessons'] = $lessons['results'];
        $content['lessons_filter_options'] = $lessons['filter_options'];
        if ($request->get('with_filters', true)) {
            $content['lesson_count'] = $lessons['total_results'];
        }
        $content['total_results'] = $lessons['total_results'];

        return ResponseService::content($content);
    }

    public function artistsPage(Request $request)
    {
        ContentRepository::$availableContentStatues =
            [ContentService::STATUS_PUBLISHED, ContentService::STATUS_SCHEDULED];
        ContentRepository::$pullFutureContent = false;
        ContentRepository::$getFutureScheduledContentOnly = false;

        $artists = $this->contentService->getArtists();

        return ResponseService::array($artists);
    }
}
