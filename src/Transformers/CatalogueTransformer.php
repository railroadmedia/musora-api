<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;
use Railroad\Railcontent\Helpers\ContentHelper;

class CatalogueTransformer extends TransformerAbstract
{
    public function transform($contents)
    {
        $response = [];

        $responseStructure = config('musora-api.response-structure.catalogues', []);
        if (!$responseStructure) {
            return $contents;
        }

        foreach ($contents as $index => $content) {
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
                } elseif (array_key_exists($item, $content)) {
                    $response[$index][$item] = $content[$item];
                }
            }
        }

        return $response;
    }
}
