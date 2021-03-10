<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;
use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Support\Collection;

class ContentTransformer extends TransformerAbstract
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
                    $response[$index] = [];
                    foreach ($content[$index] as $content2) {
                        $response[$index][] = self::transform($content2, $value);
                    }
                } elseif (array_key_exists($index, $content) && ($content[$index])) {
                    $response[$index] = self::transform($content[$index], $value);
                } elseif (array_key_exists($index, $content)) {
                    $response[$index] = $content[$index];
                }

            } else {
                $response = $this->transformItem($value, $content, $response);
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
    private function transformItem($item, $content, array $response)
    : array {
        //check if we have fields or data in response structure
        $fields = explode('.', $item);

        if (count($fields) >= 2) {
            $key = array_last($fields);
            if ($fields[0] == '*fields') {
                $content = (!is_array($content)) ? $content->getArrayCopy() : $content;
                $response[$key] = ContentHelper::getFieldValues($content, $fields[1]);
            } elseif ($fields[0] == 'fields') {
                if (count($fields) > 2) {
                    $response[$key] = $content->fetch($item, null);
                } else {
                    $content = (!is_array($content)) ? $content->getArrayCopy() : $content;
                    $response[$key] = ContentHelper::getFieldValue($content, $fields[1]);
                }
            } elseif ($fields[0] == '*data') {
                if ($fields[1] == 'sheet_music_image_url') {
                    foreach ($content['data'] as $data) {
                        if ($data['key'] == 'sheet_music_image_url') {
                            $response[$key][] = $data;
                        }
                    }
                } else {
                    $content = (!is_array($content)) ? $content->getArrayCopy() : $content;
                    $response[$key] = ContentHelper::getDatumValues($content, $fields[1]);
                }
            } elseif ($fields[0] == 'data') {
                $content = (!is_array($content)) ? $content->getArrayCopy() : $content;
                $response[$key] = ContentHelper::getDatumValue($content, $fields[1]);
            } else {
                $response[$key] = $content->fetch($item);
            }

            if ($key != 'sheet_music_image_url' && is_array($response[$key])) {
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
            } elseif (array_key_exists($item, $content)) {
                $response[$item] = $content[$item];
            }
        }
        return $response;
    }
}
