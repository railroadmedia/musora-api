<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Helpers\ContentHelper;

class FilterOptionsTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($filterOptions)
    {
        $response = [];
        foreach ($filterOptions as $index=>$option) {
            if (is_array($option)) {
                foreach ($option as $indx=>$op) {
                    if (isset($op['id'])) {
                        $responseStructure = config('musora-api.response-structure.' . $op['type'], []);
                        foreach ($responseStructure as $item) {
                            $fields = explode('fields.', $item);
                            if (count($fields) == 2) {
                                $response[$index][$indx][$fields[1]] = ContentHelper::getFieldValue($op,$item);
                            } else {
                                $response[$index][$indx][$item] = $op[$item] ?? false;
                            }
                        }
                    }
                }
            } else{
                $response[$index]= $option;
            }
        }

        return $response;
    }
}
