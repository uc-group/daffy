<?php

namespace App\Request;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;

abstract class PostOperatingSystem
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var Image[]
     */
    private $images;

    /**
     * @param string|null $description
     * @param string[] $images
     */
    protected function __construct(string $description = null, array $images = [])
    {
        $this->description = $description;
        $this->images = [];

        foreach ($images as $image) {
            try {
                $image = Image::fromString($image);
                $this->images[] = $image;
            } catch (InvalidNameException $exception) {
                // Ignore invalid names
            }
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }
}
