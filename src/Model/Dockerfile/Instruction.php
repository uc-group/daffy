<?php

namespace App\Model\Dockerfile;

class Instruction
{
    const ADD = 'ADD';
    const ARG = 'ARG';
    const CMD = 'CMD';
    const COPY = 'COPY';
    const ENV = 'ENV';
    const EXPOSE = 'EXPOSE';
    const FROM = 'FROM';
    const LABEL = 'LABEL';
    const RUN = 'RUN';
    const STOPSIGNAL = 'STOPSIGNAL';
    const USER = 'USER';
    const VOLUME = 'VOLUME';

    /**
     * @var string[]
     */
    private $arguments;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $argumentsAsArray;

    /**
     * @param string $name
     * @param array $arguments
     * @param bool $argumentsAsArray
     */
    private function __construct(string $name, array $arguments = [], bool $argumentsAsArray = false)
    {
        $this->name = $name;
        $this->arguments = $arguments;
        $this->argumentsAsArray = true;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return Instruction
     */
    public static function add(string $source, string $destination): self
    {
        return new self(self::ADD, [
            $source,
            $destination
        ], true);
    }

    /**
     * @param array $source
     * @param string $destination
     * @return Instruction
     */
    public static function addMultipleSources(array $source, string $destination): self
    {
        $args = $source;
        $args[] = $destination;

        return new self(self::ADD, $args, true);
    }

    /**
     * @param string $source
     * @param string $destination
     * @return Instruction
     */
    public static function copy(string $source, string $destination): self
    {
        return new self(self::ADD, [
            $source,
            $destination
        ], true);
    }

    /**
     * @param array $source
     * @param string $destination
     * @return Instruction
     */
    public static function copyMultipleSources(array $source, string $destination): self
    {
        $args = $source;
        $args[] = $destination;

        return new self(self::ADD, $args, true);
    }

    /**
     * @return Instruction
     */
    public static function emptyLine()
    {
        return new self('');
    }

    /**
     * @param Image $image
     * @param string|null $alias
     * @return Instruction
     */
    public static function from(Image $image, string $alias = null)
    {
        return new self(self::FROM, [
            (string)$image,
            $alias ? ['AS', $alias] : []
        ]);
    }

    /**
     * @param array $arguments
     * @return Instruction
     */
    public static function run(array $arguments): self
    {
        return new self(self::RUN, $arguments);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->argumentsAsArray) {
            return sprintf('%s [%s]', $this->name, implode(', ', $this->arguments));
        }

        return trim(sprintf('%s %s', $this->name, implode(' ', $this->arguments)));
    }
}
