<?php

namespace Railroad\MusoraApi\Transformers;

class ScheduledContentTransformer extends ContentTransformer
{
    public function transform($content, $responseStructure = [])
    {

        $responseStructure = config('musora-api.response-structure.live-schedule', []);
        if (!$responseStructure) {
            return $content->getArrayCopy();
        }

        return parent::transform($content, $responseStructure);
    }
}
