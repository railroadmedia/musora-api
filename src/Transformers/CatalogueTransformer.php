<?php

namespace Railroad\MusoraApi\Transformers;

class CatalogueTransformer extends ContentTransformer
{
    public function transform($contents, $responseStructure = [])
    {
        $response = [];
        $apiVersion = (config('musora-api.api.version'));
        $responseStructure = config('musora-api.response-structure.catalogues', []);
        if ($apiVersion) {
            $responseStructure = config('response.'.$apiVersion.'.catalogues', []);
        }

        if (!$responseStructure) {
            return $contents;
        }

        foreach ($contents as $content) {
            $response[] = parent::transform($content, $responseStructure);
        }

        return $response;
    }
}
