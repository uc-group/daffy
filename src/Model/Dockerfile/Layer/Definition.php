<?php

namespace App\Model\Dockerfile\Layer;

class Definition
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param string $type
     * @param array $arguments
     */
    private function __construct(string $type, array $arguments)
    {
        $this->type = $type;
        $this->arguments = $arguments;
    }

    /**
     * @param array $definition
     * @return Definition
     */
    public static function fromArray(array $definition): self
    {
        return new self($definition['type'], $definition['args']);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getArgument(string $name, $default = null)
    {
        return $this->arguments[$name] ?? $default;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->type,
            'args' => $this->arguments
        ];
    }
}
