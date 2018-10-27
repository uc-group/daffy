<?php

namespace App\Model\Dockerfile\Layer;

use App\Model\Dockerfile\InstructionInterface;

class InstructionLayer implements LayerInterface
{
    private $instruction;

    /**
     * InstructionLayer constructor.
     * @param InstructionInterface $instruction
     */
    public function __construct(InstructionInterface $instruction)
    {
        $this->instruction = $instruction;
    }

    /**
     * @return InstructionInterface[]
     */
    public function getInstructions(): array
    {
        return [$this->instruction];
    }
}
