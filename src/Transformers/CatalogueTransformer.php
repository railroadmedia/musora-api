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
                if($fields[0] == 'data') {
                    $response[$fields[1]] = (ContentHelper::getDatumValue($fields[1]));
                }elseif($fields[0] == 'fields'){
                    $response[$fields[1]] = (ContentHelper::getFieldValue($fields[1]));
                }
            } else {
                $response[$item] = $content[$item] ?? false;
            }
        }

        return $response;
    }
}
