<?php

namespace App\Model\Dockerfile\Layer;

use App\Exception\InstructionNotFound;
use App\Model\Dockerfile\Instruction;

class LayerBuilder
{
    const LAYER_INSTRUCTION = 'instruction';

    /**
     * @param Definition $definition
     * @return InstructionLayer
     * @throws InstructionNotFound
     * @throws \ReflectionException
     */
    public function buildFromArray(Definition $definition): LayerInterface
    {
        switch ($definition->getType()) {
            case self::LAYER_INSTRUCTION:
                $reflection = new \ReflectionClass(Instruction::class);
                $instructionName = $definition->getArgument('instruction');
                if (!$reflection->hasMethod($instructionName)) {
                    throw new InstructionNotFound($instructionName);
                }

                $method = $reflection->getMethod($instructionName);
                $args = [];
                foreach ($method->getParameters() as $parameter) {
                    $args[] = $definition->getArgument($parameter->getName());
                }
                $instruction = $method->invoke(null, ...$args);

                return new InstructionLayer($instruction);
        }
    }
}
