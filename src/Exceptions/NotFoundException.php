<?php

namespace Railroad\MusoraApi\Exceptions;

class NotFoundException extends MusoraAPIException
{
    /**
     * NotFoundException constructor.
     *
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->statusCode = 404;
        $this->title = 'Not found';
    }
}
