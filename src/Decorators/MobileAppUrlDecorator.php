<?php

namespace Railroad\MusoraApi\Decorators;

use Railroad\Railcontent\Entities\CommentEntity;
use Railroad\Railcontent\Entities\ContentEntity;
use Railroad\Railcontent\Support\Collection;

class MobileAppUrlDecorator extends ModeDecoratorBase
{
    public function decorate(Collection $contents)
    {
        foreach ($contents as $content) {
            if ($content['type'] == 'pack' ||
                $content['type'] == 'semester-pack' ||
                $content['type'] == 'pack-bundle') {
                $content['mobile_app_url'] = url()->route('mobile.pack.show', [$content['id']]);
            } elseif ($content['type'] == 'pack-bundle-lesson') {
                $content['mobile_app_url'] = url()->route('mobile.pack.lesson.show', [$content['id']]);
            }  elseif ($content['type'] == 'semester-pack-lesson') {
                $content['mobile_app_url'] = url()->route('mobile.pack.lesson.show', [$content['id']]);
            }
        }

        return $contents;
    }
}
