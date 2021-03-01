<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Support\Collection;

class ContentTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content, $responseStructure = [])
    {
        $response = [];

        if (empty($responseStructure) && array_key_exists('type', $content)) {
            $responseStructure = config('musora-api.response-structure.' . $content['type']);
        }

        if (!$responseStructure) {
            return (!is_array($content))?$content->getArrayCopy():$content;
        }

        foreach ($responseStructure as $index => $value) {
            if (is_array($value)) {
                if (array_key_exists($index, $content) && (is_array($content[$index])|| $content[$index] instanceof Collection)) {
                    foreach ($content[$index] as $content2) {

                        $response[$index][] = self::transform($content2, $value);
                    }
                } elseif (array_key_exists($index, $content) && ($content[$index])) {
                    $response[$index] = self::transform($content[$index], $value);
                } else {
                    $response[$index] = null;
                    continue;
                }

            } else {
                $response = $this->trans($value, $content, $response);
            }
        }

        return $response;
    }

    /**
     * @param array $item
     * @param $content
     * @param array $response
     * @return array
     */
    private function trans($item, $content, array $response)
    : array {

        $fields = explode('.', $item);

        if (count($fields) >= 2) {
            $key = array_last($fields)!='value'?array_last($fields):$fields[1];
            $response[$key] = $content->fetch($item);

            if (is_array($response[$key])) {
                foreach ($response[$key] as $index => $val) {
                    if (isset($val['id'])) {

                        $response[$key][$index] = self::transform($val);
                    }
                }
            }
        } else {
            if (is_array($content[$item] ?? false)) {
                $response[$item] = [];
                foreach ($content[$item] as $index => $val) {
                    if (isset($val['id'])) {
                        $response[$item][$index] = self::transform($val);
                    }else{
                        $response[$item][$index] = $val;
                    }
                }
            } elseif (isset($content[$item]['id'])) {
                $response[$item] = self::transform($content[$item]);
            } else {
                $response[$item] = $content[$item] ?? false;
            }
        }
        return $response;
    }
}
