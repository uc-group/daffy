<?php

namespace App\Manager;

use App\Model\DockerCompose\Service;
use App\Model\DockerCompose\Service\Element\Volume;
use App\Model\DockerCompose\Service\Element\Port;

class ServiceManager
{
    /**
     * @param Service $service
     * @param string $source
     * @param string $target
     */
    public function addVolume(Service $service, string $source, string $target)
    {
        $service->addVolume(new Volume($source, $target));
    }

    /**
     * @param Service $service
     * @param string $target
     * @param string $published
     * @param string $protocol
     */
    public function addPort(Service $service, string $target, string $published, ?string $protocol = null)
    {
        $service->addPort(new Port($target, $published, $protocol));
    }
}