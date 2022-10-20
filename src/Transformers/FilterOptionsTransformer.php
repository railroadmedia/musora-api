<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;
use Railroad\Railcontent\Helpers\ContentHelper;

class FilterOptionsTransformer extends TransformerAbstract
{
    public function transform($filterOptions)
    {
        $response = [];
        $apiVersion = (config('musora-api.api.version'));
        $responseStructure = config('musora-api.response-structure.coach-filter', []);
        if ($apiVersion) {
            $responseStructure = config('response.'.$apiVersion.'.coach-filter', []);
        }

        foreach ($filterOptions as $index => $option) {
            if (is_array($option)) {
                foreach ($option as $indx => $op) {
                    if (isset($op['id'])) {
                        foreach ($responseStructure as $item) {
                            $fields = explode('fields.', $item);
                            $data = explode('data.', $item);
                            if (count($fields) == 2) {
                                $response[$index][$indx][$fields[1]] = ContentHelper::getFieldValue($op, $fields[1]);
                            } elseif (count($data) == 2) {
                                foreach ($op['data']??[] as $dataRow) {
                                    if ($dataRow['key'] == $data[1]) {
                                        $response[$index][$indx][$data[1]] = $dataRow['value'];
                                    }
                                }
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
