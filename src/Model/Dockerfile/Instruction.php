<?php

namespace App\Model\Dockerfile;

use App\Exception\InvalidInstructionUsageException;

class Instruction implements InstructionInterface
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
    const ENTRYPOINT = 'ENTRYPOINT';
    const WORKDIR = 'WORKDIR';
    const ONBUILD = 'ONBUILD';
    const HEALTHCHECK = 'HEALTHCHECK';

    const ARG_TCP = 'tcp';
    const ARG_UDP = 'udp';

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
        if ($alias && !is_numeric($alias)) {
            $args[] = 'AS';
            $args[] = $alias;
        }

        return new self(self::FROM, $args);
    }

    /**
     * @param string $name
     * @param string|null $alias
     * @return Instruction
     */
    public static function fromCustom(string $name, string $alias = null)
    {
        $args = [$name];
        if ($alias && !is_numeric($alias)) {
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
     * @param string $executable
     * @param string[] $params
     * @return Instruction
     */
    public static function cmd(string $executable, array $params): self
    {
        $args = array_merge([$executable], $params);

        return new self(self::CMD, self::addQuotes($args), true);
    }

    /**
     * @param string $key
     * @param string $value
     * @return Instruction
     */
    public static function singleLabel(string $key, string $value): self
    {
        return new self(self::LABEL, [sprintf('%s=%s', $key, self::addQuotes($value))]);
    }

    /**
     * @param string[] $values
     * @return Instruction
     */
    public static function multiLabel(array $values): self
    {
        $arguments = [];
        foreach ($values as $key => $value) {
            $arguments[] = sprintf('%s=%s', $key, self::addQuotes($value));
        }

        return new self(self::LABEL, $arguments);
    }

    /**
     * @param int $port
     * @param string $protocol
     * @return Instruction
     */
    public static function expose(int $port, string $protocol = self::ARG_TCP): self
    {
        return new self(self::EXPOSE, [sprintf('%d/%s', $port, $protocol)]);
    }

    /**
     * @param string $key
     * @param string $value
     * @return Instruction
     */
    public static function singleEnv(string $key, string $value): self
    {
        return new self(self::ENV, [sprintf('%s=%s', $key, self::addQuotes($value))]);
    }

    /**
     * @param string[] $values
     * @return Instruction
     */
    public static function multiEnv(array $values): self
    {
        $arguments = [];
        foreach ($values as $key => $value) {
            $arguments[] = sprintf('%s=%s', $key, self::addQuotes($value));
        }

        return new self(self::ENV, $arguments);
    }

    /**
     * @param string $executable
     * @param array $params
     * @return Instruction
     */
    public static function entrypoint(string $executable, array $params): self
    {
        $args = array_merge([$executable], $params);

        return new self(self::ENTRYPOINT, self::addQuotes($args), true);
    }

    /**
     * @param string[] $volumes
     * @return Instruction
     */
    public static function volume(array $volumes)
    {
        return new self(self::VOLUME, self::addQuotes($volumes), true);
    }

    /**
     * @param string|int $user
     * @param string|int|null $group
     * @return Instruction
     */
    public static function user($user, $group = null)
    {
        $arg = ($group === null ? $user : sprintf('%s:%s', $user, $group));

        return new self(self::USER, [$arg]);
    }

    /**
     * @param string $path
     * @return Instruction
     */
    public static function workdir(string $path): self
    {
        return new self(self::WORKDIR, [$path]);
    }

    /**
     * @param string $key
     * @param string|null $default
     * @return Instruction
     */
    public static function arg(string $key, string $default = null): self
    {
        $arg = ($default === null ? $key : sprintf('%s=%s', $key, $default));

        return new self(self::ARG, [$arg]);
    }

    /**
     * @param InstructionInterface $instruction
     * @return Instruction
     * @throws InvalidInstructionUsageException
     */
    public static function onbuild(InstructionInterface $instruction)
    {
        if ($instruction->is(self::ONBUILD) || $instruction->is(self::FROM)) {
            throw new InvalidInstructionUsageException($instruction, '"ONBUILD" instruction');
        }

        return new self(self::ONBUILD, [(string)$instruction]);
    }

    /**
     * @param string|int $signal SIGNAME or signal unsigned number (http://man7.org/linux/man-pages/man7/signal.7.html)
     * @return Instruction
     */
    public static function stopsignal($signal): self
    {
        return new self(self::STOPSIGNAL, [$signal]);
    }

    /**
     * @return Instruction
     */
    public static function disableHealthcheck(): self
    {
        return new self(self::HEALTHCHECK, ['NONE']);
    }

    public static function healthcheck(
        InstructionInterface $cmd,
        string $interval = '30s',
        string $timeout = '30s',
        string $startPeriod = '0s',
        int $retries = 3
    ): self {
        if (!$cmd->is(self::CMD)) {
            throw new InvalidInstructionUsageException($cmd, '"HEALTHCHECK" instruction');
        }

        return new self(self::HEALTHCHECK, array_merge(self::arrayToKeyValuePairs([
            '--interval' => $interval,
            '--timeout' => $timeout,
            '--start-period' => $startPeriod,
            '--retries' => $retries
        ]), [(string)$cmd]));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function is(string $name): bool
    {
        return $this->name === $name;
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
                $quoted[] = sprintf('"%s"', str_replace('"', '\\"', $element));
            }

            return $quoted;
        }

        return sprintf('"%s"', $target);
    }

    /**
     * @param array $array
     * @return array
     */
    private static function arrayToKeyValuePairs(array $array): array
    {
        $pairs = [];
        foreach ($array as $key => $value) {
            $pairs[] = sprintf('%s=%s', $key, $value);
        }

        return $pairs;
    }
}
