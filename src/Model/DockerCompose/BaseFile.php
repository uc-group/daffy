<?php

namespace App\Model\DockerCompose;

use Symfony\Component\Yaml\Yaml;

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
     * BaseFile constructor.
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->version = $version;
    }

    /**
     * @param Service $service
     */
    public function addService(Service $service)
    {
        $this->services[] = $service;
    }

    /**
     * @return string
     */
    public function toYaml(): string
    {
        $dockerComposeFile = [];
        foreach ($this->services as $service) {
            $dockerComposeFile[$service->getName()] = $service->toArray();
        }

        $versionPart = Yaml::dump(['version' => sprintf('%s', $this->version)], 2, 2);

        return $versionPart . PHP_EOL . Yaml::dump($dockerComposeFile, 4, 2);
    }
}
