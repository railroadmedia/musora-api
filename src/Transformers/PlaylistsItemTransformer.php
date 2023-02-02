<?php

namespace Railroad\MusoraApi\Transformers;

class PlaylistsItemTransformer extends ContentTransformer
{
    public function transform($contents, $responseStructure = [])
    {
        $response = [];
        $apiVersion = (config('musora-api.api.version'));
        $responseStructure = config('musora-api.response-structure.catalogues', []);
        if ($apiVersion) {
            $responseStructure = config('response.'.$apiVersion.'.playlist-item', []);
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
