<?php

namespace Railroad\MusoraApi\Decorators;

use Railroad\Railcontent\Entities\CommentEntity;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Support\Collection;

class StripTagDecorator extends ModeDecoratorBase
{
    public function decorate(Collection $entities)
    {
        foreach ($entities as $entityIndex => $entity) {

            if ($entity instanceof ContentEntity) {
                $contentData = $entity['data'] ?? [];
                foreach ($contentData as $index => $data) {
                    if  (in_array($data['key'] ,[ 'description','short_description','long_description'])) {
                        $entities[$entityIndex]['data'][$index]['value'] =
                            strip_tags(html_entity_decode($data['value']));
                    }
                }

                $assignments = $entity['assignments'] ?? [];
                foreach ($assignments as $index => $item) {
                    foreach ($item['data'] as $indexData => $data) {
                        if ($data['key'] == 'description') {
                            $entities[$entityIndex]['assignments'][$index]['data'][$indexData]['value'] =
                                strip_tags(html_entity_decode($data['value']));
                        }
                    }
                }

                $instructors = $entity['instructors'] ?? [];

                foreach ($instructors as $index => $item) {
                    if ($item instanceof ContentEntity) {
                        foreach ($item['data'] as $indexData => $data) {
                            if ($data['key'] == 'biography') {
                                $entities[$entityIndex]['instructors'][$index]['data'][$indexData]['value'] =
                                    strip_tags(html_entity_decode($data['value']));
                            }
                        }
                    }
                }
            }

            if ($entity instanceof CommentEntity) {
                $entities[$entityIndex]['comment'] = strip_tags(html_entity_decode($entity['comment']));
                $replies = $entity['replies'] ?? [];
                foreach ($replies as $index => $reply) {
                    $entities[$entityIndex]['replies'][$index]['comment'] =
                        strip_tags(html_entity_decode($reply['comment']));
                }
            }
        }

        return $entities;
    }
}