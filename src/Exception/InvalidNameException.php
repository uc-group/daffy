<?php

namespace App\Exception;

class InvalidNameException extends \Exception
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf('"%s" name is invalid.', $name));
    }
}
