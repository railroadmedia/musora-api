<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;
use Railroad\Railcontent\Helpers\ContentHelper;

class FilterOptionsTransformer extends TransformerAbstract
{
    public function transform($filterOptions)
    {
        $response = [];

        foreach ($filterOptions as $index => $option) {
            if (is_array($option)) {
                foreach ($option as $indx => $op) {
                    if (isset($op['id'])) {
                        $responseStructure = config('musora-api.response-structure.' . $op['type'].'-filter', []);
                        foreach ($responseStructure as $item) {
                            $fields = explode('fields.', $item);
                            $data = explode('data.', $item);
                            if (count($fields) == 2) {
                                $response[$index][$indx][$fields[1]] = ContentHelper::getFieldValue($op, $fields[1]);
                            } elseif (count($data) == 2) {
                                $response[$index][$indx][$data[1]] = ContentHelper::getDatumValue($op, $data[1]);
                            } else {
                                $response[$index][$indx][$item] = $op[$item] ?? false;
                            }
                        }
                    } else {
                        $response[$index][$indx] = $op;
                    }
                }
            } else {
                $response[$index] = $option;
            }
        }

        return $response;
    }
}
