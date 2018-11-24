<?php

namespace App\Model\Dockerfile\Layer;

use App\Exception\CannotCreateLayerException;
use App\Exception\InstructionNotFound;
use App\Model\Dockerfile\Instruction;
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

    /**
     * @param Definition $definition
     * @return LayerInterface
     * @throws InstructionNotFound
     * @throws CannotCreateLayerException
     */
    public static function create(Definition $definition): LayerInterface
    {
        try {
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
        } catch (\ReflectionException|\TypeError $exception) {
            throw new CannotCreateLayerException($exception->getMessage());
        }
    }
}
