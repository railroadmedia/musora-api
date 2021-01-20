<?php

namespace Railroad\MusoraApi\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Decorators\ModeDecoratorBase;
use Railroad\Railcontent\Decorators\DecoratorInterface;
use Railroad\Railcontent\Repositories\ContentRepository;
use Railroad\Railcontent\Services\ContentService;
use Railroad\Railcontent\Support\Collection;

class PacksController extends Controller
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var ProductProviderInterface
     */
    private $productProvider;

    /**
     * PacksController constructor.
     *
     * @param ContentService $contentService
     * @param ProductProviderInterface $productProvider
     */
    public function __construct(
        ContentService $contentService,
        ProductProviderInterface $productProvider
    ) {
        $this->contentService = $contentService;
        $this->productProvider = $productProvider;
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

        return response()->json(
            $results
        );
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
            $topPack = $this->isOwnedOrLocked($topPack, current_user()->getId());
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
            $topPack['next_lesson_url'] = url()->route('mobile.packs.jump-to-next-lesson', [$topPack['id']]);
            $topPack['apple_product_id'] = $this->productProvider->getAppleProductId($topPack['slug']);
            $topPack['google_product_id'] = $this->productProvider->getGoogleProductId($topPack['slug']);

            //            if (UserAccessService::isAdministrator(current_user()->getId())) {
            //                $topPack['is_owned'] = true;
            //            }
        }

        return $topPack;
    }

    /**
     * @param $pack
     * @param int $currentUserId
     * @return mixed
     */
    private function isOwnedOrLocked($pack, int $currentUserId)
    {
        if (empty($pack['permissions']) || (UserAccessService::isAdministrator(current_user()->getId()))) {
            $pack['is_owned'] = true;
            $pack['is_locked'] = false;

            return $pack;
        }

        foreach ($pack['permissions'] as $permission) {
            if ($permission['name'] == 'Independence Made Easy - January 2017 Semester') {
                $permission['name'] = 'Independence Made Easy';
            }

            if (isset($permission['name']) && !(UserAccessService::hasAnyAccessLevelProducts(
                        current_user()->getId(),
                        $permission['name']
                    ) || ($this->userPermissionService->userHasPermissionName(
                        current_user()->getId(),
                        $permission['name']
                    )))) {
                $pack['is_owned'] = false;
                $pack['is_locked'] = true;
            } else {
                $pack['is_owned'] = true;
                $pack['is_locked'] = false;
                break;
            }
        }

        if (!UserAccessService::isPackOnlyOwner($currentUserId) && $pack['is_new']) {
            $pack['is_locked'] = false;
        }

        return $pack;
    }
}
