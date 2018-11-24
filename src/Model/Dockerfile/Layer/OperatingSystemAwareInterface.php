<?php

namespace App\Model\Dockerfile\Layer;


use App\Entity\OperatingSystem;

interface OperatingSystemAwareInterface
{
    public function supports(OperatingSystem $operatingSystem): bool;

    /**
     * @param OperatingSystem $operatingSystem
     * @return array
     */
    public function getPackages(OperatingSystem $operatingSystem): array;

    /**
     * @param OperatingSystem $operatingSystem
     * @return array
     */
    public function getConfigFiles(OperatingSystem $operatingSystem): array;
}
