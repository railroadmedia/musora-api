<?php

namespace Railroad\MusoraApi\Services;

use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;
use Railroad\MusoraApi\Serializer\DataSerializer;
use Railroad\MusoraApi\Transformers\CatalogueTransformer;
use Railroad\MusoraApi\Transformers\ContentForDownloadTransformer;
use Railroad\MusoraApi\Transformers\ContentTransformer;
use Railroad\MusoraApi\Transformers\FilterOptionsTransformer;
use Railroad\MusoraApi\Transformers\LiveTransformer;
use Railroad\MusoraApi\Transformers\PacksTransformer;
use Railroad\MusoraApi\Transformers\ScheduledContentTransformer;
use Railroad\Railcontent\Entities\ContentFilterResultsEntity;
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
        $filters = $data->filterOptions();
        $filters['showSkillLevel'] = true;

        if ($request->get('included_types', []) == ['coach-stream']) {
            $filters = ['content_type' => ['coach-stream']];
        }

        foreach ($filters as $key => $filterOptions) {
            if (is_array($filterOptions)) {
                if (($key != 'content_type') && ($key != 'instructor')) {
                    $filters[$key] = array_diff($filterOptions, ['All']);
                    array_unshift($filters[$key], 'All');
                }
            }
        }

        if($request->has('old_style')){
            $result = $data->results();
        } else {
            $result = (new CatalogueTransformer())->transform($data->results());

            $filters = (new FilterOptionsTransformer())->transform($data->filterOptions());
        }

        return (new ContentFilterResultsEntity(
            [
                'results' => $result,
                'filter_options' => $filters,
                'total_results' => $data->totalResults(),
            ]
        ))->toJsonResponse();
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

    public static function contentForDownload($data)
    {
        return Fractal::create()
            ->item($data)
            ->transformWith(ContentForDownloadTransformer::class)
            ->serializeWith(DataSerializer::class)
            ->toArray();
    }
}

