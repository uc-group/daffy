<?php

namespace App\Exception;

class InstructionNotFound extends \Exception
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Instruction "%s" not found.', $name));
    }
}
