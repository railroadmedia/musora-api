<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;
use Railroad\MusoraApi\Serializer\DataSerializer;
use Spatie\Fractal\Fractal;

class PacksTransformer extends TransformerAbstract
{
    public function transform($packs)
    {
        $packs['myPacks'] =
            Fractal::create()
                ->collection($packs['myPacks'])
                ->transformWith(ContentTransformer::class)
                ->serializeWith(DataSerializer::class)
                ->toArray();

        $packs['morePacks'] =
            Fractal::create()
                ->collection($packs['morePacks'])
                ->transformWith(ContentTransformer::class)
                ->serializeWith(DataSerializer::class)
                ->toArray();

        $packs['topHeaderPack'] =
            Fractal::create()
                ->item($packs['topHeaderPack'])
                ->transformWith(ContentTransformer::class)
                ->serializeWith(DataSerializer::class)
                ->toArray();

        return $packs;
    }
}
