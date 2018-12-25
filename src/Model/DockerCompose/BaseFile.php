<?php

namespace App\Model\DockerCompose;

use Symfony\Component\Yaml\Yaml;

class BaseFile
{
    const INDENT_SIZE = 2;
    const INLINE_LEVEL = 4;

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
        $this->services = [];
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

        $versionPart = Yaml::dump(['version' => sprintf('%s', $this->version)], self::INLINE_LEVEL, self::INDENT_SIZE);
        if (empty($dockerComposeFile)) {
            return $versionPart;
        }

        return $versionPart . PHP_EOL . Yaml::dump(['services' => $dockerComposeFile], self::INLINE_LEVEL, self::INDENT_SIZE);
    }
}
