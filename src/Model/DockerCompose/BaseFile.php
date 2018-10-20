<?php

namespace App\Model\DockerCompose;

class BaseFile
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var Service[]
     */
    private $services;

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return Service[]
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @param Service[] $services
     */
    public function setServices(array $services): void
    {
        $this->services = $services;
    }
}