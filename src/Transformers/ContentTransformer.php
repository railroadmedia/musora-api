<?php

namespace Railroad\MusoraApi\Transformers;

class ContentTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content, $responseStructure = [])
    {
        $response = [];
        if (empty($responseStructure) && array_key_exists('type', $content)) {
            $responseStructure = config('musora-api.response-structure.' . $content['type']);
        }
        if (!$responseStructure) {
            return $content;
        }

        foreach ($responseStructure as $index => $item) {
            if (is_array($item)) {
                if (is_array($content[$index])) {
                    foreach ($content[$index] as $content2) {
                        $response[$index][] = self::transform($content2, $item);
                    }
                } else {
                    $response[$index] = self::transform($content, $item);
                }
            } else {
                $response = $this->trans($item, $content, $response);
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
        return $response;
    }
}
