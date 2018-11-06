<?php

namespace App\Common;

class Inflector
{
    /**
     * @param string $name
     * @return string
     */
    public static function toNormalizedHyphenCase(string $name): string
    {
        return preg_replace(
            ['/[^a-z0-9_\s\.]/', '/\s+|\.+/', '/_+/'],
            ['', '_', '_'],
            strtolower($name)
        );
    }
}
