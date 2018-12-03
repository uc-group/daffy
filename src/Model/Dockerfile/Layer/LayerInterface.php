<?php

namespace App\Model\Dockerfile\Layer;

use App\Model\Dockerfile\InstructionInterface;

interface LayerInterface
{
    /**
     * Returns list of instructions
     *
     * @return InstructionInterface[]
     */
    public function getInstructions(): array;

    /**
     * @param Definition $definition
     * @return LayerInterface
     */
    public static function create(Definition $definition): LayerInterface;
}
