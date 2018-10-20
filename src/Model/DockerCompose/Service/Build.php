<?php

namespace App\Model\DockerCompose\Service;

use App\Model\DockerCompose\Util\Args;

class Build
{
    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    private $dockerfile;

    /**
     * @var Args
     */
    private $args;

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param string $context
     */
    public function setContext(string $context): void
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getDockerfile(): string
    {
        return $this->dockerfile;
    }

    /**
     * @param string $dockerfile
     */
    public function setDockerfile(string $dockerfile): void
    {
        $this->dockerfile = $dockerfile;
    }

    /**
     * @return Args
     */
    public function getArgs(): Args
    {
        return $this->args;
    }

    /**
     * @param Args $args
     */
    public function setArgs(Args $args): void
    {
        $this->args = $args;
    }
}