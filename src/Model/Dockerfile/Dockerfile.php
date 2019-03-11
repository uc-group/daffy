<?php

namespace App\Model\Dockerfile;

use App\Model\Dockerfile\Stage\ImageId;
use App\Model\Dockerfile\Stage\Stage;

class Dockerfile
{
    /**
     * @var string
     */
    private $firstStage;

    /**
     * @var Stage[]
     */
    private $stages = [];

    /**
     * Dockerfile constructor.
     * @param Image $baseImage
     * @param string|null $baseImageAlias
     */
    public function __construct(Image $baseImage, string $baseImageAlias = null)
    {
        if ($baseImageAlias === null) {
            $baseImageAlias = '0';
        }

        $this->firstStage = $baseImageAlias;
        $this->stages[$baseImageAlias] = new Stage(new ImageId($baseImage, $baseImageAlias));
    }

    /**
     * @param InstructionInterface $instruction
     * @param string|null $stageAlias
     */
    public function addInstruction(InstructionInterface $instruction, string $stageAlias = null): void
    {
        $this->getStage($stageAlias)->addInstruction($instruction);
    }

    /**
     * Adds empty line
     * @param string|null $stageAlias
     */
    public function addEmptyLine(string $stageAlias = null): void
    {
        $this->getStage($stageAlias)->addInstruction(Instruction::emptyLine());
    }

    /**
     * @param Stage $stage
     */
    public function addStage(Stage $stage): void
    {
        $this->stages[$stage->getAlias()] = $stage;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $dockerfile = '';
        foreach ($this->stages as $stage) {

            foreach ($stage->toInstructionList() as $i => $instruction) {
                $dockerfile .= sprintf("%s\n", $instruction);
            }
        }

        return $dockerfile;
    }

    /**
     * @param string|null $stageAlias
     * @return Stage
     */
    public function getStage(string $stageAlias = null): Stage
    {
        if ($stageAlias === null) {
            return $this->stages[$this->firstStage];
        } else {
            //TODO: throw stage not found exception
            return $this->stages[$stageAlias];
        }
    }
}
