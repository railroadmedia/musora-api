<?php

namespace Railroad\MusoraApi\Transformers;

class LiveTransformer extends ContentTransformer
{
    public function transform($content, $responseStructure = [])
    {
        $apiVersion = (config('musora-api.api.version'));
        $responseStructure = config('musora-api.response-structure.live', []);
        if ($apiVersion) {
            $responseStructure = config('response.'.$apiVersion.'.live', []);
        }

        if (!$responseStructure) {
            return $content->getArrayCopy();
        }

        return parent::transform($content, $responseStructure);
    }
}
