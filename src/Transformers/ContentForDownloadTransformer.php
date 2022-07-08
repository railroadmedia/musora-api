<?php

namespace Railroad\MusoraApi\Transformers;

class ContentForDownloadTransformer extends ContentTransformer
{
    public function transform($content, $responseStructure = [])
    {
        if (isset( $content['type'])) {
            $type = (in_array($content['type'], config('railcontent.showTypes')[config('railcontent.brand')] ?? [])) ? 'show-lesson' : $content['type'];

            $responseStructure = array_merge(
                config('musora-api.response-structure.' . $type,[]),
                config('musora-api.response-structure.download',[])
            );
        }

       return parent::transform($content, $responseStructure);
    }
}
