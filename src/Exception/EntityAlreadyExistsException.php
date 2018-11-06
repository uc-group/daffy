<?php

namespace App\Exception;

class EntityAlreadyExistsException extends \Exception
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Entity "%s" already exists.', $id));
    }
}
