<?php

namespace Railroad\MusoraApi\Transformers;

use Carbon\Carbon;
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

        $content['cohort'] = $content['cohort']->getAttributes();
        $content['cohort']['cohort_start_date'] =  Carbon::parse($content['cohort']['cohort_start_date'])
            ->format('Y/m/d H:i:s');
        $content['cohort']['cohort_end_date'] =  Carbon::parse($content['cohort']['cohort_end_date'])
            ->format('Y/m/d H:i:s');
        $content['cohort']['enrollment_start_date'] =  Carbon::parse($content['cohort']['enrollment_start_date'])
            ->format('Y/m/d H:i:s');
        $content['cohort']['enrollment_end_date'] =  Carbon::parse($content['cohort']['enrollment_end_date'])
            ->format('Y/m/d H:i:s');

        $content['cohort']['cohort_start_date_fomatted'] =  Carbon::parse($content['cohort']['cohort_start_date'])
            ->format('F jS');
        $content['cohort']['cohort_end_date_fomatted'] =  Carbon::parse($content['cohort']['cohort_end_date'])
            ->format('F jS');
        $content['cohort']['enrollment_start_date_fomatted'] =  Carbon::parse($content['cohort']['enrollment_start_date'])
            ->format('F jS');
        $content['cohort']['enrollment_end_date_fomatted'] =  Carbon::parse($content['cohort']['enrollment_end_date'])
            ->format('F jS');

        if (!$responseStructure) {
            return $content;
        }

        return parent::transform($content, $responseStructure);
    }
}
