<?php

namespace Railroad\MusoraApi\Tests\Fixtures;

use Railroad\MusoraApi\Contracts\ChatProviderInterface;

class ChatProvider implements ChatProviderInterface
{

    public function getEmbedUrl()
    : string
    {
        return '';
    }

    public function getCustomStyle()
    : array
    {
        return [];
    }

    public function getRailchatData()
    : array
    {
        return [];
    }
}