<?php

namespace App\Model\Dockerfile\Stage;

use App\Model\Dockerfile\Instruction;
use App\Model\Dockerfile\InstructionInterface;

class Stage
{
    /**
     * @var StageIdInterface
     */
    private $id;

    /**
     * @var InstructionInterface[]
     */
    private $instructions;

    /**
     * Stage constructor.
     * @param StageIdInterface $id
     */
    public function __construct(StageIdInterface $id)
    {
        $this->id = $id;
        $this->instructions = [];
    }

    /**
     * @param InstructionInterface $instruction
     */
    public function addInstruction(InstructionInterface $instruction): void
    {
        $this->instructions[] = $instruction;
    }

    /**
     * @return InstructionInterface[]
     */
    public function toInstructionList(): array
    {
        return [
            Instruction::fromCustom($this->id->getName(), $this->id->getAlias()),
            Instruction::emptyLine()
        ] + $this->instructions;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->id->getAlias();
    }
}
