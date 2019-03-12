<?php

namespace App\Model\Dockerfile\Stage;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;
use App\Model\Dockerfile\Instruction;
use App\Model\Dockerfile\InstructionInterface;
use App\Model\Dockerfile\Layer\LayerInterface;

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
     * @param LayerInterface $layer
     */
    public function addLayer(LayerInterface $layer): void
    {
        foreach ($layer->getInstructions() as $instruction) {
            $this->instructions[] = $instruction;
        }
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

    /**
     * @return Image
     */
    public function getBaseImage(): Image
    {
        if ($this->id instanceof ImageId) {
            return $this->id->getImage();
        } else if ($this->id instanceof StageId) {
            return $this->id->getStage()->getBaseImage();
        }
    }
}
