<?php

namespace Railroad\MusoraApi\Transformers;

class CatalogueTransformer extends ContentTransformer
{
    public function transform($contents, $responseStructure = [])
    {
        $response = [];

        $responseStructure = config('musora-api.response-structure.catalogues', []);
        if (!$responseStructure) {
            return $contents;
        }

        foreach ($contents as $content) {
            $response[] = parent::transform($content, $responseStructure);
        }

        return $response;
    }
}
