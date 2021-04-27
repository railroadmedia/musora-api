<?php

namespace Railroad\MusoraApi\Transformers;

class LiveTransformer extends ContentTransformer
{
    public function transform($content, $responseStructure = [])
    {
        $responseStructure = config('musora-api.response-structure.live', []);
        if (!$responseStructure) {
            return $content->getArrayCopy();
        }

        return parent::transform($content, $responseStructure);
    }
}
