<?php

namespace App\Model\DockerCompose;

use App\Model\DockerCompose\Service\Element\Port;
use App\Model\DockerCompose\Service\Element\Volume;

abstract class Service
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $containerName;

    /**
     * @var Port[]|array
     */
    protected $ports;

    /**
     * @var array
     */
    protected $networks;

    /**
     * @var array
     */
    protected $dependsOn;

    /**
     * @var Volume[]|array
     */
    protected $volumes;

    /**
     * @var array
     */
    protected $dns;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->ports = [];
        $this->volumes = [];
        $this->dependsOn = [];
        $this->dns = [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $containerName
     */
    public function setContainerName(?string $containerName)
    {
        $this->containerName = $containerName;
    }

    /**
     * @param Port $port
     */
    public function addPort(Port $port)
    {
        $this->ports[] = $port;
    }

    /**
     * @param Volume $volume
     */
    public function addVolume(Volume $volume)
    {
        $this->volumes[] = $volume;
    }

    /**
     * @param string $network
     */
    public function addNetwork(string $network)
    {
        $this->networks[] = $network;
    }

    /**
     * @param string $dependsOn
     */
    public function addDependsOn(string $dependsOn)
    {
        $this->dependsOn[] = $dependsOn;
    }

    /**
     * @param string $dns
     */
    public function addDns(string $dns)
    {
        $this->dns[] = $dns;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        if ($this->containerName !== null) {
            $data['container_name'] = $this->containerName;
        }
        if (!empty($this->ports)) {
            $ports = [];
            foreach ($this->ports as $port) {
                $ports[] = $port->__toString();
            }
            $data['ports'] = $ports;
        }
        if (!empty($this->networks)) {
            $data['networks'] = $this->networks;
        }
        if (!empty($this->dependsOn)) {
            $data['depends_on'] = $this->dependsOn;
        }
        if (!empty($this->volumes)) {
            $volumes = [];
            foreach ($this->volumes as $volume) {
                $volumes[] = $volume->__toString();
            }
            $data['volumes'] = $volumes;
        }
        if (!empty($this->dns)) {
            $data['dns'] = $this->dns;
        }

        return $data;
    }
}
