<?php

namespace Railroad\MusoraApi\Controllers;

use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Decorators\VimeoVideoSourcesDecorator;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentHierarchyService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;
use Railroad\Railcontent\Decorators\ModeDecoratorBase;

class PacksController extends Controller
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
     * @var ContentHierarchyService
     */
    private $contentHierarchyService;

    /**
     * @var ContentRepository
     */
    private $contentRepository;

    /**
     * @var ProductProviderInterface
     */
    private $productProvider;

    /**
     * @var VimeoVideoSourcesDecorator
     */
    private $vimeoVideoDecorator;

    /**
     * PacksController constructor.
     *
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param ContentHierarchyService $contentHierarchyService
     * @param ProductProviderInterface $productProvider
     * @param ContentRepository $contentRepository
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        ContentHierarchyService $contentHierarchyService,
        ProductProviderInterface $productProvider,
        ContentRepository $contentRepository,
        VimeoVideoSourcesDecorator $videoSourcesDecorator
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->contentHierarchyService = $contentHierarchyService;
        $this->contentRepository = $contentRepository;
        $this->productProvider = $productProvider;
        $this->vimeoVideoDecorator = $videoSourcesDecorator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showAllPacks(Request $request)
    {
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        ContentRepository::$availableContentStatues = [ContentService::STATUS_PUBLISHED];
        ContentRepository::$pullFutureContent = false;

        $packs = (new Collection(
            $this->contentService->getFiltered(
                1,
                -1,
                '-progress',
                ['pack', 'semester-pack'],
                [],
                [],
                [],
                [],
                [],
                [],
                false
            )['results']
        ))->keyBy('slug');

        ContentRepository::$bypassPermissions = true;

        $allPacks = $this->contentService->getFiltered(
            1,
            -1,
            '-published_on',
            ['pack', 'semester-pack'],
            [],
            [],
            [],
            [],
            [],
            [],
            false
        )
            ->results()
            ->keyBy('slug');

        $newProducts =
            $allPacks->where('is_new', true)
                ->toArray();

        $myPacks = [];

        $allMyPacks = $packs->toArray();

        foreach ($allMyPacks as $slug => $pack) {
            $myPacks[$slug] = $pack;
            $myPacks[$slug]['is_owned'] = true;
            $myPacks[$slug]['is_locked'] = false;
            $myPacks[$slug]['thumbnail'] = $pack->fetch('data.header_image_url');
            $myPacks[$slug]['pack_logo'] = $pack->fetch('data.logo_image_url');

            $packPrice = $this->productProvider->getPackPrice($slug);

            if (!empty($packPrice)) {
                $myPacks[$slug]['full_price'] = $packPrice['full_price'];
                $myPacks[$slug]['price'] = $packPrice['price'];
            }
        }

        $moreProducts = $this->getMorePacks($myPacks, $allPacks->toArray());

        $topPack = $this->getTopHeaderPack($myPacks, $newProducts);

        $results = [
            'myPacks' => array_values($myPacks) ?? [],
            'morePacks' => array_values($moreProducts) ?? [],
            'topHeaderPack' => $topPack,
        ];

        return ResponseService::packsArray($results);
    }

    /**
     * @param array $myPacks
     * @param array $allPacks
     * @return array
     */
    private function getMorePacks(
        array $myPacks,
        array $allPacks
    ) {
        $moreProducts = [];

        foreach ($allPacks as $slug => $pack) {
            if (!in_array($slug, array_keys($myPacks))) {
                $moreProducts[$slug] = $allPacks[$slug];

                $moreProducts[$slug]['thumbnail'] = $allPacks[$slug]->fetch('data.header_image_url');
                $moreProducts[$slug]['pack_logo'] = $allPacks[$slug]->fetch('data.logo_image_url');
                $moreProducts[$slug]['is_locked'] = true;
                $moreProducts[$slug]['is_owned'] = false;
                $moreProducts[$slug]['apple_product_id'] = $this->productProvider->getAppleProductId($slug);
                $moreProducts[$slug]['google_product_id'] = $this->productProvider->getGoogleProductId($slug);

                $packPrice = $this->productProvider->getPackPrice($slug);
                if (!empty($packPrice)) {
                    $moreProducts[$slug]['full_price'] = $packPrice['full_price'];
                    $moreProducts[$slug]['price'] = $packPrice['price'];
                }
            }
        }

        return $moreProducts;
    }

    /**
     * @param array $myPacks
     * @param array $newProducts
     * @return mixed|null
     */
    private function getTopHeaderPack(
        array $myPacks,
        array $newProducts
    ) {
        $topPack = null;
        foreach ($newProducts as $newProduct) {
            if (!$topPack && ($newProduct->fetch('data.show_on_header', false))) {
                $topPack = $newProduct;
                $topPack['thumbnail'] = $newProduct->fetch('data.header_image_url');
                $topPack['pack_logo'] = $newProduct->fetch('data.logo_image_url');
                $topPack['is_locked'] = false;

                $packPrice = $this->productProvider->getPackPrice($newProduct['slug']);
                if (!empty($packPrice)) {
                    $topPack['full_price'] = $packPrice['full_price'];
                    $topPack['price'] = $packPrice['price'];
                }
            }
        }

        if ($topPack) {
            $topPack = $this->isOwnedOrLocked($topPack, auth()->id());
        }

        if (!$topPack) {
            foreach ($myPacks as $userProduct) {
                $topPack = $userProduct;
                $topPack['thumbnail'] = $topPack->fetch('data.header_image_url');
                $topPack['pack_logo'] = $topPack->fetch('data.logo_image_url');
                $topPack['full_price'] = 0;
                $topPack['price'] = 0;
                $topPack['is_owned'] = true;
                $topPack['is_locked'] = false;

                $packPrice = $this->productProvider->getPackPrice($userProduct['slug']);
                if (!empty($packPrice)) {
                    $topPack['full_price'] = $packPrice['full_price'];
                    $topPack['price'] = $packPrice['price'];
                }

                break;
            }
        }

        if ($topPack) {
            $topPack['next_lesson_url'] = url()->route('mobile.pack.jump-to-next-lesson', [$topPack['id']]);
            $topPack['apple_product_id'] = $this->productProvider->getAppleProductId($topPack['slug']);
            $topPack['google_product_id'] = $this->productProvider->getGoogleProductId($topPack['slug']);
        }

        return $topPack;
    }

    /**
     * @param $pack
     * @param int $currentUserId
     * @return mixed
     */
    private function isOwnedOrLocked(&$pack, int $currentUserId)
    {
        //TODO: Need a provider for user permissions
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
     * @param $packId
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function showPack($packId)
    {
        ContentRepository::$bypassPermissions = true;

        $pack = $this->contentService->getById($packId)->getArrayCopy();

        if (empty($pack)) {
            return response()->json($pack);
        }

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $pack = $this->isOwnedOrLocked($pack, auth()->id());

        $pack['thumbnail'] = ContentHelper::getDatumValue($pack, 'header_image_url');
        $pack['pack_logo'] = ContentHelper::getDatumValue($pack, 'logo_image_url');

        $pack['apple_product_id'] = $this->productProvider->getAppleProductId($pack['slug']);
        $pack['google_product_id'] = $this->productProvider->getGoogleProductId($pack['slug']);

        $packPrice = $this->productProvider->getPackPrice($pack['slug']);

        $pack['full_price'] = $packPrice['full_price'] ?? 0;
        $pack['price'] = $packPrice['price'] ?? 0;

        $index = null;
        $lessons = $this->contentService->getByParentId($pack['id']);

        if ($pack['type'] == 'pack') {
            $pack['bundles'] = $lessons->toArray();
            $pack['bundle_number'] = count($lessons);

            if ($pack['bundle_number'] == 1) {
                $lessons[0]['is_locked'] = $pack['is_locked'];
                $lessons[0]['is_owned'] = $pack['is_owned'];
                $lessons[0]['full_price'] = $packPrice['full_price'] ?? 0;
                $lessons[0]['price'] = $packPrice['price'] ?? 0;
                $lessons[0]['pack_logo'] = ContentHelper::getDatumValue($pack, 'logo_image_url');
                $lessons[0]['apple_product_id'] = $pack['apple_product_id'];
                $lessons[0]['google_product_id'] = $pack['google_product_id'];
                $lessons[0]['next_lesson'] = $lessons[0]['current_lesson'] ?? null;

                return ResponseService::content($lessons[0]);
            }

            $isSet = false;

            foreach ($lessons as $bundleIndex => $bundle) {
                foreach ($bundle['lessons'] ?? [] as $lessonIndex => $lesson) {
                    if ($lesson['completed'] != true && $bundle['completed'] != true && (!$isSet)) {
                        $pack['current_lesson_index'] = $lessonIndex;
                        $pack['next_lesson'] = $bundle['lessons'][$lessonIndex] ?? null;
                        $isSet = true;
                    }
                }
            }

        } else {
            $isSet = false;
            foreach ($lessons as $lessonIndex => $lesson) {
                    if ($lesson['completed'] != true && (!$isSet)) {
                        $pack['current_lesson_index'] = $lessonIndex;
                        $pack['next_lesson'] = $lesson;
                        $isSet = true;
                    }
            }
            $pack['lessons'] = $lessons;
        }

        return ResponseService::content($pack);
    }

    /**
     * @param $lessonId
     * @return array|JsonResponse
     * @throws NonUniqueResultException
     */
    public function showLesson($lessonId)
    {
        $thisLesson = $this->contentService->getById($lessonId);

        if (empty($thisLesson)) {
            return response()->json($thisLesson);
        }

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $thisLesson['total_xp'] = $thisLesson['xp'];

        if (array_key_exists('resources', $thisLesson)) {
            $thisLesson['resources'] = array_values($thisLesson['resources']);
        }

        CommentRepository::$availableContentId = $thisLesson['id'];

        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $thisLesson['comments'] = (new CommentTransformer())->transform($comments['results']);
        $thisLesson['total_comments'] = $comments['total_results'];

        $thisPackBundle =
            $this->contentService->getByChildIdWhereParentTypeIn($lessonId, ['pack-bundle'])
                ->first();

        $pack =
            $this->contentService->getByChildIdWhereParentTypeIn($lessonId, ['semester-pack'])
                ->first();

        if (empty($thisPackBundle) && empty($pack)) {
            return response()->json($thisLesson);
        }

        $parent = $thisPackBundle ?? $pack;

        $parentLessons = $this->contentService->getByParentId($parent['id']);

        $thisLesson['related_lessons'] =
            $parentLessons->whereNotIn('id', [$thisLesson['id']])
                ->values();

        $lessonContent =
            $parentLessons->where('id', $lessonId)
                ->first();

        $nextChild = $parentLessons->getMatchOffset($lessonContent, 1);
        $previousChild = $parentLessons->getMatchOffset($lessonContent, -1);

        $thisLesson['next_content_type'] = 'lesson';

        if (!$pack) {
            $pack =
                $this->contentService->getByChildIdWhereParentTypeIn($thisPackBundle['id'], ['pack'])
                    ->first();
        }
        $pack = $this->isOwnedOrLocked($pack, auth()->id());

        $thisLesson['is_owned'] = $pack['is_owned'];

        $incompleteLesson =
            $thisLesson['related_lessons']->where('completed', '!=', true)
                ->where('id', '!=', $lessonId)
                ->first();

        if (empty($incompleteLesson)) {
            $incompleteBundles =
                $this->contentService->getByParentId($pack['id'])
                    ->where('completed', '!=', true);

            $thisLesson['next_content_type'] = 'bundle';
            $thisLesson['bundle_count'] = $pack->fetch('bundle_count');
            $bundleSiblings = $this->contentHierarchyService->getByParentIds([$pack['id']]);

            foreach ($bundleSiblings as $index => $bundleSibling) {
                if ($bundleSibling['child_id'] == $thisPackBundle['id']) {
                    $nextBundleId =
                        (array_key_exists($index + 1, $bundleSiblings)) ? $bundleSiblings[$index + 1]['child_id'] :
                            null;

                    if (!$nextBundleId) {
                        $nextBundleId = $incompleteBundles->first()['child_id'];
                    }

                    $incompleteLesson = $this->contentService->getById($nextBundleId);
                    if ($incompleteLesson) {
                        $incompleteLesson['lesson_count'] = $this->contentHierarchyService->countParentsChildren(
                            [$incompleteLesson['id']]
                        )[$incompleteLesson['id']];
                    }
                }
            }
        }

        $thisLesson['parent'] = $thisPackBundle;
        $thisLesson['parent']['current_lesson'] = $incompleteLesson;

        $thisLesson['next_lesson'] = $nextChild;
        $thisLesson['previous_lesson'] = $previousChild;

        $this->vimeoVideoDecorator->decorate(new Collection([$thisLesson]));

        return ResponseService::content($thisLesson);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function jumpToNextLesson(Request $request, $id)
    {
        $pack = $this->contentService->getById($id);

        $thisPackBundle = null;

        if ($pack['type'] == 'pack') {

            $lessonsProgressRows =
                $this->contentRepository->connection()
                    ->table('railcontent_content_hierarchy as ch_1')
                    ->select(
                        [
                            'ch_1.parent_id as pack_id',
                            'ch_2.parent_id as pack_bundle_id',
                            'ch_2.child_id as pack_bundle_lesson_id',

                            'up.state as state',
                            'up.progress_percent as progress_percent',
                        ]
                    )
                    ->join('railcontent_content_hierarchy as ch_2', 'ch_2.parent_id', '=', 'ch_1.child_id')
                    ->leftJoin(
                        'railcontent_user_content_progress as up',
                        function (JoinClause $join) {
                            $join->on(
                                'up.content_id',
                                '=',
                                'ch_2.child_id'
                            )
                                ->where(
                                    function (Builder $builder) {
                                        $builder->where('up.user_id', auth()->id());
                                    }
                                );
                        }
                    )
                    ->where('ch_1.parent_id', $id)
                    ->get();

            foreach ($lessonsProgressRows as $childProgressRow) {
                if ($childProgressRow['state'] != 'completed' && $childProgressRow['progress_percent'] != 100) {
                    return $this->showLesson($childProgressRow['pack_bundle_lesson_id']);
                }
            }
        } else {
            if ($pack['type'] == 'semester-pack') {
                $packBundles = $this->contentService->getByParentId($pack['id']);
                $packBundles = $packBundles->sort(
                    function ($a, $b) {
                        return strtotime($a["published_on"]) - strtotime($b["published_on"]);
                    }
                );

                foreach ($packBundles as $packBundle) {
                    if ($packBundle['completed'] == false) {
                        return $this->showLesson($packBundle['id']);
                    }
                }
            }
        }

        return response()->json();
    }

}
