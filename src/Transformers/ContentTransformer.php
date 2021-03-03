<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Support\Collection;

class ContentTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content, $responseStructure = [])
    {
        $response = [];

        if (empty($responseStructure) && array_key_exists('type', $content)) {

            $type = (in_array($content['type'], config('railcontent.showTypes'))) ? 'show-lesson' : $content['type'];

            $responseStructure = config('musora-api.response-structure.' . $type);

        }

        if (!$responseStructure) {
            return (!is_array($content)) ? $content->getArrayCopy() : $content;
        }

        foreach ($responseStructure as $index => $value) {
            if (is_array($value)) {
                if (array_key_exists($index, $content) &&
                    (is_array($content[$index]) || $content[$index] instanceof Collection)) {
                    foreach ($content[$index] as $content2) {

                        $response[$index][] = self::transform($content2, $value);
                    }
                } elseif (array_key_exists($index, $content) && ($content[$index])) {
                    $response[$index] = self::transform($content[$index], $value);
                } elseif (array_key_exists($index, $content)) {
                    $response[$index] = $content[$index];
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
            $key = array_last($fields) != 'value' ? array_last($fields) : $fields[1];
            if(array_key_exists($key, $content)){
                $response[$key] =$content[$key];
            }elseif($fields[0] == '*fields'){
                $response[$key] = ContentHelper::getFieldValues($content->getArrayCopy(), $fields[1]);
                //dd($response[$key]);
            }elseif ($fields[0] == 'fields'){
                $response[$key] = ContentHelper::getFieldValue($content->getArrayCopy(), $fields[1]);
            }elseif ($fields[0] == '*data')
            {
                $response[$key] = ContentHelper::getDatumValues($content->getArrayCopy(), $fields[1]);
            }elseif ($fields[0] == 'data')
            {
                $response[$key] = ContentHelper::getDatumValue($content->getArrayCopy(), $fields[1]);
            }
            else{
                $response[$key] = $content->fetch($item);
            }

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
                    } else {
                        $response[$item][$index] = $val;
                    }
                }
            } elseif (isset($content[$item]['id'])) {
                $response[$item] = self::transform($content[$item]);
            } elseif(array_key_exists($item, $content)){
                $response[$item] = $content[$item];
            }
        }
        return $response;
    }
}
