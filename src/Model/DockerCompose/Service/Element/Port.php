<?php

namespace App\Model\DockerCompose\Service\Element;

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
     * Port constructor.
     * @param string $target
     * @param null|string $published
     * @param null|string $protocol
     */
    public function __construct(string $target, ?string $published = null, ?string $protocol = null)
    {
        $this->target = $target;
        $this->published = $published;
        $this->protocol = $protocol;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $port = $this->target;
        if ($this->published !== null) {
            $port .= sprintf(':%s', $this->published);
        }
        if ($this->protocol !== null) {
            $port .= sprintf('/%s', $this->protocol);
        }

        return $port;
    }
}
