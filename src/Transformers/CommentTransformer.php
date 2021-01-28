<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    public function transform($comments)
    {

        $responseStructure = config('musora-api.response-structure.comment', []);
        if (!$responseStructure) {
            return $comments->getArrayCopy();
        }

        $response = [];
        foreach ($comments as $index => $comment) {
            foreach ($responseStructure as $key => $item) {
                if (is_array($item)) {
                    foreach ($item as $it) {
                        $response[$index][$key][] = $comment[$key][$it] ?? false;
                    }
                } else {
                    $response[$index][$item] = $comment[$item] ?? false;
                }
            }
        }

        return $response;
    }
}
