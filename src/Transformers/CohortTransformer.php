<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;

class CohortTransformer extends TransformerAbstract
{
    public function transform($content, $responseStructure = [])
    {
        $apiVersion = (config('musora-api.api.version'));
        $responseStructure = config('musora-api.response-structure.cohort', []);
        if ($apiVersion) {
            $responseStructure = config('response.'.$apiVersion.'.cohort', []);
        }

        if (!$responseStructure) {
            return $content;
        }

        return parent::transform($content, $responseStructure);
    }
}
