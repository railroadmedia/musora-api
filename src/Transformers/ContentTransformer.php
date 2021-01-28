<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Decorators\Entity\ContentEntityDecorator;
use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Support\Collection;

class ContentTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content)
    {
        $response = [];

        $responseStructure = config('musora-api.response-structure.' . $content['type']);
        if (!$responseStructure) {
            return $content->getArrayCopy();
        }

        foreach ($responseStructure as $index => $item) {
//            if(!is_array($items)){
//                $items = [$items];
//            }

//            foreach($items as $item) {
                $fields = explode('.', $item);
                if (count($fields) == 2) {
                    $response[$fields[1]] = $content->fetch($item);
                    if (is_array($response[$fields[1]])) {
                        foreach ($response[$fields[1]] as $index => $val) {
                            if (isset($val['id'])) {
                                $response[$fields[1]][$index] = self::transform($val);
                            }
                        }
                    }
                } else {
                    if (is_array($content[$item] ?? false)) {
                        foreach ($content[$item] as $index => $val) {
                            if (isset($val['id'])) {
                                $response[$item][$index] = self::transform($val);
                            }
                        }
                    } elseif (isset($content[$item]['id'])) {
                        $response[$item] = self::transform($content[$item]);
                    } else {
                        $response[$item] = $content[$item] ?? false;
                    }
                }
//            }
        }

        return $response;
    }
}
