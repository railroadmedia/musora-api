<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;

class UserDataTransformer extends TransformerAbstract
{
    public function transform($profileData)
    {
        $response = [];

        $responseStructure = config('musora-api.response-structure.profile');
        if (!$responseStructure) {
            return $profileData;
        }
        foreach ($responseStructure as $index => $data) {
            $response[$data] = $profileData[$data];
        }

        return $response;
    }
}
