<?php

namespace App\Model\DockerCompose\Service\Element;

class Volume
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $target;

    /**
     * @param string $source
     * @param string $target
     */
    public function __construct(string $source, string $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s:%s', $this->source, $this->target);
    }
}
