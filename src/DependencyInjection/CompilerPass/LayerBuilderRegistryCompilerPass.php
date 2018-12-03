<?php

namespace App\DependencyInjection\CompilerPass;

use App\Model\Dockerfile\LayerBuilder\LayerBuilderRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LayerBuilderRegistryCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->findDefinition(LayerBuilderRegistry::class);

        $builders = [];
        foreach ($container->findTaggedServiceIds('daffy.layer_builder') as $id => $tags) {
            $layerBuilderDefinition = $container->findDefinition($id);
            $class = $layerBuilderDefinition->getClass();
            $builders[$class] = new Reference($id);
        }

        $registry->setArguments([$builders]);
    }
}
