<?php

namespace App\Model\Dockerfile\PackageManager;

use Symfony\Component\DependencyInjection\ServiceLocator;

class PackageManagerRegistry extends ServiceLocator
{
    /**
     * @var string[]
     */
    private $identifiers = [];

    /**
     * @param array $factories
     */
    public function __construct(array $factories)
    {
        parent::__construct($factories);
        $this->identifiers[] = array_keys($factories);
    }

    /**
     * @return string[]
     */
    public function all()
    {
        return $this->identifiers;
    }
}
