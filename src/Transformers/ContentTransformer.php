<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Helpers\ContentHelper;

class ContentTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content)
    {
        $response = [];

        $responseStructure = config('musora-api.response-structure.catalogues', []);
        foreach ($responseStructure as $item) {
            $fields = explode('.', $item);
            if (count($fields) == 2) {
                $response[$fields[1]] = $content->fetch($item);
            } else {
                $response[$item] = $content[$item] ?? false;
            }
        }

        return $response;
    }
}
