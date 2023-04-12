<?php

namespace Railroad\MusoraApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Services\ResponseService;

class CohortPackController extends Controller
{
    private ProductProviderInterface $productProvider;

    /**
     * @param ProductProviderInterface $productProvider
     */
    public function __construct(
        ProductProviderInterface $productProvider
    ) {
        $this->productProvider = $productProvider;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getTemplate(Request $request)
    {
        $data = $this->productProvider->getCohortTemplate($request->get('slug'));

        return ResponseService::cohort($data);
    }

}
