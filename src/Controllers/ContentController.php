<?php

namespace Railroad\MusoraApi\Controllers;

use Carbon\Carbon;
use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\Mailora\Providers\MailoraServiceProvider;
use Railroad\Mailora\Services\MailService;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\MusoraApi\Requests\SubmitQuestionRequest;
use Railroad\MusoraApi\Requests\SubmitStudentFocusFormRequest;
use Railroad\MusoraApi\Requests\SubmitVideoRequest;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentService;
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
     * ContentController constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param VimeoVideoSourcesDecorator $vimeoVideoDecorator
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        VimeoVideoSourcesDecorator $vimeoVideoDecorator,
        UserProviderInterface $userProvider,
        MailService $mailoraMailService
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
        $this->userProvider = $userProvider;
        $this->mailoraMailService = $mailoraMailService;
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

        $isDownload = $request->get('download', false);

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

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $parentChildrenTrimmed = $this->getParentChildTrimmed($parentChildren, $content);

        $content['related_lessons'] = $parentChildrenTrimmed;

        $content = $this->attachNextPrevLesson($parent, $content, $parentChildren);

        CommentRepository::$availableContentId = $content['id'];
        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $content['comments'] = (new CommentTransformer())->transform($comments['results']);
        $content['total_comments'] = $comments['total_results'];

        if ($isDownload && array_key_exists('lessons', $content)) {
            //for download feature we need lessons assignments, vimeo urls, comments
            $this->vimeoVideoDecorator->decorate(new Collection($content['lessons']));

            $this->commentService->attachCommentsToContents($content['lessons']);

            $parentChildren = $this->contentService->getByParentId($content['id']);

            foreach ($content['lessons'] as $lessonIndex => $lesson) {
                $content['lessons'][$lessonIndex]['related_lessons'] = $parentChildren;
                $content['lessons'][$lessonIndex]['previous_lesson'] = $parentChildren[$lessonIndex - 1] ?? null;
                $content['lessons'][$lessonIndex]['next_lesson'] = $parentChildren[$lessonIndex + 1] ?? null;
            }
        }

        $content =
            $this->vimeoVideoDecorator->decorate(new Collection([$content]))
                ->first();

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
     * @return array|mixed|\Railroad\Railcontent\Entities\ContentEntity[]|Collection
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
     * @throws \Exception
     */
    public function submitQuestion(SubmitQuestionRequest $request)
    {
        $input = $request->all();
        $currentUser = $this->userProvider->getCurrentUser();
        $brand = config('railcontent.brand','');

        $input['subject'] = config('musora-api.submit_question_subject.'.$brand,'')
            . $currentUser->getDisplayName() . " (" . $currentUser->getEmail() . ")";
        $input['sender-address'] = $currentUser->getEmail();
        $input['sender-name'] = $currentUser->getDisplayName();
        $input['lines'] = [$input['question']];
        $input['unsubscribeLink'] = '';
        $input['alert'] =
            config('musora-api.submit_question_subject.'.$brand,''). $currentUser->getDisplayName() . " (" . $currentUser->getEmail() . ")";

        $input['logo'] = config('musora-api.brand_logo_path_for_email.'.$brand);
        $input['type'] = 'layouts/inline/alert';
        $input['recipient'] = config('musora-api.submit_question_recipient.'.$brand);
        $input['success'] =config('musora-api.submit_question_success_message.'.$brand);

        return $this->sendSecure($input);
    }

    /**
     * @param SubmitVideoRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function submitVideo(SubmitVideoRequest $request)
    {
        $input = $request->all();
        $currentUser = $this->userProvider->getCurrentUser();
        $brand = config('railcontent.brand','');

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
        $input['logo'] = config('musora-api.brand_logo_path_for_email.'.$brand);
        $input['type'] = 'layouts/inline/alert';
        $input['success'] =
            "Our team will combine your video with the other student videos to create next months episode. Collaborations are typically released on the first of each month.";

        return $this->sendSecure($input);
    }

    /**
     * @param SubmitStudentFocusFormRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function submitStudentFocusForm(SubmitStudentFocusFormRequest $request)
    {
        $currentUser = $this->userProvider->getCurrentUser();
        $brand = config('railcontent.brand','');
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
            'Student Review Application from:' .
            $currentUser->getDisplayName() .
            '(' .
            $currentUser->getEmail() .
            ')';

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
     * @throws \Exception
     */
    public function sendSecure($input)
    {
        try {
            $this->mailoraMailService->sendSecure($input);
        } catch (\Exception $exception) {
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

}
