<?php

namespace Railroad\MusoraApi\Transformers;

class ContentForDownloadTransformer extends ContentTransformer
{
    public function transform($content, $responseStructure = [])
    {
        if (array_key_exists('type', $content)) {
            $type = (in_array($content['type'], config('railcontent.showTypes'))) ? 'show-lesson' : $content['type'];

            $responseStructure = array_merge(
                config('musora-api.response-structure.' . $type,[]),
                config('musora-api.response-structure.download',[])
            );
        }

       return parent::transform($content, $responseStructure);
    }
}
