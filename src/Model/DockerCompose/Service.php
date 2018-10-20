<?php

namespace App\Model\DockerCompose;

use App\Model\DockerCompose\Service\Build;
use App\Model\DockerCompose\Service\Port;
use App\Model\DockerCompose\Service\Volume;
use App\Model\DockerCompose\Util\Args;

class Service
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $image;

    /**
     * @var Build
     */
    private $build;

    /**
     * @var Port[]
     */
    private $ports;

    /**
     * @var
     */
    private $networks;

    /**
     * @var Args
     */
    private $dependsOn;

    /**
     * @var Volume[]
     */
    private $volumes;

    /**
     * @var string
     */
    private $containerName;

    /**
     * @var Args|string
     */
    private $dns;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Build
     */
    public function getBuild(): Build
    {
        return $this->build;
    }

    /**
     * @param Build $build
     */
    public function setBuild(Build $build): void
    {
        $this->build = $build;
    }

    /**
     * @return Port[]
     */
    public function getPorts(): array
    {
        return $this->ports;
    }

    /**
     * @param Port[] $ports
     */
    public function setPorts(array $ports): void
    {
        $this->ports = $ports;
    }

    /**
     * @return mixed
     */
    public function getNetworks()
    {
        return $this->networks;
    }

    /**
     * @param mixed $networks
     */
    public function setNetworks($networks): void
    {
        $this->networks = $networks;
    }

    /**
     * @return Args
     */
    public function getDependsOn(): Args
    {
        return $this->dependsOn;
    }

    /**
     * @param Args $dependsOn
     */
    public function setDependsOn(Args $dependsOn): void
    {
        $this->dependsOn = $dependsOn;
    }

    /**
     * @return Volume[]
     */
    public function getVolumes(): array
    {
        return $this->volumes;
    }

    /**
     * @param Volume[] $volumes
     */
    public function setVolumes(array $volumes): void
    {
        $this->volumes = $volumes;
    }

    /**
     * @return string
     */
    public function getContainerName(): string
    {
        return $this->containerName;
    }

    /**
     * @param string $containerName
     */
    public function setContainerName(string $containerName): void
    {
        $this->containerName = $containerName;
    }

    /**
     * @return Args|string
     */
    public function getDns()
    {
        return $this->dns;
    }

    /**
     * @param Args|string $dns
     */
    public function setDns($dns): void
    {
        $this->dns = $dns;
    }
}