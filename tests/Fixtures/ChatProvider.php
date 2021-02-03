<?php
namespace Railroad\MusoraApi\Tests\Fixtures;

use Railroad\MusoraApi\Contracts\ChatProviderInterface;

class ChatProvider implements ChatProviderInterface
{

    public function getEmbedUrl()
    {
       return '';
    }

    public function getCustomStyle()
    {
        return [];
    }
}