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
                $content['mobile_app_url'] = url()->route('mobile.musora-api.pack.show', [$content['id']]);
            } elseif ($content['type'] == 'pack-bundle-lesson') {
                $content['mobile_app_url'] = url()->route('mobile.musora-api.pack.lesson.show', [$content['id']]);
            } elseif ($content['type'] == 'semester-pack-lesson') {
                $content['mobile_app_url'] = url()->route('mobile.musora-api.pack.lesson.show', [$content['id']]);
            } elseif ($content['type'] == 'learning-path-level') {
                $content['banner_button_url'] =
                    $content->fetch('current_lesson') ? url()->route('mobile.musora-api.learning-path.lesson.show', [
                            $content->fetch('current_lesson')['id'],
                        ]) : '';
            } elseif ($content['type'] == 'learning-path-course') {
                $content['mobile_app_url'] = url()->route('mobile.musora-api.learning-path.course.show', [
                        $content['id'],
                    ]);

                $content['banner_button_url'] =
                    $content->fetch('current_lesson') ? url()->route('mobile.musora-api.learning-path.lesson.show', [
                            $content->fetch('current_lesson')['id'],
                        ]) : '';
            } elseif ($content['type'] == 'learning-path-lesson') {
                $content['mobile_app_url'] = url()->route('mobile.musora-api.learning-path.lesson.show', [
                        $content['id'],
                    ]);
            } elseif ($content['type'] == 'unit') {
                $content['banner_button_url'] =
                    $content->fetch('next_lesson') ? url()->route('mobile.musora-api.learning-paths.unit-part.show', [
                            $content->fetch('next_lesson')['id'],
                        ]) : '';
            }
        }

        return $contents;
    }
}
