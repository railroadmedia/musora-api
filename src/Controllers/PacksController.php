<?php

namespace Railroad\MusoraApi\Controllers;

use Doctrine\ORM\NonUniqueResultException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Collections\PackCollection;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\Railcontent\Decorators\Video\ContentVimeoVideoDecorator;
use Railroad\MusoraApi\Exceptions\NotFoundException;
use Railroad\MusoraApi\Services\DownloadService;
use Railroad\MusoraApi\Services\ResponseService;
use Railroad\MusoraApi\Services\UserProgressService;
use Railroad\MusoraApi\Transformers\CommentTransformer;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Decorators\ModeDecoratorBase;
use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Helpers\FiltersHelper;
use Railroad\Railcontent\Repositories\CommentRepository;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\CommentService;
use Railroad\Railcontent\Services\ContentHierarchyService;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;
use Throwable;

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
     * @var ContentVimeoVideoDecorator
     */
    private $vimeoVideoDecorator;

    /**
     * @var UserProgressService
     */
    private $userProgressService;

    /**
     * @var DownloadService
     */
    private $downloadService;

    /**
     * @param ContentService $contentService
     * @param CommentService $commentService
     * @param ContentHierarchyService $contentHierarchyService
     * @param ProductProviderInterface $productProvider
     * @param ContentRepository $contentRepository
     * @param ContentVimeoVideoDecorator $videoSourcesDecorator
     * @param UserProgressService $userProgressService
     * @param DownloadService $downloadService
     */
    public function __construct(
        ContentService $contentService,
        CommentService $commentService,
        ContentHierarchyService $contentHierarchyService,
        ProductProviderInterface $productProvider,
        ContentRepository $contentRepository,
        ContentVimeoVideoDecorator $videoSourcesDecorator,
        UserProgressService $userProgressService,
        DownloadService $downloadService
    ) {
        $this->contentService = $contentService;
        $this->commentService = $commentService;
        $this->contentHierarchyService = $contentHierarchyService;
        $this->contentRepository = $contentRepository;
        $this->productProvider = $productProvider;
        $this->vimeoVideoDecorator = $videoSourcesDecorator;
        $this->userProgressService = $userProgressService;
        $this->downloadService = $downloadService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showAllPacks(Request $request)
    {
        $adminUser = $request->user()?->isAdmin() ?? false;
        $extraContentStatus = $adminUser ? [ContentService::STATUS_DRAFT] : [];

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;
        ContentRepository::$availableContentStatues = array_merge([ContentService::STATUS_PUBLISHED], $extraContentStatus);
        ContentRepository::$pullFutureContent = false;
        ContentRepository::$getEnrollmentContent = false;

        FiltersHelper::prepareFiltersFields();
        $packs = $this->productProvider->getPacks(FiltersHelper::$includedFields, $request->get('sort', '-progress'));

        ContentRepository::$bypassPermissions = true;
        $allPacks = collect($packs['results']);

        $newProducts =
            $allPacks->where('is_new', true)
                ->toArray();

        $topPack = $this->getTopHeaderPack($packs['results'], $newProducts);

        foreach ($packs['results'] as $index=>$pack){
            $packs['results'][$index]['thumbnail'] = $pack->fetch('data.header_image_url');
            $packs['results'][$index]['pack_logo'] = $pack->fetch('data.logo_image_url');
            $packs['results'][$index]['dark_mode_logo_url'] = $pack->fetch('data.dark_mode_logo_url');
            $packs['results'][$index]['light_mode_logo_url'] = $pack->fetch('data.light_mode_logo_url');
        }

        $results = [
            'myPacks' => $packs['results'],
            'filterOptions' => $packs['filter_options'],
            'morePacks' =>  [],
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

                //Return only the packs that have the price defined in the config file.
                if (!empty($packPrice)) {
                    $moreProducts[$slug]['full_price'] = $packPrice['full_price'];
                    $moreProducts[$slug]['price'] = $packPrice['price'];
                }else{
                    unset($moreProducts[$slug]);
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
            $topPack = $this->isOwnedOrLocked($topPack);
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
            $topPack['next_lesson_url'] = url()->route('mobile.musora-api.pack.jump-to-next-lesson', [$topPack['id']]);
            $topPack['apple_product_id'] = $this->productProvider->getAppleProductId($topPack['slug']);
            $topPack['google_product_id'] = $this->productProvider->getGoogleProductId($topPack['slug']);
        }

        return $topPack;
    }

    /**
     * @param $pack
     * @return mixed
     */
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
     * @param $packId
     * @param Request|null $request
     * @return array
     * @throws Throwable
     */
    public function showPack($packId, Request $request)
    {
        ContentRepository::$bypassPermissions = true;

        $packEntity = $this->contentService->getById($packId);
        throw_if(!$packEntity, new NotFoundException('Pack not found.'));

        $pack = $packEntity->getArrayCopy();
        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $pack = $this->isOwnedOrLocked($pack);

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
                if (empty($lessons[0]['data'])) {
                    $lessons[0]['data'] = $pack['data'];
                }
                $lessons[0]['is_locked'] = $pack['is_locked'];
                $lessons[0]['is_owned'] = $pack['is_owned'];
                $lessons[0]['full_price'] = $packPrice['full_price'] ?? 0;
                $lessons[0]['price'] = $packPrice['price'] ?? 0;
                $lessons[0]['pack_logo'] = ContentHelper::getDatumValue($pack, 'logo_image_url');

                $lessons[0]['apple_product_id'] = $pack['apple_product_id'];
                $lessons[0]['google_product_id'] = $pack['google_product_id'];
                $lessons[0]['next_lesson'] = $lessons[0]['current_lesson'] ?? null;

                $isDownload = $request->get('download', false);
                if ($isDownload && !empty($lessons[0]['lessons'])) {

                    $this->downloadService->attachLessonsDataForDownload($lessons[0]);

                    return ResponseService::contentForDownload($lessons[0]);
                }

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

            $parent =
                $this->contentService->getByChildIdWhereParentTypeIn($pack['id'], ['pack'])
                    ->first();
            if ($parent) {
                $pack['pack_logo'] = $parent->fetch('data.logo_image_url');
            }

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

        $isDownload = $request->get('download', false);
        if ($isDownload && !empty($pack['lessons'])) {

            $this->downloadService->attachLessonsDataForDownload($pack);

            return ResponseService::contentForDownload($pack);
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
        throw_if(!$thisLesson, new NotFoundException('Lesson not found.'));

        ModeDecoratorBase::$decorationMode = DecoratorInterface::DECORATION_MODE_MINIMUM;

        $thisLesson['total_xp'] = $thisLesson['xp'] ?? 0;

        if (isset($thisLesson['resources'])) {
            $thisLesson['resources'] = array_values($thisLesson['resources']);
        }

        CommentRepository::$availableContentId = $thisLesson['id'];

        $comments = $this->commentService->getComments(1, 10, '-created_on');
        $thisLesson['comments'] = (new CommentTransformer())->transform($comments['results']);
        $thisLesson['total_comments'] = $comments['total_comments_and_results'];

        $thisPackBundle =
            $this->contentService->getByChildIdWhereParentTypeIn($lessonId, ['pack-bundle'])
                ->first();

        $pack =
            $this->contentService->getByChildIdWhereParentTypeIn($lessonId, ['pack'])
                ->first();

        $semesterPack =
            $this->contentService->getByChildIdWhereParentTypeIn($lessonId, ['semester-pack'])
                ->first();

        throw_if(
            (empty($thisPackBundle) && empty($pack) && empty($semesterPack)),
            new NotFoundException('Lesson not found.')
        );

        $parent = $thisPackBundle ?? $pack ?? $semesterPack;

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

        if ($thisPackBundle) {
            $pack =
                $this->contentService->getByChildIdWhereParentTypeIn($thisPackBundle['id'], ['pack'])
                    ->first();
            $packBundles = $this->contentService->getByParentId($pack['id']);

            $allPackLessons = [];

            foreach ($packBundles as $_packBundle) {
                $lessons = $this->contentService->getByParentId($_packBundle['id']);
                $allPackLessons = array_merge($allPackLessons, $lessons->toArray());
            }

            $allPackLessons = new Collection($allPackLessons);

            $currentLessonIndex =
                ($allPackLessons->where('id', $thisLesson['id'])
                    ->keys()
                    ->first());
            $nextChild = $allPackLessons->get($currentLessonIndex + 1);
            $previousChild = $allPackLessons->get($currentLessonIndex - 1);
        }

        if (!$pack) {
            $pack =
                $semesterPack
                ??
                $this->contentService->getByChildIdWhereParentTypeIn($thisPackBundle['id'], ['pack'])
                    ->first();
        }
        $pack = $this->isOwnedOrLocked($pack);

        $thisLesson['is_owned'] = $pack['is_owned'];

        $incompleteLesson =
            $thisLesson['related_lessons']->where('completed', '!=', true)
                ->where('id', '!=', $lessonId)
                ->first();

        $thisLesson['parent'] = $thisPackBundle;
        $thisLesson['parent']['next_lesson'] = $incompleteLesson;

        if (empty($incompleteLesson)) {
            $thisLesson['next_content_type'] = 'bundle';
            $thisLesson['bundle_count'] = $pack->fetch('bundle_count');
            $bundleSiblings = $this->contentHierarchyService->getByParentIds([$pack['id']]);

            foreach ($bundleSiblings as $index => $bundleSibling) {
                if ($bundleSibling['child_id'] == $thisPackBundle['id']) {

                    $incompleteBundles =
                        $this->contentService->getByParentId($pack['id'])
                            ->where('completed', '!=', true)
                            ->where('id', '!=', $thisPackBundle['id']);

                    $nextBundle = $incompleteBundles->first();
                    $nextBundleId = $nextBundle ? $nextBundle['child_id'] : null;

                    $incompleteLesson = $this->contentService->getById($nextBundleId);
                    if ($incompleteLesson) {
                        $incompleteLesson['lesson_count'] =
                            $this->contentHierarchyService->countParentsChildren([$incompleteLesson['id']]
                            )[$incompleteLesson['id']];
                        $incompleteLesson = $incompleteLesson->getArrayCopy();
                        
                        if ($thisPackBundle['id'] != $nextBundleId) {
                            $thisPackBundle['next_bundle'] = $incompleteLesson;
                        }
                    }
                }
            }

        }

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

            $lessonsProgressRows = $this->userProgressService->getProgressOnPack($pack['id']);

            foreach ($lessonsProgressRows as $childProgressRow) {
                if ($childProgressRow['state'] != 'completed' && $childProgressRow['progress_percent'] != 100) {
                    return $this->showLesson($childProgressRow['pack_bundle_lesson_id']);
                }
            }
        } else {
            if ($pack['type'] == 'semester-pack') {
                $packBundles = $this->contentService->getByParentId($pack['id']);
                $packBundles = $packBundles->sort(function ($a, $b) {
                    return strtotime($a["published_on"]) - strtotime($b["published_on"]);
                });

                foreach ($packBundles as $packBundle) {
                    if ($packBundle['completed'] == false) {
                        return $this->showLesson($packBundle['id']);
                    }
                }
            }
        }

        return ResponseService::empty();
    }

    /**
     * @param $packSlug
     * @param null $bundleSlug
     * @param null $lessonSlug
     * @return array|JsonResponse
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    public function getDeepLinkForPack($packSlug, $bundleSlug = null, $lessonSlug = null, Request $request)
    {
        ContentRepository::$bypassPermissions = true;

        if ($lessonSlug) {
            $lesson =
                $this->contentService->getBySlugAndType($lessonSlug, 'pack-bundle-lesson')
                    ->first();

            return $this->showLesson($lesson['id'] ?? 0);
        }

        $pack =
            $this->contentService->getBySlugAndType($packSlug, 'pack')
                ->first();

        if ($pack['bundle_count'] == 1) {
            return $this->showPack($pack['id'] ?? 0, $request);
        }

        if ($bundleSlug) {
            $bundle =
                $this->contentService->getBySlugAndType($bundleSlug, 'pack-bundle')
                    ->first();

            return $this->showPack($bundle['id'] ?? 0, $request);
        }

        return $this->showPack($pack['id'] ?? 0, $request);
    }

    /**
     * @param $packSlug
     * @param null $lessonSlug
     * @return mixed
     */
    public function getDeepLinkForSemesterPack($packSlug, $lessonSlug = null, Request $request)
    {
        ContentRepository::$bypassPermissions = true;

        if ($lessonSlug) {
            $lesson =
                $this->contentService->getBySlugAndType($lessonSlug, 'semester-pack-lesson')
                    ->first();

            return $this->showLesson($lesson['id'] ?? 0);
        }

        $pack =
            $this->contentService->getBySlugAndType($packSlug, 'semester-pack')
                ->first();

        return $this->showPack($pack['id'], $request);
    }

    /**
     * @param $packSlug
     * @param null $bundleSlug
     * @param null $bundleId
     * @return array
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    public function getDeepLinkForPianotePack($packSlug, $bundleSlug, $bundleId, Request $request)
    {
        return $this->showPack($bundleId, $request);
    }

    /**
     * @param $packSlug
     * @param $bundleSlug
     * @param $lessonSlug
     * @param $lessonId
     * @return array|JsonResponse
     * @throws NonUniqueResultException
     */
    public function getDeepLinkForPianotePackBundleLesson($packSlug, $bundleSlug, $lessonSlug, $lessonId)
    {
        return $this->showLesson($lessonId);
    }

    /**
     * @param $packSlug
     * @param $lessonSlug
     * @param $lessonId
     * @return array|JsonResponse
     * @throws NonUniqueResultException
     */
    public function getDeepLinkForPianotePackLesson($packSlug, $lessonSlug, $lessonId)
    {
        return $this->showLesson($lessonId);
    }
}
