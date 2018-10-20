<?php

namespace App\Model\DockerCompose\Service;

use App\Model\DockerCompose\Util\Args;

class Volume
{
    const TYPE_VOLUME = 'volume';
    const TYPE_BIND = 'bind';
    const TYPE_TMPFS = 'tmpfs';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $target;

    /**
     * @var bool
     */
    private $readOnly;

    /**
     * @var Args
     */
    private $volume;

    /**
     * @var Args
     */
    private $tmpfs;

    /**
     * @var Args
     */
    private $bind;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

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
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     */
    public function setReadOnly(bool $readOnly): void
    {
        $this->readOnly = $readOnly;
    }

    /**
     * @return Args
     */
    public function getVolume(): Args
    {
        return $this->volume;
    }

    /**
     * @param Args $volume
     */
    public function setVolume(Args $volume): void
    {
        $this->volume = $volume;
    }

    /**
     * @return Args
     */
    public function getTmpfs(): Args
    {
        return $this->tmpfs;
    }

    /**
     * @param Args $tmpfs
     */
    public function setTmpfs(Args $tmpfs): void
    {
        $this->tmpfs = $tmpfs;
    }

    /**
     * @return Args
     */
    public function getBind(): Args
    {
        return $this->bind;
    }

    /**
     * @param Args $bind
     */
    public function setBind(Args $bind): void
    {
        $this->bind = $bind;
    }
}