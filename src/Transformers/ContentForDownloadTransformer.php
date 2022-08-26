<?php

namespace Railroad\MusoraApi\Transformers;

class ContentForDownloadTransformer extends ContentTransformer
{
    public function transform($content, $responseStructure = [])
    {
        if (isset( $content['type'])) {
            $type = (in_array($content['type'], config('railcontent.showTypes')[config('railcontent.brand')] ?? [])) ? 'show-lesson' : $content['type'];
            $apiVersion = (config('musora-api.api.version'));
            $responseStructure = array_merge(
                config('musora-api.response-structure.' . $type,[]),
                config('musora-api.response-structure.download',[])
            );
            if ($apiVersion) {
                $responseStructure =array_merge(
                    config('response.'.$apiVersion.'.'.$type, []),
                    config('response.'.$apiVersion.'.download', [])
                );
            }
        }

       return parent::transform($content, $responseStructure);
    }
}
