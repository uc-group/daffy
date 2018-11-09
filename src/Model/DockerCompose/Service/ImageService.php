<?php

namespace App\Model\DockerCompose\Service;

use App\Model\DockerCompose\Service;

class ImageService extends Service
{
    /**
     * @var string
     */
    private $image;

    /**
     * @param string $name
     * @param string $image
     */
    public function __construct(string $name, string $image)
    {
        parent::__construct($name);
        $this->image = $image;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = ['image' => $this->image];

        return array_merge($data, parent::toArray());
    }
}