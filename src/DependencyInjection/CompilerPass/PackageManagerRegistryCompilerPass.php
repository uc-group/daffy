<?php

namespace App\DependencyInjection\CompilerPass;

use App\Model\Dockerfile\PackageManager\PackageManagerRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PackageManagerRegistryCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->findDefinition(PackageManagerRegistry::class);

        $packageManagers = [];
        foreach ($container->findTaggedServiceIds('daffy.package_manager') as $id => $tags) {
            $packageManagerDefinition = $container->findDefinition($id);
            $class = $packageManagerDefinition->getClass();
            $identifier = call_user_func([$class, 'getIdentifier']);
            $packageManagers[$identifier] = new Reference($id);
        }

        $registry->setArguments([$packageManagers]);
    }
}
