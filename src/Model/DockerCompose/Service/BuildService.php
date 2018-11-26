<?php

namespace App\Model\DockerCompose\Service;

use App\Model\DockerCompose\Service;

class BuildService extends Service
{
    /**
     * @var string
     */
    private $build;

    /**
     * @param string $name
     * @param string $build
     */
    public function __construct(string $name, string $build)
    {
        parent::__construct($name);
        $this->build = $build;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = ['build' => $this->build];

        return array_merge($data, parent::toArray());
    }
}