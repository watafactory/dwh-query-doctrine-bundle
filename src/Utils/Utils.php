<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Utils;

abstract class Utils
{
    public static function getTypeName(string $className): string
    {
        $parts = explode('\\', $className);

        return end($parts);
    }
}
