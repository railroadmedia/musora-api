<?php

namespace Railroad\MusoraApi\Transformers;

use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    public function transform($comments)
    {
        $responseStructure = config('musora-api.response-structure.comment', []);
        if (!$responseStructure) {
            return $comments;
        }

        $response = [];

        foreach ($comments->values() ?? [] as $index => $comment) {
            foreach ($responseStructure as $key => $item) {
                if (is_array($item)) {
                    foreach ($item as $index2 => $it) {

                        $response[$index][$key][$it] = $comment[$key][$it] ?? false;
                    }
                } elseif (array_key_exists($item, $comment)) {
                    $response[$index][$item] = $comment[$item];
                }
            }
        }

        return $response;
    }
}
