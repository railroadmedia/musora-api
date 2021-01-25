<?php

namespace Railroad\MusoraApi\Controllers;

use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\MusoraApi\Transformers\ContentTransformer;
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
     * ContentController constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param VimeoVideoSourcesDecorator $vimeoVideoDecorator
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        VimeoVideoSourcesDecorator $vimeoVideoDecorator
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->vimeoVideoDecorator = $vimeoVideoDecorator;
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
        $content['comments'] = $comments['results'];
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

        return response()->json($content);
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

        return reply()->json(
            $results->results(),
            [
                'transformer' => ContentTransformer::class,
                'totalResults' => $results->totalResults(),
                'filterOptions' => $results->filterOptions(),
            ]
        );
    }

}
