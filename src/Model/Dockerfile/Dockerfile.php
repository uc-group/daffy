<?php

namespace App\Model\Dockerfile;

class Dockerfile
{
    /**
     * @var Instruction[]
     */
    private $instructions = [];

    /**
     * Dockerfile constructor.
     * @param Image $baseImage
     * @param string|null $baseImageAlias
     */
    public function __construct(Image $baseImage, string $baseImageAlias = null)
    {
        $this->addInstruction(Instruction::from($baseImage, $baseImageAlias));
        $this->addEmptyLine();
    }

    /**
     * @param InstructionInterface $instruction
     */
    public function addInstruction(InstructionInterface $instruction): void
    {
        $this->instructions[] = $instruction;
    }

    /**
     * Adds empty line
     */
    public function addEmptyLine(): void
    {
        $this->instructions[] = Instruction::emptyLine();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $dockerfile = '';
        foreach ($this->instructions as $i => $instruction) {
            $dockerfile .= sprintf("%s\n", $instruction);
        }

        return $dockerfile;
    }
}
