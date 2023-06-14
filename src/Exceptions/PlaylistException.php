<?php

namespace Railroad\MusoraApi\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class PlaylistException extends Exception
{
    protected $message;
    protected $title;
    protected $extraData;

    /**
     * MusoraAPIException constructor.
     *
     * @param $message
     * @param string $title
     * @param int $statusCode
     */
    public function __construct($message, $title, $extraData = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->extraData = $extraData;
    }

    /**
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json([
                                    'data' => [
                                        array_merge(
                                            [
                                                "success" => false,
                                                "message" => $this->message,
                                                "title" => $this->title,
                                            ],
                                            $this->extraData
                                        ),
                                    ],
                                ], 404);
    }
}
