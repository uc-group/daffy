<?php

namespace App\Model\Dockerfile\LayerBuilder;

use App\Model\Dockerfile\Layer\Definition;
use Symfony\Component\DependencyInjection\ServiceLocator;

class LayerBuilderRegistry extends ServiceLocator
{
    private $builders = [];

    public function __construct($factories)
    {
        parent::__construct($factories);
        $this->builders = array_keys($factories);
    }

    /**
     * @param Definition $definition
     * @return LayerBuilderInterface|null
     */
    public function getBuilder(Definition $definition): ?LayerBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if (call_user_func([$builder, 'supports'], $definition)) {
                return $this->get($builder);
            }
        }

        return null;
    }
}
