<?php

namespace Railroad\MusoraApi\Contracts;

interface ChatProviderInterface
{
    public function getEmbedUrl()
    : string;

    public function getCustomStyle()
    : array;

    public function getRailchatData()
    : array;

}
