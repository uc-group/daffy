<?php

namespace App\Model\Dockerfile\LayerBuilder;

use App\Entity\OperatingSystem;
use App\Exception\CannotCreateLayerException;
use App\Exception\InstructionNotFound;
use App\Model\Dockerfile\Layer\Definition;
use App\Model\Dockerfile\Layer\InstructionLayer;
use App\Model\Dockerfile\Layer\LayerInterface;

class InstructionLayerBuilder implements LayerBuilderInterface
{
    public static function supports(Definition $definition)
    {
        return $definition->getType() === 'instruction';
    }

    /**
     * @param Definition $definition
     * @param OperatingSystem $operatingSystem
     * @return LayerInterface
     * @throws CannotCreateLayerException
     */
    public function build(Definition $definition, OperatingSystem $operatingSystem): LayerInterface
    {
        try {
            return InstructionLayer::create($definition);
        } catch (InstructionNotFound $exception) {
            throw new CannotCreateLayerException($exception->getMessage());
        }
    }

}
