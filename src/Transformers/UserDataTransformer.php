<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;

class UserDataTransformer extends TransformerAbstract
{
    public function transform($profileData)
    {
        $response = [];

        $apiVersion = (config('musora-api.api.version'));
        $responseStructure = config('musora-api.response-structure.profile', []);
        if ($apiVersion) {
            $responseStructure = config('response.'.$apiVersion.'.profile', []);
        }

        if (!$responseStructure) {
            return $profileData;
        }
        foreach ($responseStructure as $index => $data) {
            $response[$data] = $profileData[$data];
        }

        return $response;
    }
}
