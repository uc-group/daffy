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
        return str_replace(
            ['/\s+/', '/\s|\./', '/[^a-z0-9\-]/'],
            [' ', '-', ''],
            strtolower($name)
        );
    }
}
