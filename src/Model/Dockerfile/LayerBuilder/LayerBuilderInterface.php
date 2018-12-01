<?php

namespace App\Model\Dockerfile\LayerBuilder;

use App\Entity\OperatingSystem;
use App\Exception\CannotCreateLayerException;
use App\Model\Dockerfile\Layer\Definition;
use App\Model\Dockerfile\Layer\LayerInterface;

interface LayerBuilderInterface
{
    public static function supports(Definition $definition);

    /**
     * @param Definition $definition
     * @param OperatingSystem $operatingSystem
     * @return LayerInterface
     * @throws CannotCreateLayerException
     */
    public function build(Definition $definition, OperatingSystem $operatingSystem): LayerInterface;
}
