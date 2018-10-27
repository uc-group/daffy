<?php

namespace App\Exception;

use App\Model\Dockerfile\Instruction;
use App\Model\Dockerfile\InstructionInterface;

class InvalidInstructionUsageException extends \Exception
{
    /**
     * @param InstructionInterface $instruction
     * @param string $target
     */
    public function __construct(InstructionInterface $instruction, string $target)
    {
        parent::__construct(sprintf('Cannot use "%s" instruction in %s.', $instruction->getName(), $target));
    }
}
