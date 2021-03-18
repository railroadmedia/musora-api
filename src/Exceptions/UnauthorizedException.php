<?php

namespace Railroad\MusoraApi\Exceptions;

class UnauthorizedException  extends MusoraAPIException
{

    /**
     * UnauthorizedException constructor.
     *
     * @param $message
     */
    public function __construct($message)
    {
         $this->message = $message;
         $this->statusCode = 401;
         $this->title = 'Unauthorized';
    }
}
