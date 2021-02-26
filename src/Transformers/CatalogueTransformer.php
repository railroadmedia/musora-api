<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Helpers\ContentHelper;

class CatalogueTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($contents)
    {
        $response = [];

        $responseStructure = config('musora-api.response-structure.catalogues', []);
        if (!$responseStructure) {
            return $contents;
        }

        foreach($contents as $index=>$content) {
            if (!is_array($content)) {
                $content = $content->getArrayCopy();
            }

           foreach ($responseStructure as $item) {
                $fields = explode('.', $item);
                if (count($fields) == 2) {
                    if ($fields[0] == 'data') {
                        $response[$index][$fields[1]] = (ContentHelper::getDatumValue($content, $fields[1]));
                    } elseif ($fields[0] == 'fields') {
                        $response[$index][$fields[1]] = (ContentHelper::getFieldValue($content, $fields[1]));
                    }
                } else {
                    $response[$index][$item] = $content[$item] ?? false;
                }
            }
        }

        return $response;
    }
}
