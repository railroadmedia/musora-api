<?php

namespace Railroad\MusoraApi\Transformers;

use Railroad\Railcontent\Entities\Content;
use Railroad\Railcontent\Helpers\ContentHelper;

class ContentTransformer extends \League\Fractal\TransformerAbstract
{
    public function transform($content)
    {
        $response = [
            'id' => $content['id'],
            'type' => $content['type'],
            'status' => $content['status'],
            'published_on' => $content['published_on'],
            'parent_id' => $content['parent_id'],
            'completed' => $content['completed'] ?? false,
            'progress_percent' => $content['progress_percent'] ?? 0,
            'is_added_to_primary_playlist' => $content['is_added_to_primary_playlist'] ?? false,
            'title' => ContentHelper::getFieldValue($content,'title'),
            'artist'=> ContentHelper::getFieldValue($content,'artist'),
        ];

        return $response;
    }
}
