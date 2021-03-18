<?php

namespace Railroad\MusoraApi\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class MusoraAPIException extends Exception
{
    protected $message;
    protected $title;
    protected $statusCode;

    /**
     * MusoraAPIException constructor.
     *
     * @param $message
     * @param string $title
     * @param int $statusCode
     */
    public function __construct($message, $title, $statusCode)
    {
        $this->title = $title;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    /**
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json(
            [
                'success' => false,
                'errors' => [
                    [
                        'title' => $this->title,
                        'detail' => $this->message,
                    ],
                ],
            ],
            $this->statusCode
        );
    }
}
