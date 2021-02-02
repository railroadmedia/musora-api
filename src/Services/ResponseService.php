<?php

namespace Railroad\MusoraApi\Services;

use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;
use Railroad\MusoraApi\Serializer\DataSerializer;
use Railroad\MusoraApi\Transformers\CatalogueTransformer;
use Railroad\MusoraApi\Transformers\ContentTransformer;
use Railroad\MusoraApi\Transformers\FilterOptionsTransformer;
use Railroad\MusoraApi\Transformers\LiveTransformer;
use Railroad\MusoraApi\Transformers\PacksTransformer;
use Railroad\MusoraApi\Transformers\ScheduledContentTransformer;
use Spatie\Fractal\Fractal;

class ResponseService
{

    public static function packsArray($data)
    {
        return Fractal::create()
            ->item($data)
            ->transformWith(PacksTransformer::class)
            ->serializeWith(DataSerializer::class)
            ->toArray();
    }

    public static function catalogue($data, $request)
    {
        return Fractal::create()
            ->collection($data->results())
            ->transformWith(CatalogueTransformer::class)
            ->addMeta(
                [
                    'totalResults' => $data->totalResults(),
                    'page' => $request->get('page', 1),
                    'limit' => $request->get('limit', 10),
                    'filterOptions' => (new FilterOptionsTransformer())->transform($data->filterOptions()),
                ]
            )
            ->toArray();
    }

    public static function content($data)
    {
        return Fractal::create()
            ->item($data)
            ->transformWith(ContentTransformer::class)
            ->serializeWith(DataSerializer::class)
            ->toArray();
    }

    public static function scheduleContent($data)
    {
        return Fractal::create()
            ->collection($data)
            ->transformWith(ScheduledContentTransformer::class)
            ->serializeWith(DataSerializer::class)
            ->toArray();
    }

    public static function live($data)
    {
        return Fractal::create()
            ->item($data)
            ->transformWith(LiveTransformer::class)
            ->serializeWith(DataSerializer::class)
            ->toArray();
    }
}

