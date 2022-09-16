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

                if (isset($entity['long_bio'])) {
                    $entities[$entityIndex]['long_bio'] = strip_tags(html_entity_decode($entity['long_bio']), '<a>');
                }

                if (isset($entity['short_bio'])) {
                    $entities[$entityIndex]['short_bio'] = strip_tags(html_entity_decode($entity['short_bio']), '<a>');
                }

                $contentData = $entity['data'] ?? [];
                foreach ($contentData as $index => $data) {
                    $isLesson = in_array($entity['type'], array_merge(
                        config('railcontent.singularContentTypes', []),
                        config('railcontent.showTypes')[$entity['brand']] ?? []
                    ));
                    if(in_array($data['key'] ,['description']) && $isLesson){
                        $entities[$entityIndex]['data'][$index]['value'] = strip_tags(html_entity_decode($data['value']), '<a><p>');
                    }
                    elseif(in_array($data['key'] ,['description','short_description','long_description'])) {
                        $entities[$entityIndex]['data'][$index]['value'] = strip_tags(html_entity_decode($data['value']), '<a>');
                    }
                }

                $assignments = $entity['assignments'] ?? [];
                foreach ($assignments as $index => $item) {
                    foreach ($item['data'] as $indexData => $data) {
                        if ($data['key'] == 'description') {
                            $entities[$entityIndex]['assignments'][$index]['data'][$indexData]['value'] =
                                strip_tags(html_entity_decode($data['value']), '<a>');
                        }
                    }
                }

                $instructors = $entity['instructor'] ?? $entity->fetch('*fields.instructor', []);

                foreach ($instructors as $index => $item) {
                    $entities[$entityIndex]['instructor'][$index] = $item;

                    if ($item instanceof ContentEntity) {
                        foreach ($item['data'] as $indexData => $data) {
                            if ($data['key'] == 'biography') {
                                $entities[$entityIndex]['instructor'][$index]['data'][$indexData]['value'] =
                                    strip_tags(html_entity_decode($data['value']), '<a>');
                            }
                        }
                    }
                }

                //coach biography
                if ($entity['type'] == 'coach' && array_key_exists('biography', $entity)) {
                    $entities[$entityIndex]['biography'] = strip_tags(html_entity_decode($entity['biography']), '<a>');
                }
            }

            if ($entity instanceof CommentEntity) {
                $entities[$entityIndex]['comment'] = strip_tags(html_entity_decode($entity['comment']), '<a>');

                $replies = $entity['replies'] ?? [];
                foreach ($replies as $index => $reply) {
                    $entities[$entityIndex]['replies'][$index]['comment'] =
                        strip_tags(html_entity_decode($reply['comment']), '<a>');
                }
            }
        }

        return $entities;
    }
}
