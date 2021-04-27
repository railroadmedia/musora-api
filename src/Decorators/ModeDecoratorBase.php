<?php

namespace Railroad\MusoraApi\Decorators;

use Railroad\Railcontent\Decorators\DecoratorInterface;

abstract class ModeDecoratorBase implements DecoratorInterface
{
    public static $decorationMode = DecoratorInterface::DECORATION_MODE_MAXIMUM;
}