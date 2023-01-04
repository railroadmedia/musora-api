<?php

namespace Railroad\MusoraApi\Decorators;

use Railroad\Railcontent\Support\Collection;

class DifficultyDecorator extends ModeDecoratorBase
{
    /**
     * @param Collection $contents
     * @return Collection
     */
    public function decorate(Collection $contents)
    {
        foreach ($contents as $contentIndex => $content) {
            if (!empty($content['user_id'])) {
                continue;
            }

            $hasDifficulty = false;
            $difficulty = '';

            foreach ($content['fields'] ?? [] as $field) {
                if ($field['key'] == 'difficulty' && !empty($field['value'])) {
                    $hasDifficulty = true;
                    $difficulty = is_numeric($field['value']) ? (int)$field['value'] : $field['value'];
                }
            }

            if (!$hasDifficulty) {
                $contents[$contentIndex]['difficulty_string'] = '';
            } else {
                $contents[$contentIndex]['difficulty_string'] = $difficulty;
                if (!is_string($difficulty)) {
                    if ($difficulty <= 3) {
                        $contents[$contentIndex]['difficulty_string'] = 'beginner '.$difficulty;
                    }
                    if ($difficulty > 3 && $difficulty <= 6) {
                        $contents[$contentIndex]['difficulty_string'] = 'intermediate '.$difficulty;
                    }
                    if ($difficulty > 6) {
                        $contents[$contentIndex]['difficulty_string'] = 'advanced '.$difficulty;
                    }
                }
            }
        }

        return $contents;
    }
}
