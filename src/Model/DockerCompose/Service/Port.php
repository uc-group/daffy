<?php

namespace App\Model\DockerCompose\Service;

class Port
{
    /**
     * @var string
     */
    private $target;

    /**
     * @var string
     */
    private $published;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var string
     */
    private $mode;

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getPublished(): string
    {
        return $this->published;
    }

    /**
     * @param string $published
     */
    public function setPublished(string $published): void
    {
        $this->published = $published;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }
}