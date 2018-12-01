<?php

namespace App\Model\Dockerfile\Layer;

interface OperatingSystemAwareInterface
{
    /**
     * @return array
     */
    public function getPackages(): array;

    /**
     * @return array
     */
    public function getConfigFiles(): array;
}
