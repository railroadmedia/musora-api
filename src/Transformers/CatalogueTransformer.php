<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Helpers\ContentHelper;

class CatalogueTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content)
    {
        $responseStructure = config('musora-api.response-structure.catalogues', []);
        if(!$responseStructure){
           return $content->getArrayCopy();
        }

        $response = [];

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
