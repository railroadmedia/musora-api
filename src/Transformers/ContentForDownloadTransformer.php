<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Support\Collection;

class ContentForDownloadTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content, $responseStructure = [])
    {
        $response = [];

        if (empty($responseStructure) && array_key_exists('type', $content)) {
            $type = (in_array($content['type'], config('railcontent.showTypes'))) ? 'show-lesson' : $content['type'];

            $responseStructure = array_merge(
                config('musora-api.response-structure.' . $type,[]),
                config('musora-api.response-structure.download',[])
            );
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
            $response[array_last($fields)] = $content->fetch($item);

            if (is_array($response[array_last($fields)])) {
                foreach ($response[array_last($fields)] as $index => $val) {
                    if (isset($val['id'])) {

                        $response[array_last($fields)][$index] = self::transform($val);
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
            } elseif(array_key_exists($item, $content)){
                $response[$item] = $content[$item];
            }
        }
        return $response;
    }
}
