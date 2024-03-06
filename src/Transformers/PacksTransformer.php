<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;
use Railroad\MusoraApi\Serializer\DataSerializer;
use Spatie\Fractal\Fractal;

class PacksTransformer extends TransformerAbstract
{
    public function transform($packs)
    {
        $top = null;
        $myPacks = [];
        $morePacks = [];

        $topPackResponseStructure = config('musora-api.response-structure.top-header-pack');
        $myPacksResponseStructure = config('musora-api.response-structure.my-packs');
        $morePacksResponseStructure = config('musora-api.response-structure.more-packs');

        if (empty($topPackResponseStructure)) {
            $top = $packs['topHeaderPack'];
        }

        if (empty($myPacksResponseStructure)) {
            $myPacks = $packs['myPacks'];
        }

        if (empty($morePacksResponseStructure)) {
            $morePacks = $packs['morePacks'];
        }

        if ($packs['topHeaderPack']) {
            foreach ($topPackResponseStructure ?? [] as $key => $item) {
                if (is_array($item)) {
                    foreach ($item as $index2 => $it) {

                        $top[$key][$it] = $packs['topHeaderPack'][$key][$it] ?? false;
                    }
                } else {
                    $top[$item] = $packs['topHeaderPack'][$item] ?? false;
                }
            }
        }

        foreach ($packs['myPacks'] as $index => $pack) {
            foreach ($myPacksResponseStructure ?? [] as $key => $item) {
                if (is_array($item)) {
                    foreach ($item as $index2 => $it) {

                        $myPacks[$index][$key][$it] = $pack[$key][$it] ?? false;
                    }
                } else {
                    $myPacks[$index][$item] = $pack[$item] ?? false;
                }
            }
        }

        foreach ($packs['morePacks'] ?? [] as $index => $pack) {
            foreach ($morePacksResponseStructure ?? [] as $key => $item) {
                if (is_array($item)) {
                    foreach ($item as $index2 => $it) {

                        $morePacks[$index][$key][$it] = $pack[$key][$it] ?? false;
                    }
                } else {
                    $morePacks[$index][$item] = $pack[$item] ?? false;
                }
            }
        }

        return [
            'myPacks' => $myPacks,
            'morePacks' => $morePacks,
            'topHeaderPack' => $top,
            'filters' => $packs['filterOptions'] ?? [],
        ];
    }
}
