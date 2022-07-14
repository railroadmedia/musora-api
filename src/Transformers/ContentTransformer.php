<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Helpers\ContentHelper;
use Railroad\Railcontent\Support\Collection;
use Illuminate\Support\Arr;

class ContentTransformer extends TransformerAbstract
{
    public function transform($content, $responseStructure = [])
    {
        $response = [];

        //The response structure should be defined in musora-api config file per content type
        if (empty($responseStructure) && isset($content['type'])) {

            $type = (in_array($content['type'], config('railcontent.showTypes')[config('railcontent.brand')] ?? [])) ? 'show-lesson' : $content['type'];
            $apiVersion = (config('musora-api.api.version'));
            $responseStructure = config('musora-api.response-structure.'.$type, []);
            if ($apiVersion) {
                $responseStructure = config('response.'.$apiVersion.'.'.$type, []);
            }

        }

        // if the response structure for a specific content type is missing, the content is not transformed
        if (!$responseStructure) {
            return (!is_array($content)) ? $content->getArrayCopy() : $content;
        }

        /** The response structure can contain:
         * - content property (e.g: 'id', 'type')
         * - specific content's field (e.g: 'fields.title')
         * - array with specific content fields (e.g:  '*fields.instructor')
         * - specific content's data (e.g:  'data.description',  'data.thumbnail_url' )
         * - array with specific content data (e.g:  '*data.sheet_music_image_url',  '*data.captions')
         * - array with response structure for content association
               (e.g:   'next_lesson' => [
                                            'id',
                                            'type',
                                            'published_on',
                                            'completed',
                                            'started',
                                            'progress_percent',
                                            'is_added_to_primary_playlist',
                                            'fields.title',
                                            'length_in_seconds',
                                            'data.thumbnail_url',
                                        ],
         *
         */
        foreach ($responseStructure as $index => $value) {

            if (is_array($value)) {
                if (isset($content["$index"]) &&
                    (is_array($content[$index]) || $content[$index] instanceof Collection)) {
                    $response[$index] = [];
                    //we need to transform each content using the response structure from 'value'
                    if (!isset($content[$index]['id'])) {
                        foreach ($content[$index] as $content2) {
                            $response[$index][] = self::transform($content2, $value);
                        }
                    } else{
                        $response[$index] = self::transform($content[$index], $value);
                    }
                } elseif (isset($content["$index"]) && ($content[$index])) {
                    //transform the content using response structure from 'value'
                    $response[$index] = self::transform($content[$index], $value);
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
            $key = Arr::last($fields);
            if ($fields[0] == '*fields') {
                $content = (!is_array($content)) ? $content->getArrayCopy() : $content;
                $response[$key] = ContentHelper::getFieldValues($content, $fields[1]);
            } elseif ($fields[0] == 'fields') {
                if (count($fields) > 2) {
                    if($content instanceof ContentEntity ) {
                        $response[$key] = $content->fetch($item, null);
                    } else {
                       // in the response structure can be one field frm the content field   e.g.:'fields.video.fields.length_in_seconds',
                       if(count($fields) == 4){
                           $fieldValue = ContentHelper::getFieldValue($content, $fields[1]);
                           if($fieldValue){
                               $response[$key] = ContentHelper::getFieldValue($fieldValue->getArrayCopy(), $fields[3]);
                           }
                       } else {
                           $response[$key] = ContentHelper::getFieldValue($content, last($fields));
                       }
                    }
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

            if ($key != 'sheet_music_image_url' && $key != 'length_in_seconds' && is_array($response[$key])) {
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
                    if (is_array($val) && isset($val['id'])) {
                        //nested contents should be transformed
                        $response[$item][$index] = self::transform($val);
                    } else {
                        $response[$item][$index] = $val;
                    }
                }
            } elseif (isset($content[$item]['id'])) {
                //nested content should be transformed
                $response[$item] = self::transform($content[$item]);
            } elseif (isset($content["$item"])) {
                $response[$item] = $content[$item];
            }
        }
        return $response;
    }
}
