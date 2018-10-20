<?php

namespace App\Model\DockerCompose\Service;

use App\Model\DockerCompose\Util\Args;

class Network
{
    /**
     * @var
     */
    private $name;

    /**
     * @var Args
     */
    private $aliases;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return Args
     */
    public function getAliases(): Args
    {
        return $this->aliases;
    }

    /**
     * @param Args $aliases
     */
    public function setAliases(Args $aliases): void
    {
        $this->aliases = $aliases;
    }
}