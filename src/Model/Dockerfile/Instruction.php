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
        $this->argumentsAsArray = $argumentsAsArray;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return Instruction
     */
    public static function add(string $source, string $destination): self
    {
        return new self(self::ADD, [
            self::addQuotes($source),
            self::addQuotes($destination)
        ], true);
    }

    /**
     * @param array $sources
     * @param string $destination
     * @return Instruction
     */
    public static function addMultipleSources(array $sources, string $destination): self
    {
        $args = self::addQuotes($sources);
        $args[] = self::addQuotes($destination);

        return new self(self::ADD, $args, true);
    }

    /**
     * @param string $source
     * @param string $destination
     * @return Instruction
     */
    public static function copy(string $source, string $destination): self
    {
        return new self(self::COPY, [
            self::addQuotes($source),
            self::addQuotes($destination)
        ], true);
    }

    /**
     * @param array $sources
     * @param string $destination
     * @return Instruction
     */
    public static function copyMultipleSources(array $sources, string $destination): self
    {
        $args = self::addQuotes($sources);
        $args[] = self::addQuotes($destination);

        return new self(self::COPY, $args, true);
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
        $args = [(string)$image];
        if ($alias) {
            $args[] = 'AS';
            $args[] = $alias;
        }

        return new self(self::FROM, $args);
    }

    /**
     * @param string[] $commands
     * @return Instruction
     */
    public static function run(string ...$commands): self
    {
        return new self(self::RUN, [implode (' && ', $commands)]);
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

    /**
     * @param string[]|string $target
     * @return string[]|string
     */
    private static function addQuotes($target)
    {
        if (is_array($target)) {
            $quoted = [];
            foreach ($target as $element) {
                $quoted[] = sprintf('"%s"', $element);
            }

            return $quoted;
        }

        return sprintf('"%s"', $target);
    }
}